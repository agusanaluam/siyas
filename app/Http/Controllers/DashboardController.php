<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction\Donation;
use App\Models\Transaction\DonationDetail;
use App\Models\User;
use App\Models\Master\Campaign;
use App\Models\Master\CampaignCategory;
use App\Models\Master\Volunteer;
use Yajra\DataTables\DataTables;
use Illuminate\Database\Eloquent\Builder;


class DashboardController extends Controller
{
    public function index()
    {
        if(Auth::check()){
            switch (Auth::user()->level) {
                case 'leader':
                    return $this->leader();
                    break;
                case 'administrator':
                    return $this->administrator();
                    break;
                case 'root':
                    return $this->administrator();
                    break;
                default:
                    return $this->volunteer();
                    break;
            }
        }

        return redirect("login")->withSuccess('You are not allowed to access');
    }

    public function volunteer() {
        $volunteerId = auth()->user()->volunteer_id;
        
        // Optimasi: gunakan single query untuk statistics
        $donationStats = Donation::where('volunteer_id', $volunteerId)
            ->selectRaw('SUM(total_amount) as total_penerimaan, COUNT(DISTINCT liq_number) as jml_kupon')
            ->first();
        
        $totalPenerimaan = $donationStats->total_penerimaan ?? 0;
        $jmlKupon = $donationStats->jml_kupon ?? 0;
        
        // Optimasi: gunakan distinct untuk program_id
        $program_id = DonationDetail::whereHas('donation', function($q) use ($volunteerId) {
            $q->where('volunteer_id', $volunteerId);
        })->distinct('program_id')->pluck('program_id')->toArray();
        
        $jmlProgram = count($program_id);
        $points = Volunteer::where('id', $volunteerId)->value('points') ?? 0;
        $statistic = array(
            "totalPenerimaan" => $totalPenerimaan,
            "jmlKupon" => $jmlKupon,
            "jmlProgram" => $jmlProgram,
            "points" => $points
        );

        $uncontribCampaign = Campaign::with(['image', 'category'])
            ->where(function($q) use ($program_id) {
                $q->whereRaw('(total_amount/target_amount)*100 < 40')
                  ->orWhereNotIn('id', $program_id);
            })
            ->limit(10)
            ->get();
        $recentDonation = DonationDetail::with(['campaign.image', 'donation'])
            ->whereHas('donation', function($q) use ($volunteerId) {
                $q->where('volunteer_id', $volunteerId);
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        $category = CampaignCategory::select('id', 'name')->get();

        return view('pages.dashboard.volunteer',compact('statistic', 'uncontribCampaign', 'recentDonation','category'));
    }
    public function leader() {
        $groupId = auth()->user()->profile->group_id;
        $volunteerIds = Volunteer::where('group_id', $groupId)->pluck('id');
        
        // Optimasi: gunakan single query untuk statistics
        $donationStats = Donation::whereIn('volunteer_id', $volunteerIds)
            ->selectRaw('SUM(total_amount) as total_penerimaan, COUNT(DISTINCT liq_number) as jml_kupon, COUNT(DISTINCT volunteer_id) as jml_relawan')
            ->first();
        
        $totalPenerimaan = $donationStats->total_penerimaan ?? 0;
        $jmlKupon = $donationStats->jml_kupon ?? 0;
        $jmlRelawan = $donationStats->jml_relawan ?? 0;
        
        // Optimasi: gunakan distinct untuk program_id
        $program_id = DonationDetail::whereHas('donation', function($q) use ($volunteerIds) {
            $q->whereIn('volunteer_id', $volunteerIds);
        })->distinct('program_id')->pluck('program_id')->toArray();
        
        $jmlProgram = count($program_id);
        $statistic = array(
            "totalPenerimaan" => $totalPenerimaan,
            "jmlKupon" => $jmlKupon,
            "jmlProgram" => $jmlProgram,
            "jmlRelawan" => $jmlRelawan
        );

        $campaignProgress = Campaign::with('image')->orderBy('total_amount', 'desc')->limit(8)->get();
        $recentDonation = DonationDetail::with(['campaign.image', 'donation.volunteer'])
            ->whereHas('donation', function (Builder $query) use ($volunteerIds) {
                $query->whereIn('volunteer_id', $volunteerIds);
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        $category = CampaignCategory::select('id', 'name')->get();

        return view('pages.dashboard.leader', compact('statistic', 'campaignProgress', 'recentDonation', 'category'));
    }

    public function administrator() {
        // Optimasi: gunakan single query untuk semua statistics
        $donationStats = Donation::selectRaw('
            SUM(CASE WHEN status = "Completed" THEN total_amount ELSE 0 END) as total_penerimaan,
            SUM(CASE WHEN status = "Completed" AND via_transfer = 1 THEN total_amount ELSE 0 END) as total_penerimaan_rekening,
            SUM(CASE WHEN status != "Completed" AND via_transfer = 0 THEN total_amount ELSE 0 END) as potensi_penerimaan_cash,
            SUM(CASE WHEN status != "Completed" AND via_transfer = 1 THEN total_amount ELSE 0 END) as potensi_penerimaan_rekening,
            COUNT(DISTINCT donatur_name) as jml_uniq_donatur,
            COUNT(DISTINCT liq_number) as jml_kupon
        ')->first();

        $totalPenerimaan = $donationStats->total_penerimaan ?? 0;
        $totalPenerimaanRekening = $donationStats->total_penerimaan_rekening ?? 0;
        $potensiPenerimaanCash = $donationStats->potensi_penerimaan_cash ?? 0;
        $potensiPenerimaanRekening = $donationStats->potensi_penerimaan_rekening ?? 0;
        $jmlUniqDonatur = $donationStats->jml_uniq_donatur ?? 0;
        $jmlKupon = $donationStats->jml_kupon ?? 0;
        
        // Optimasi: tidak perlu load relationship untuk count
        $jmlRelawan = User::where('level','volunteer')->count();
        $jmlProgramAktif = Campaign::where('status',2)->count();

        $campaignProgress = Campaign::with('image')->orderBy('total_amount', 'desc')->limit(10)->get();
        $recentDonation = DonationDetail::with('campaign.image', 'donation.volunteer')->orderBy('created_at','desc')->limit(10)->get();
        $category = CampaignCategory::select('id','name')->get();
        $statistic = array(
            "totalPenerimaan" => $totalPenerimaan,
            "totalPenerimaanRekening" => $totalPenerimaanRekening,
            "potensiPenerimaanCash" => $potensiPenerimaanCash,
            "potensiPenerimaanRekening" => $potensiPenerimaanRekening,
            "jmlUniqDonatur" => $jmlUniqDonatur,
            "jmlKupon" => $jmlKupon,
            "jmlRelawan" => $jmlRelawan,
            "jmlProgramAktif" => $jmlProgramAktif,
//            "dataDonatur" => $dataDonatur,
//            "dataProgram" => $dataProgram,
//            "dataKupon" => $dataKupon,
        );

        return view('pages.dashboard.admin', compact('statistic', 'category', 'campaignProgress', 'recentDonation'));
    }

    public function volunteerLeaderboard(Request $request) {
        if (request()->ajax()) {
            $data = Volunteer::with('group')->with('donationList','user');
            if (auth()->user()->level == 'leader') {
                $data = $data->where('group_id', auth()->user()->profile->group_id);
            }
            return DataTables::of($data->orderBy('points', 'desc')->get())
                ->addIndexColumn()
                ->addColumn('totalProgram', function ($data) {
                    $donation_id = array();
                    if (isset($data->donationList)){
                        foreach ($data->donationList as $donation) {
                            if (!in_array($donation->id, $donation_id, true)) {
                                array_push($donation_id, $donation->id);
                            }
                        }
                    }
                    return count($donation_id);
                })
                ->addColumn('totalReceipt', function ($data) {
                    $total = 0;
                    if (isset($data->donationList)) {
                        foreach($data->donationList as $donation) {
                            $total += $donation->total_amount;
                        }
                    }
                    return "Rp. " . number_format($total, 0, ',', '.');;
                })
                ->addColumn('totalCoupon', function ($data) {
                    if (isset($data->donationList)) {
                        return count($data->donationList);
                    }
                    return 0;
                })
                ->rawColumns(['totalProgram', 'totalReceipt', 'totalCoupon'])
                ->make();
        }
    }

}
