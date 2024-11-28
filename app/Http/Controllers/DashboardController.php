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
        $donation = Donation::with('detail')->where('volunteer_id', auth()->user()->volunteer_id);
        $totalPenerimaan = $donation->sum('total_amount');
        $jmlKupon = $donation->distinct('liq_number')->count('liq_number');
        $data = $donation->get();
        $program_id = array();
        foreach($data as $row){
            foreach ($row->detail as $detail) {
                if (!in_array($detail->program_id, $program_id, true)) {
                    array_push($program_id, $detail->program_id);
                }
            }
        }
        $jmlProgram = count($program_id);
        $points = Volunteer::where('id', auth()->user()->volunteer_id)->first()->points;
        $statistic = array(
            "totalPenerimaan" => $totalPenerimaan,
            "jmlKupon" => $jmlKupon,
            "jmlProgram" => $jmlProgram,
            "points" => $points
        );

        $uncontribCampaign = Campaign::with('image','category')->whereRaw('(total_amount/target_amount)*100 < 40')->orWhereNotIn('id',$program_id)->get();
        $recentDonation = DonationDetail::with('campaign.image','donation')->whereRelation('donation','volunteer_id', auth()->user()->volunteer_id)->orderBy('created_at', 'desc')->limit(10)->get();
        $category = CampaignCategory::select('id', 'name')->get();

        return view('pages.dashboard.volunteer',compact('statistic', 'uncontribCampaign', 'recentDonation','category'));
    }
    public function leader() {
        $volunteers = Volunteer::select('id')->where('group_id',auth()->user()->profile->group_id)->get();
        $donation = Donation::with('detail')->whereIn('volunteer_id', $volunteers);
        $totalPenerimaan = $donation->sum('total_amount');
        $jmlKupon = $donation->distinct('liq_number')->count('liq_number');
        $jmlRelawan = $donation->distinct('volunteer_id')->count('volunteer_id');
        $data = $donation->get();
        $program_id = array();
        foreach ($data as $row) {
            foreach ($row->detail as $detail) {
                if (!in_array($detail->program_id, $program_id, true)) {
                    array_push($program_id, $detail->program_id);
                }
            }
        }
        $jmlProgram = count($program_id);
        $statistic = array(
            "totalPenerimaan" => $totalPenerimaan,
            "jmlKupon" => $jmlKupon,
            "jmlProgram" => $jmlProgram,
            "jmlRelawan" => $jmlRelawan
        );

        $campaignProgress = Campaign::with('image')->orderBy('total_amount', 'desc')->limit(8)->get();
        $recentDonation = DonationDetail::with('campaign.image', 'donation.volunteer')->whereHas('donation', function (Builder $query) {
            $volunteers = Volunteer::select('id')->where('group_id', auth()->user()->profile->group_id)->get();
            $query->whereIn('volunteer_id', $volunteers);
        })->orderBy('created_at', 'desc')->limit(10)->get();
        $category = CampaignCategory::select('id', 'name')->get();

        return view('pages.dashboard.leader', compact('statistic', 'campaignProgress', 'recentDonation', 'category'));
    }

    public function administrator() {
        $totalPenerimaan = Donation::where('status', 'Completed')->sum('total_amount');
        $totalPenerimaanRekening = Donation::where('status', 'Completed')->where('via_transfer', true)->sum('total_amount');
        $potensiPenerimaanCash = Donation::whereNot('status', 'Completed')->where('via_transfer', false)->sum('total_amount');
        $potensiPenerimaanRekening = Donation::whereNot('status', 'Completed')->where('via_transfer', true)->sum('total_amount');

        $jmlUniqDonatur = Donation::distinct('donatur_name')->count('donatur_name');
        $jmlKupon = Donation::distinct('liq_number')->count('liq_number');
        $jmlRelawan = User::with('volunteer')->where('level','volunteer')->count();
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
