<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Campaign;
use App\Models\Transaction\Donation;
use App\Models\Transaction\DonationDetail;
use App\Models\Transaction\MutationDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use App\Models\Master\Volunteer;
use Carbon\Carbon;




class DonationController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $status = request()->input('status');
            $data = Donation::with('detail');
            if (isset($status)) {
                $data = $data->where('via_transfer', $status);
            }
            switch (auth()->user()->level) {
                case "administrator":
                    break;
                case "leader":
                    $volunteers = Volunteer::select('id')->where('group_id', auth()->user()->profile->group_id)->get();
                    $data = $data->whereIn('volunteer_id', $volunteers);
                    break;
                default:
                    $data = $data->where('volunteer_id', auth()->user()->volunteer_id);
                    break;
            }
            return DataTables::of($data->get())
                ->addIndexColumn()
                ->editColumn('trans_date', function ($data) {
                    $formatDate = date('d-M-Y', strtotime($data->trans_date));
                    return $formatDate;
                })
                ->editColumn('total_amount', function ($data) {
                $formatCurrency = "Rp. " . number_format($data->total_amount, 0, ',', '.');
                return $formatCurrency;
                })
                ->addColumn('paymentLabel', function ($data) {
                    switch ($data->via_transfer) {
                        case true:
                            $formatType = "Transfer";
                            $badge = 'info';
                            break;
                        case false:
                            $formatType = "Cash";
                            $badge = 'secondary';
                            break;
                    }

                    return '<span class="badge badge-md bg-' . $badge . '">' . $formatType . '</span>';
                })
                ->addColumn('action', function ($data) {
                    $button = '<a class="action-set" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="true">
							<i class="fa fa-ellipsis-v" aria-hidden="true"></i>
						</a>
						<ul class="dropdown-menu" id="'.$data->id. '">
							<li>
								<a href="javascript:void(0);" class="dropdown-item donation-detail" data-bs-toggle="modal" data-bs-target="#donation-details"><i class="fa fa-eye info-img"></i>Donation Detail</a>
							</li>';
                        if ( ($data->status == 'at Volunteer') || ((auth()->user()->level != 'volunteer') && ($data->status != 'Completed'))) {
                            $button .= '<li>
								<a href="javascript:void(0);" class="dropdown-item edit-donation" data-bs-toggle="modal" data-bs-target="#edit-donation"><i class="fa fa-pen info-img"></i>Edit Donation</a>
							</li>
                            <li>
								<a href="javascript:void(0);" class="dropdown-item confirm-text mb-0 cancel-donation"><i class="info-img fa fa-trash-can"></i>Cancel Donation</a>
							</li>';
                        }
                        if ((auth()->user()->level != 'volunteer') && ($data->status != 'Completed')) {
							$button .= '<li>
								<a href="javascript:void(0);" class="dropdown-item approve-donation"><i class="fa fa-check info-img"></i>Accept Donation</a>
							</li>';
                        }
						$button .= '</ul>';
                        return $button;
                })
                ->rawColumns(['paymentLabel', 'action'])
                ->make();
        }
        $status = "";
        $campaign = Campaign::with('image')->get();
        return view('pages.donation.donation-list', compact('campaign',"status"));
    }

    function getTransferDonation()
    {
        $status = true;
        return view('pages.donation.donation-list', compact('status'));
    }

    public function history()
    {
        $query = DonationDetail::with('donation.volunteer')->with('campaign.image');
        switch (auth()->user()->level) {
            case "administrator":
                break;
            case "leader":
                $query = $query->whereHas('donation', function (Builder $query) {
                    $volunteers = Volunteer::select('id')->where('group_id', auth()->user()->profile->group_id)->get();
                    $query->whereIn('volunteer_id', $volunteers);
                });
                break;
            default:
                $query = $query->whereRelation('donation','volunteer_id', auth()->user()->volunteer_id);
                break;
        }
        $data = $query->orderBy('created_at','desc')->get();
        return view('pages.donation.donation-history', compact('data'));
    }

    public function search(Request $request)
    {
        $donationRequest = MutationDetail::select('donation_id')->get();
        $data = Donation::with('detail')->whereNotIn('id',$donationRequest);
        $queryStatus = $request->query('status');
        if (isset($queryStatus)) {
            $data->where('status', $queryStatus);
        }
        $queryTransfer = $request->query('via_transfer');
        if (isset($queryTransfer)) {
            $data->where('via_transfer', $queryTransfer);
        }
        return response()->json(['success' => true, 'message' => 'success get details', 'data' => $data->get()]);
    }

    function check($liq)
    {
        $liq = Donation::where('liq_number', $liq)->first();
        if ($liq) {
            return response()->json(['success' => false,'message' => 'Duplicate Liq Number']);
        }
        return response()->json(['success' => true]);
    }

    public function create($liq)
    {
        $campaign = Campaign::with('image')->get();

        return view('pages.donation.add-donation', compact('campaign', 'liq'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'liq_number' => 'required|string|unique:t_donation,liq_number',
            'donatur_name' => 'required|string|max:150',
            'donatur_phone' => 'required|string',
            'payment_method' => 'required',
            'campaign_id.*' => 'required',
            'amount.*' => 'required',
            'total_amount'=>'required',

        ]);

        DB::beginTransaction();

        try {
            $path = "";
            if ($request->hasFile('reference_picture')) {
                // Upload gambar baru
                $extension = $request->file('reference_picture')->getClientOriginalExtension();
                $filenameSimpan = Str::random(16) . '_' . time() . '.' . $extension;
                $request->file('reference_picture')->storeAs('public/payment_reference/' . $filenameSimpan);
                $path = 'payment_reference/' . $filenameSimpan; // Simpan foto baru
            }
            $reference_code = $request->reference_code;
            if ($request->payment_method == 'transfer') {
                if ($reference_code == "") {
                    $reference_code = $path;
                }
            }

            $donation = Donation::create([
                'volunteer_id' => auth()->user()->volunteer_id,
                'liq_number' => $request->liq_number,
                'donatur_name' => $request->donatur_name,
                'donatur_phone' => $request->donatur_phone,
                'donatur_address' => $request->donatur_address,
                'description' => $request->description,
                'total_amount' => $request->total_amount,
                'trans_date' => date("Y-m-d", strtotime(now())),
                'via_transfer' => ($request->payment_method == 'transfer')? true: false,
                'reference_code' => $reference_code,
                'reference_picture' => $path,
                'created_by' => auth()->user()->id,
                'status' => ($request->payment_method == 'transfer') ? 'at Rekening' : 'at Volunteer',
            ]);

            foreach ($request->campaign_id as $key => $campaign) {
                DonationDetail::create([
                    'donation_id' => $donation->id,
                    'program_id' => $campaign,
                    'amount' => $request->amount[$key],
                ]);
            }


            DB::Commit();

            return response()->json(['success' => true, 'message' => 'Success add donation.']);


        } catch (\Exception $e) {

            DB::Rollback();
            return response()->json(['success' => false, 'message' => 'Oops. Something wrong: ' . $e->getMessage()], 500);
        }
    }

    public function detailbyID($id) {
        $donation = Donation::with('detail.campaign.image','volunteer')->where('id', $id)->first();
        return response()->json(['success' => true, 'message' => 'success get details', 'data'=> $donation]);
    }

    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'id' => 'required',
            'liq_number'    => 'required|string',
            'donatur_name' => 'required|string|max:150',
            'donatur_phone' => 'required|string',
            'payment_method' => 'required',
            'campaign_id.*' => 'required',
            'amount.*' => 'required',
            'total_amount' => 'required',

        ]);

        DB::beginTransaction();

        try {
            $donation = Donation::find($request->id);
            if (!$donation) {
                return response()->json(['success' => false,'message' => 'Resources not found'], 404);
            }

            $path = "";
            if ($request->hasFile('reference_picture')) {
                // Upload gambar baru
                $extension = $request->file('reference_picture')->getClientOriginalExtension();
                $filenameSimpan = Str::random(16) . '_' . time() . '.' . $extension;
                $request->file('reference_picture')->storeAs('public/payment_reference/' . $filenameSimpan);
                $path = 'payment_reference/' . $filenameSimpan; // Simpan foto baru
            }

            $donation->liq_number = $request->liq_number;
            $donation->donatur_name = $request->donatur_name;
            $donation->donatur_phone = $request->donatur_phone;
            $donation->donatur_address = $request->donatur_address;
            $donation->description = $request->description;
            $donation->total_amount = $request->total_amount;
            $donation->trans_date = date("Y-m-d", strtotime($request->trans_date));
            $donation->via_transfer = ($request->payment_method == 'transfer') ? true : false;
            $donation->reference_code = $request->reference_code;
            $donation->reference_picture = $path;
            $donation->updated_by = auth()->user()->id;
            $donation->updated_at = date("Y-m-d", strtotime(now()));
            $donation->save();

            $donation->detail()->whereNotIn('program_id', array_values($request->detail_id))->delete();
            foreach ($request->detail_id as $key => $detail) {
                if (isset($detail)) {
                    // Update atau insert jika ID produk ditemukan
                    $donation->detail()->updateOrCreate(
                        ['id' => $detail,'program_id' => $request->campaign_id[$key]],
                        [
                            'amount' => $request->amount[$key]
                        ]
                    );
                } else {
                    // Insert produk baru jika ID tidak ditemukan
                    $donation->detail()->create([
                        'donation_id' => $donation->id,
                        'program_id' => $request->campaign_id[$key],
                        'amount' => $request->amount[$key],

                    ]);
                }
            }


            DB::Commit();

            return response()->json(['success' => true, 'message' => 'Success update donation, please check your donation list']);
        } catch (\Exception $e) {

            DB::Rollback();
            return response()->json(['success' => false, 'message' => 'Oops. Something wrong: ' . $e->getMessage()], 500);
        }
    }

    public function approve($id)
    {
        try {
            $data = Donation::findOrFail($id);
            if ($data->status == 'Completed') {
                return response()->json(['success' => false, 'message' => 'Oops. Something wrong: This transaction has been completed'], 422);
            }
            if ($data->status == 'Approved') {
                return response()->json(['success' => true, 'message' => 'This transaction has been approved before']);
            }
            $data->status = "Approved";
            if ($data->via_transfer) {
                if ((auth()->user()->level == 'administrator') || (auth()->user()->level == 'root')) {
                    $data->status = "Completed";
                } else {
                    return response()->json(['success' => false, 'message' => 'This transaction is via transfer. Will be approve by Admin'], 422);
                }
            }
            $data->approve_lead = date("Y-m-d H:i:s", strtotime(now()));
            $data->updated_by = auth()->user()->id;
            $data->updated_at = date("Y-m-d H:i:s", strtotime(now()));
            $data->save();

            $volunteer = Volunteer::find($data->volunteer_id);
            if ($data->total_amount > 200000) {
                $volunteer->points += 5;
            } else if ($data->total_amount > 150000 && $data->total_amount < 199999) {
                $volunteer->points += 4;
            } else if ($data->total_amount > 100000 && $data->total_amount < 149999) {
                $volunteer->points += 3;
            } else if ($data->total_amount > 50000 && $data->total_amount < 99999) {
                $volunteer->points += 2;
            } else {
                $volunteer->points += 1;
            }
            $volunteer->save();

            return response()->json(['success' => true, 'message' => 'Success update data']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Oops. Something wrong: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $data = Donation::findOrFail($id);
            if ($data->status == 'Completed') {
                return response()->json(['success' => false, 'message' => 'Oops. Something wrong: This transaction has been completed'], 422);
            }
            $mutationDetail = MutationDetail::where('donation_id', $id)->first();
            if ($mutationDetail) {
                return response()->json(['success' => false, 'message' => 'Oops. Something wrong: This transaction has been used in mutation'], 422);
            }
            $data->detail()->delete();
            $data->delete();

            return response()->json(['success' => true, 'message' => 'Success delete data']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Oops. Something wrong: ' . $e->getMessage()], 500);
        }
    }

    public function chart(Request $request)
    {
        $category = $request->get('category', 'all');
        $range = $request->get('range', 'last_30_days');

        $query = DonationDetail::query();
        switch (auth()->user()->level) {
            case "leader":
                $volunteer = Volunteer::select('id')->where('group_id', auth()->user()->profile->group_id)->get();
                $donation_id = Donation::select('id')->distinct()->whereIn('volunteer_id', $volunteer)->get();
                $query->whereIn('donation_id', $donation_id);
                break;
            case "administrator":
                break;
            default:
                $donation_id = Donation::select('id')->distinct()->where('volunteer_id', auth()->user()->volunteer_id)->get();
                $query->whereIn('donation_id', $donation_id);
                break;
        }
        if ($category != 'all') {
            $campaign = Campaign::select('id')->where('category_id',$category)->get();
            $query->whereIn('program_id', $campaign);
        }

        // Filter data berdasarkan range
        switch ($range) {
            case 'last_year':
                $query->selectRaw('MONTH(created_at) as period, COUNT(distinct(donation_id)) as total_coupon, SUM(amount) as total_amount')
                ->where('created_at', '>=', Carbon::now()->subYear())
                    ->groupBy('period');
                break;

            case 'last_3_month':
                $query->selectRaw('MONTH(created_at) as period, COUNT(distinct(donation_id)) as total_coupon, SUM(amount) as total_amount')
                ->where('created_at', '>=', Carbon::now()->subMonths(3))
                    ->groupBy('period');
                break;

            case 'last_30_days':
                $query->selectRaw('DAY(created_at) as period, COUNT(distinct(donation_id)) as total_coupon, SUM(amount) as total_amount')
                ->where('created_at', '>=', Carbon::now()->subMonth())
                    ->groupBy('period');
                break;

            case 'last_7_days':
                $query->selectRaw('DAY(created_at) as period, COUNT(distinct(donation_id)) as total_coupon, SUM(amount) as total_amount')
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                    ->groupBy('period');
                break;

            case 'today':
                $query->selectRaw('HOUR(created_at) as period, COUNT(distinct(donation_id)) as total_coupon, SUM(amount) as total_amount')
                ->whereDate('created_at', Carbon::today())
                    ->groupBy('period');
                break;
        }
        $data = $query->orderBy('period')->get();

        return response()->json($data);

    }
}
