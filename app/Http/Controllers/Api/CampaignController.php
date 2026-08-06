<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Master\Campaign;
use App\Models\Master\CampaignCategory;
use App\Models\Master\CampaignImage;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction\DonationDetail;
use App\Services\ImageUploadService;

class CampaignController extends Controller
{

    protected $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index(Request $request)
    {
        $status = $request->input('status', 0);
        $categoryId = $request->input('category_id');
        $perPage = $request->input('per_page', 12);

        $query = Campaign::with(['category', 'image']);

        // Eager load accumulation for index listing if needed, but for now user focused on detail.
        // We can add withCount/withSum if we want efficient list loading later.

        if ($status > 0) {
            $query->where('status', $status);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $campaigns = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return $this->corsResponse(response()->json($campaigns));
    }

    public function show($id)
    {
        $campaign = Campaign::with(['category', 'image'])->findOrFail($id);

        // Hanya hitung donasi yang sudah sukses (paid atau Completed)
        $totalCollected = DonationDetail::where('program_id', $id)
            ->whereHas('donation', function($query) {
                $query->where(function($q) {
                    $q->where('payment_status', 'paid')
                      ->orWhere('status', 'Completed');
                });
            })
            ->sum('amount');

        // Hanya hitung jumlah donatur yang sudah sukses
        $totalDonors = DonationDetail::where('program_id', $id)
            ->whereHas('donation', function($query) {
                $query->where(function($q) {
                    $q->where('payment_status', 'paid')
                      ->orWhere('status', 'Completed');
                });
            })
            ->count();

        // Hanya tampilkan donatur yang sudah sukses
        $donors = DonationDetail::where('program_id', $id)
            ->join('t_donation', 't_donation_detail.donation_id', '=', 't_donation.id')
            ->whereNull('t_donation.deleted_at')
            ->where(function($query) {
                $query->where('t_donation.payment_status', 'paid')
                      ->orWhere('t_donation.status', 'Completed');
            })
            ->orderBy('t_donation.created_at', 'desc')
            ->take(15)
            ->get(['t_donation.donatur_name', 't_donation_detail.amount', 't_donation.created_at']);

        $campaign->setAttribute('total_collected', $totalCollected);
        $campaign->setAttribute('total_donors', $totalDonors);
        $campaign->setAttribute('donors', $donors);

        return $this->corsResponse(response()->json($campaign));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:m_program_category,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'required|string',
            'pic' => 'required|string|max:150',
            'target_amount' => 'required|numeric|min:0',
            'target_object' => 'nullable|numeric|min:0',
            'close_type' => 'required|in:1,2',
            'campaign_picture.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        DB::beginTransaction();

        try {
            $campaign = Campaign::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'description' => $request->description,
                'pic' => $request->pic,
                'target_amount' => $request->target_amount,
                'target_object' => $request->target_object ?? 0,
                'close_type' => $request->close_type,
                'status' => Campaign::determineStatus($request->start_date, $request->end_date),
            ]);

            if ($request->hasFile('campaign_picture')) {
                foreach ($request->file('campaign_picture') as $file) {
                    $url = $this->imageService->uploadImage($file, 'campaign_pictures');

                    CampaignImage::create([
                        'program_id' => $campaign->id,
                        'picture_path' => $url,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Campaign berhasil dibuat',
                'data' => $campaign->load(['category', 'image']),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Campaign Store Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $isProduction = config('app.env') === 'production';
            return response()->json([
                'message' => 'Gagal membuat campaign',
                'error' => $isProduction ? null : $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        // Support both PUT and POST with _method
        if ($request->has('_method') && $request->input('_method') === 'PUT') {
            // Handle as PUT request
        }

        $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'required|exists:m_program_category,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'required|string',
            'pic' => 'required|string|max:150',
            'target_amount' => 'required|numeric|min:0',
            'target_object' => 'nullable|numeric|min:0',
            'close_type' => 'required|in:1,2',
            'campaign_picture.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        DB::beginTransaction();

        try {
            $campaign = Campaign::findOrFail($id);

            $campaign->update([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'description' => $request->description,
                'pic' => $request->pic,
                'target_amount' => $request->target_amount,
                'target_object' => $request->target_object ?? 0,
                'close_type' => $request->close_type,
                'status' => Campaign::determineStatus($request->start_date, $request->end_date),
            ]);

            if ($request->hasFile('campaign_picture')) {
                foreach ($request->file('campaign_picture') as $file) {
                    $url = $this->imageService->uploadImage($file, 'campaign_pictures');

                    CampaignImage::create([
                        'program_id' => $campaign->id,
                        'picture_path' => $url,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Campaign berhasil diupdate',
                'data' => $campaign->load(['category', 'image']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Campaign Update Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $isProduction = config('app.env') === 'production';
            return response()->json([
                'message' => 'Gagal mengupdate campaign',
                'error' => $isProduction ? null : $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $campaign = Campaign::with('image')->findOrFail($id);

            foreach ($campaign->image as $image) {
                // Try deleting from R2 (service)
                $this->imageService->deleteImage($image->picture_path);

                $image->delete();
            }

            $campaign->delete();

            return response()->json([
                'message' => 'Campaign berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            Log::error('Campaign Delete Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $isProduction = config('app.env') === 'production';
            return response()->json([
                'message' => 'Gagal menghapus campaign',
                'error' => $isProduction ? null : $e->getMessage(),
            ], 500);
        }
    }

    public function getPending()
    {
        $campaigns = Campaign::with(['category', 'image'])
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($campaigns);
    }

    public function getRunning()
    {
        $campaigns = Campaign::with(['category', 'image'])
            ->where('status', 2)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($campaigns);
    }

    public function getClosed()
    {
        $campaigns = Campaign::with(['category', 'image'])
            ->where('status', 3)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($campaigns);
    }
}


