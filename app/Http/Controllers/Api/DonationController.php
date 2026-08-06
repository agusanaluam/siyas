<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Master\Campaign;
use App\Models\Transaction\Donation;
use App\Models\Transaction\DonationDetail;
use App\Models\Master\Volunteer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $query = Donation::with('detail.campaign.image');

        if (isset($status)) {
            $query->where('via_transfer', $status);
        }

        switch (auth()->user()->level) {
            case "administrator":
                break;
            case "leader":
                $volunteers = Volunteer::select('id')
                    ->where('group_id', auth()->user()->profile->group_id)
                    ->get();
                $query->whereIn('volunteer_id', $volunteers->pluck('id'));
                break;
            default:
                $query->where('volunteer_id', auth()->user()->volunteer_id);
                break;
        }

        $donations = $query->orderBy('created_at', 'desc')->get();

        return response()->json($donations);
    }

    public function show($id)
    {
        $donation = Donation::with('detail.campaign.image', 'volunteer')
            ->findOrFail($id);
        return response()->json($donation);
    }

    /**
     * Get donation by liq_number (public endpoint for success page)
     */
    public function showByLiqNumber($liqNumber)
    {
        $donation = Donation::with('detail.campaign.image')
            ->where('liq_number', $liqNumber)
            ->first();

        if (!$donation) {
            return response()->json([
                'message' => 'Donation not found'
            ], 404);
        }

        return response()->json($donation);
    }

    public function store(Request $request)
    {
        $request->validate([
            'liq_number' => 'required|string',
            'donatur_name' => 'required|string|max:150',
            'donatur_phone' => 'required|string',
            'donatur_address' => 'nullable|string',
            'payment_method' => 'required|in:transfer,cash',
            'campaign_id.*' => 'required|exists:m_program,id',
            'amount.*' => 'required|numeric|min:1',
            'total_amount' => 'required|numeric|min:1',
            'trans_date' => 'required|date',
            'reference_code' => 'nullable|string',
            'reference_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $path = "";
            if ($request->hasFile('reference_picture')) {
                $extension = $request->file('reference_picture')->getClientOriginalExtension();
                $filenameSimpan = Str::random(16) . '_' . time() . '.' . $extension;
                $request->file('reference_picture')->storeAs('public/payment_reference', $filenameSimpan);
                $path = 'payment_reference/' . $filenameSimpan;
            }

            $donation = Donation::create([
                'liq_number' => $request->liq_number,
                'donatur_name' => $request->donatur_name,
                'donatur_phone' => $request->donatur_phone,
                'donatur_address' => $request->donatur_address,
                'total_amount' => $request->total_amount,
                'trans_date' => $request->trans_date,
                'via_transfer' => $request->payment_method === 'transfer',
                'reference_code' => $request->reference_code,
                'reference_picture' => $path,
                'description' => $request->description,
                'status' => $request->payment_method === 'transfer' ? 'at Rekening' : 'at Volunteer',
                'volunteer_id' => auth()->user()->volunteer_id,
                'user_id' => auth()->id(),
            ]);

            foreach ($request->campaign_id as $key => $campaignId) {
                DonationDetail::create([
                    'donation_id' => $donation->id,
                    'program_id' => $campaignId,
                    'amount' => $request->amount[$key],
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Donation berhasil dibuat',
                'data' => $donation->load('detail.campaign'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Donation Store Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $isProduction = config('app.env') === 'production';
            return response()->json([
                'message' => 'Gagal membuat donation',
                'error' => $isProduction ? null : $e->getMessage(),
            ], 500);
        }
    }

    public function storePublic(Request $request)
    {
        $request->validate([
            'donatur_name' => 'required|string|max:150',
            'donatur_phone' => 'required|string',
            'donatur_email' => 'nullable|email',
            'campaign_id' => 'required|exists:m_program,id',
            'amount' => 'required|numeric|min:1000',
            'description' => 'nullable|string|max:140',
            'is_anonymous' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            // Check for authenticated user via sanctum
            $user = auth('sanctum')->user();

            $donation = Donation::create([
                'liq_number' => 'WEB' . date('ymdHis'), // 3 + 12 = 15 chars
                'donatur_name' => $request->is_anonymous ? 'Hamba Allah' : $request->donatur_name,
                'donatur_phone' => $request->donatur_phone,
                // donatur_address is nullable in DB, passing description as prayer/message if needed or strictly description
                'description' => $request->description,
                'total_amount' => $request->amount,
                'trans_date' => now(),
                'via_transfer' => true,
                'status' => 'Pending',
                'payment_status' => 'pending', // Status awal pembayaran adalah pending
                'volunteer_id' => null,
                'user_id' => $user ? $user->id : null,
            ]);

            DonationDetail::create([
                'donation_id' => $donation->id,
                'program_id' => $request->campaign_id,
                'amount' => $request->amount,
            ]);

            DB::commit();

            // Reload donation dengan relations
            $donation->refresh();

            return response()->json([
                'message' => 'Donation created successfully',
                'data' => $donation
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating donation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $isProduction = config('app.env') === 'production';
            return response()->json([
                'message' => 'Failed to create donation',
                'error' => $isProduction ? null : $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'liq_number' => 'required|string',
            'donatur_name' => 'required|string|max:150',
            'donatur_phone' => 'required|string',
            'donatur_address' => 'nullable|string',
            'payment_method' => 'required|in:transfer,cash',
            'campaign_id.*' => 'required|exists:m_program,id',
            'amount.*' => 'required|numeric|min:1',
            'total_amount' => 'required|numeric|min:1',
            'trans_date' => 'required|date',
            'reference_code' => 'nullable|string',
            'reference_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'detail_id.*' => 'nullable|exists:t_donation_detail,id',
        ]);

        DB::beginTransaction();

        try {
            $donation = Donation::findOrFail($id);

            $path = $donation->reference_picture;
            $publicPath = 'public/';
            if ($request->hasFile('reference_picture')) {
                if ($donation->reference_picture && Storage::exists($publicPath . $donation->reference_picture)) {
                    Storage::delete($publicPath . $donation->reference_picture);
                }
                $extension = $request->file('reference_picture')->getClientOriginalExtension();
                $filenameSimpan = Str::random(16) . '_' . time() . '.' . $extension;
                $request->file('reference_picture')->storeAs($publicPath . 'payment_reference', $filenameSimpan);
                $path = 'payment_reference/' . $filenameSimpan;
            }

            $donation->update([
                'liq_number' => $request->liq_number,
                'donatur_name' => $request->donatur_name,
                'donatur_phone' => $request->donatur_phone,
                'donatur_address' => $request->donatur_address,
                'total_amount' => $request->total_amount,
                'trans_date' => $request->trans_date,
                'via_transfer' => $request->payment_method === 'transfer',
                'reference_code' => $request->reference_code,
                'reference_picture' => $path,
                'description' => $request->description,
                'updated_by' => auth()->id(),
            ]);

            if ($request->has('detail_id')) {
                $donation->detail()->whereNotIn('id', array_filter($request->detail_id))->delete();
                foreach ($request->detail_id as $key => $detailId) {
                    if ($detailId) {
                        $donation->detail()->updateOrCreate(
                            ['id' => $detailId, 'program_id' => $request->campaign_id[$key]],
                            ['amount' => $request->amount[$key]]
                        );
                    } else {
                        $donation->detail()->create([
                            'program_id' => $request->campaign_id[$key],
                            'amount' => $request->amount[$key],
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Donation berhasil diupdate',
                'data' => $donation->load('detail.campaign'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Donation Update Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $isProduction = config('app.env') === 'production';
            return response()->json([
                'message' => 'Gagal mengupdate donation',
                'error' => $isProduction ? null : $e->getMessage(),
            ], 500);
        }
    }

    public function approve($id)
    {
        try {
            $donation = Donation::findOrFail($id);
            $donation->status = 'Completed';
            $donation->save();

            return response()->json([
                'message' => 'Donation berhasil disetujui',
                'data' => $donation,
            ]);
        } catch (\Exception $e) {
            Log::error('Donation Approve Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $isProduction = config('app.env') === 'production';
            return response()->json([
                'message' => 'Gagal menyetujui donation',
                'error' => $isProduction ? null : $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $donation = Donation::findOrFail($id);

            if ($donation->reference_picture && Storage::exists('public/' . $donation->reference_picture)) {
                Storage::delete('public/' . $donation->reference_picture);
            }

            $donation->detail()->delete();
            $donation->delete();

            return response()->json([
                'message' => 'Donation berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            Log::error('Donation Delete Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $isProduction = config('app.env') === 'production';
            return response()->json([
                'message' => 'Gagal menghapus donation',
                'error' => $isProduction ? null : $e->getMessage(),
            ], 500);
        }
    }

    public function getHistory()
    {
        $user = auth()->user();

        $donations = Donation::with('detail.campaign.image')
            ->where(function ($query) use ($user) {
                // Show personal donations (logged in user made them)
                $query->where('user_id', $user->id);

                // If volunteer, also show donations they managed/collected
                if ($user->volunteer_id) {
                    $query->orWhere('volunteer_id', $user->volunteer_id);
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($donations);
    }

    public function getTransfer()
    {
        // Hanya administrator yang bisa melihat semua transfer
        $user = auth()->user();
        if (!in_array($user->level, ['administrator', 'root'])) {
            return response()->json([
                'message' => 'Unauthorized. Hanya administrator yang dapat mengakses endpoint ini.'
            ], 403);
        }

        $donations = Donation::with('detail.campaign.image')
            ->where('via_transfer', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($donations);
    }
}

