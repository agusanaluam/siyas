<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction\Mutation;
use App\Models\Transaction\MutationDetail;
use App\Models\Transaction\Donation;
use App\Models\Master\Campaign;

use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;


class MutationController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $data = Mutation::with('detail');
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
                ->addColumn('statusLabel', function ($data) {
                switch ($data->status) {
                    case "Requested":
                        $badge = 'warning';
                        break;
                    case "Approved":
                        $badge = 'success';
                        break;
                    case "Canceled":
                        $badge = 'danger';
                        break;
                }

                return '<span class="badge badge-md bg-' . $badge . '">' . $data->status . '</span>';
                })
                ->addColumn('action', function ($data) {
                        $button = '<a class="action-set" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="true">
							<i class="fa fa-ellipsis-v" aria-hidden="true"></i>
						</a>
						<ul class="dropdown-menu" id="' . $data->id . '">
							<li>
								<a href="javascript:void(0);" class="dropdown-item mutation-detail" data-bs-toggle="modal" data-bs-target="#mutation-details"><i class="fa fa-eye info-img"></i>Mutation Detail</a>
							</li>';
                        if ((auth()->user()->level == 'administrator') && (!isset($data->approve_date))) {
                                $button .= '<li>
                                    <a href="javascript:void(0);" class="dropdown-item approve-mutation"><i class="fa fa-check info-img"></i>Approve Mutation</a>
                                </li>';
                        }
                        if ((auth()->user()->level == 'administrator') || (!isset($data->approve_date))) {
                            $button .= '<li>
                                <a href="javascript:void(0);" class="dropdown-item confirm-text mb-0 cancel-mutation"><i class="info-img fa fa-trash-can"></i>Cancel Mutation</a>
                            </li>';
                        }
						$button .= '</ul>';
                        return $button;
                })
                ->rawColumns(['statusLabel','action'])
                ->make();
        }
        return view('pages.mutation.list');
    }

    public function detail($id) {

    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'trans_date' => 'required|string',
            'total_amount' => 'required',
            'donation_id.*' => 'required',

        ]);

        DB::beginTransaction();

        try {
            $invoice = $request->invoice_number;
            if ($request->invoice_number == "") {
                $invoice = 'TRM-'.round(microtime(true) * 1000);
            }

            $mutation = Mutation::create([
                'invoice_number' => $invoice,
                'trans_date' => date('Y-m-d',strtotime($request->trans_date)),
                'total_amount' => $request->total_amount,
                'total_liq' => count($request->donation_id),
                'description' => $request->description,
                'status' => 'Requested',
                'created_by' => auth()->user()->id,
            ]);

            foreach ($request->donation_id as $donation) {
                $donationData = Donation::where('id', $donation)->first();
                MutationDetail::create([
                    'mutation_id' => $mutation->id,
                    'donation_id' => $donation,
                    'liq_number' => $donationData->liq_number,
                    'amount' => $donationData->total_amount,
                ]);
            }


            DB::Commit();

            return response()->json(['success' => true, 'message' => 'Success insert mutation, please check your mutation list']);
        } catch (\Exception $e) {

            DB::Rollback();
            return response()->json(['success' => false, 'message' => 'Oops. Something wrong: ' . $e->getMessage()], 500);
        }
    }

    public function approve($id)
    {
        try {
            $data = Mutation::with('detail')->findOrFail($id);
            if (isset($data->approve_date)) {
                return response()->json(['success' => false, 'message' => 'Oops. Something wrong: This transaction has been completed'], 422);
            }
            $data->status = "Approved";

            $data->approve_date = date("Y-m-d H:i:s", strtotime(now()));
            $data->approve_by = auth()->user()->id;
            $data->updated_at = date("Y-m-d H:i:s", strtotime(now()));
            $data->save();

            foreach ($data->detail as $detail) {
                $donation = Donation::with('detail')->find($detail->donation_id);
                foreach($donation->detail as $donationDetail) {
                    $campaign = Campaign::find($donationDetail->program_id);
                    $campaign->total_amount += $donationDetail->amount;
                    $campaign->save();
                }
                $donation->status = "Completed";
                $donation->save();
            }

            return response()->json(['success' => true, 'message' => 'Success update data']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Oops. Something wrong: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $data = Mutation::with('detail')->findOrFail($id);
            if (isset($data->approve_date)) {
                return response()->json(['success' => false, 'message' => 'This transaction has been approved, cannot revert.'], 422);
            }
            foreach ($data->detail as $detail) {
                $donation = Donation::with('detail')->find($detail->donation_id);
                if ($donation->status == 'Completed') {
                    foreach ($donation->detail as $donationDetail) {
                        $campaign = Campaign::find($donationDetail->program_id);
                        $campaign->total_amount -= $donationDetail->amount;
                        $campaign->save();
                    }
                }
                $donation->status = "Approved";
                $donation->save();

            }
            $data->detail()->delete();
            $data->delete();

            return response()->json(['success' => true, 'message' => 'Success delete data']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Oops. Something wrong: ' . $e->getMessage()], 500);
        }
    }
}
