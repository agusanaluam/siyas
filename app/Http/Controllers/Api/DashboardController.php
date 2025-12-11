<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction\Donation;
use App\Models\Transaction\DonationDetail;
use App\Models\User;
use App\Models\Master\Campaign;
use App\Models\Master\CampaignCategory;
use App\Models\Master\Volunteer;
use Illuminate\Database\Eloquent\Builder;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        switch ($user->level) {
            case 'leader':
                return $this->leader($user);
            case 'administrator':
            case 'root':
                return $this->administrator();
            default:
                return $this->volunteer($user);
        }
    }

    private function volunteer($user)
    {
        $volunteerId = $user->volunteer_id;
        
        $donationStats = Donation::where('volunteer_id', $volunteerId)
            ->selectRaw('SUM(total_amount) as total_penerimaan, COUNT(DISTINCT liq_number) as jml_kupon')
            ->first();
        
        $totalPenerimaan = $donationStats->total_penerimaan ?? 0;
        $jmlKupon = $donationStats->jml_kupon ?? 0;
        
        $program_id = DonationDetail::whereHas('donation', function($q) use ($volunteerId) {
            $q->where('volunteer_id', $volunteerId);
        })->distinct('program_id')->pluck('program_id')->toArray();
        
        $jmlProgram = count($program_id);
        $points = Volunteer::where('id', $volunteerId)->value('points') ?? 0;
        
        $statistic = [
            "totalPenerimaan" => $totalPenerimaan,
            "jmlKupon" => $jmlKupon,
            "jmlProgram" => $jmlProgram,
            "points" => $points
        ];

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

        return response()->json([
            'statistic' => $statistic,
            'uncontribCampaign' => $uncontribCampaign,
            'recentDonation' => $recentDonation,
            'category' => $category,
        ]);
    }

    private function leader($user)
    {
        $groupId = $user->profile->group_id;
        $volunteerIds = Volunteer::where('group_id', $groupId)->pluck('id');
        
        $donationStats = Donation::whereIn('volunteer_id', $volunteerIds)
            ->selectRaw('SUM(total_amount) as total_penerimaan, COUNT(DISTINCT liq_number) as jml_kupon, COUNT(DISTINCT volunteer_id) as jml_relawan')
            ->first();
        
        $totalPenerimaan = $donationStats->total_penerimaan ?? 0;
        $jmlKupon = $donationStats->jml_kupon ?? 0;
        $jmlRelawan = $donationStats->jml_relawan ?? 0;
        
        $program_id = DonationDetail::whereHas('donation', function($q) use ($volunteerIds) {
            $q->whereIn('volunteer_id', $volunteerIds);
        })->distinct('program_id')->pluck('program_id')->toArray();
        
        $jmlProgram = count($program_id);
        $statistic = [
            "totalPenerimaan" => $totalPenerimaan,
            "jmlKupon" => $jmlKupon,
            "jmlProgram" => $jmlProgram,
            "jmlRelawan" => $jmlRelawan
        ];

        $campaignProgress = Campaign::with('image')->orderBy('total_amount', 'desc')->limit(8)->get();
        $recentDonation = DonationDetail::with(['campaign.image', 'donation.volunteer'])
            ->whereHas('donation', function (Builder $query) use ($volunteerIds) {
                $query->whereIn('volunteer_id', $volunteerIds);
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        $category = CampaignCategory::select('id', 'name')->get();

        return response()->json([
            'statistic' => $statistic,
            'campaignProgress' => $campaignProgress,
            'recentDonation' => $recentDonation,
            'category' => $category,
        ]);
    }

    private function administrator()
    {
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
        
        $jmlRelawan = User::where('level','volunteer')->count();
        $jmlProgramAktif = Campaign::where('status',2)->count();

        $campaignProgress = Campaign::with('image')->orderBy('total_amount', 'desc')->limit(10)->get();
        $recentDonation = DonationDetail::with('campaign.image', 'donation.volunteer')->orderBy('created_at','desc')->limit(10)->get();
        $category = CampaignCategory::select('id','name')->get();
        
        $statistic = [
            "totalPenerimaan" => $totalPenerimaan,
            "totalPenerimaanRekening" => $totalPenerimaanRekening,
            "potensiPenerimaanCash" => $potensiPenerimaanCash,
            "potensiPenerimaanRekening" => $potensiPenerimaanRekening,
            "jmlUniqDonatur" => $jmlUniqDonatur,
            "jmlKupon" => $jmlKupon,
            "jmlRelawan" => $jmlRelawan,
            "jmlProgramAktif" => $jmlProgramAktif,
        ];

        return response()->json([
            'statistic' => $statistic,
            'category' => $category,
            'campaignProgress' => $campaignProgress,
            'recentDonation' => $recentDonation,
        ]);
    }

    public function volunteerLeaderboard(Request $request)
    {
        $data = Volunteer::with('group', 'donationList', 'user');
        
        if (Auth::user()->level == 'leader') {
            $data = $data->where('group_id', Auth::user()->profile->group_id);
        }
        
        $volunteers = $data->orderBy('points', 'desc')->get()->map(function ($volunteer) {
            $donation_id = [];
            if (isset($volunteer->donationList)) {
                foreach ($volunteer->donationList as $donation) {
                    if (!in_array($donation->id, $donation_id, true)) {
                        array_push($donation_id, $donation->id);
                    }
                }
            }
            
            $total = 0;
            if (isset($volunteer->donationList)) {
                foreach($volunteer->donationList as $donation) {
                    $total += $donation->total_amount;
                }
            }
            
            return [
                'id' => $volunteer->id,
                'name' => $volunteer->user->name ?? '',
                'group' => $volunteer->group->name ?? '',
                'points' => $volunteer->points ?? 0,
                'totalProgram' => count($donation_id),
                'totalReceipt' => $total,
                'totalCoupon' => count($volunteer->donationList ?? []),
            ];
        });

        return response()->json($volunteers);
    }
}

