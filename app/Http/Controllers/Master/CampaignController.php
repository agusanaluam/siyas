<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Master\Campaign;
use App\Models\Master\CampaignCategory;
use App\Models\Master\CampaignImage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CampaignController extends Controller
{
    /**
     * Check if image processing is available
     */
    private function isImageProcessingAvailable()
    {
        return extension_loaded('gd') || extension_loaded('imagick');
    }
    function index()
    {
        if (request()->ajax()) {
            $status = request()->input('status');
            $data = Campaign::with(['category', 'image']);
            if ($status > 0) {
                $data = $data->where('status', $status);
            }
            return DataTables::of($data->get())
                ->addIndexColumn()
                ->addColumn('campaign', function ($data){
                    $imagePath = $data->image->isNotEmpty() ? $data->image[0]->picture_path : 'default.jpg';
                    $storage = "storage/campaign_pictures/".$imagePath;
                    return '<div class="productimgname">
                                <a href="'.route('campaign.details',$data->id).'" class="product-img stock-img">
                                    <img src="'. asset($storage). '" alt="product">
                                </a>
                                <a href="' . route('campaign.details', $data->id) . '">'.$data->name.' </a>
                            </div>';
                })
                ->editColumn('start_date', function ($data) {
                    $formatDate = date('d-M-Y', strtotime($data->start_date));
                    return $formatDate;
                })
                ->editColumn('end_date', function ($data) {
                    $formatDate = date('d-M-Y', strtotime($data->end_date));
                    return $formatDate;
                })
                ->editColumn('target_amount', function ($data) {
                    $formatCurrency = "Rp. ". number_format($data->target_amount,0,',','.');
                    return $formatCurrency;
                })
                ->editColumn('close_type', function ($data) {
                    switch ($data->close_type) {
                        case 1:
                            $formatType = "Tanggal Berakhir";
                            break;
                        case 2:
                            $formatType = "Target Nominal";
                            break;
                        default:
                            $formatType = " ";
                            break;
                    }
                    return $formatType;
                })
                ->addColumn('statusLabel', function ($data) {
                    switch ($data->status) {
                        case 1:
                        $formatType = "Pending";
                        $badge = 'warning';
                        break;
                    case 2:
                        $formatType = "Running";
                        $badge = 'success';
                        break;
                    case 3:
                        $formatType = "Closed";
                        $badge = 'danger';
                        break;
                    }

                    return '<span class="badge bg-outline-'.$badge.'">'.$formatType.'</span>';
                })
                ->addColumn('progress', function ($data) {
                    return '<div class="progress progress-xl mb-3 progress-animate custom-progress-4 info" role="progressbar" aria-valuenow="'.round(floatval($data->total_amount/$data->target_amount)*100,2) .'" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-info" style="width:'.round(floatval($data->total_amount/$data->target_amount)*100,2) .'%"></div>
                                    <div class="progress-bar-label">'.round(floatval($data->total_amount/$data->target_amount)*100,2) .'%</div>
                            </div>';
                })
                ->addColumn('action', function ($data) {
                    if (auth()->user()->level == 'administrator') {
                        return
                            '<div class="edit-delete-action">
                                <a class="edit me-2 p-2" href="'.route('campaign.edit',$data->id).'">
                                    <i data-feather="edit" class="feather-edit"></i>
                                </a>
                                <a class="confirm-text p-2 delete" href="javascript:void(0);" data-id="' . $data->id . '">
                                    <i data-feather="trash-2" class="feather-trash-2"></i>
                                </a>
                            </div>
                            ';
                    } else {
                        return '<div class="edit-delete-action"></div>';
                    };
                })
                ->rawColumns(['campaign', 'progress','statusLabel', 'action'])
                ->make();
        }
        $status = 0;
        return view('pages.campaign.list', compact('status'));
    }

    public function search(Request $request)
    {
        $query = $request->query('query');
        $data = Campaign::with('image')->where('name', 'LIKE', "%{$query}%")->get();

        // Kembalikan data sebagai JSON
        return response()->json($data);
    }

    function create()
    {
        $categories = CampaignCategory::all();
        return view('pages.campaign.add-campaign', compact('categories'));
    }

    function store(Request $request)
    {
        // Validasi input dengan custom messages
        $request->validate([
            'name' => 'required|string|max:150',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'description' => 'required',
            'pic' => 'required|string|max:150',
            'campaign_picture.*' => 'required|image|mimes:jpeg,png,jpg,gif', // Validasi untuk foto (ukuran akan di-handle di logic)
        ], [
            'name.required' => 'Nama campaign wajib diisi',
            'name.max' => 'Nama campaign maksimal 150 karakter',
            'start_date.required' => 'Tanggal mulai wajib diisi',
            'start_date.date' => 'Format tanggal mulai tidak valid',
            'end_date.required' => 'Tanggal selesai wajib diisi',
            'end_date.date' => 'Format tanggal selesai tidak valid',
            'description.required' => 'Deskripsi wajib diisi',
            'pic.required' => 'PIC wajib diisi',
            'pic.max' => 'Nama PIC maksimal 150 karakter',
            'campaign_picture.*.required' => 'Gambar campaign wajib diupload',
            'campaign_picture.*.image' => 'File harus berupa gambar',
            'campaign_picture.*.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
        ]);

        DB::beginTransaction();

        try {

            $campaign = Campaign::create([
                'name' => $request->name,
                'category_id' => $request->category,
                'start_date' => date("Y-m-d", strtotime($request->start_date)),
                'end_date' => date("Y-m-d", strtotime($request->end_date)),
                'target_amount' => $request->target_amount,
                'target_object' => $request->target_object,
                'description' => $request->description,
                'pic' => $request->pic,
                'close_type' => $request->close_type,
                'status'=> (strtotime($request->start_date) > strtotime(now()))? 1 : ((strtotime($request->end_date) < strtotime(now())) ? 3 : 2),
            ]);


            if ($request->hasFile('campaign_picture')) {
                foreach ($request->file('campaign_picture') as $image) {
                    try {
                        $extension = $image->getClientOriginalExtension();
                        $filenameSimpan = Str::random(16) . '_' . time() . '.' . $extension;
                        
                        if ($this->isImageProcessingAvailable()) {
                            $manager = new ImageManager(new Driver());
                            
                            // Proses gambar: compress jika melebihi 2MB
                            $processedImage = $this->processImage($image, $manager, $extension);
                            
                            // Simpan gambar asli (yang sudah di-compress jika perlu)
                            Storage::disk('public')->put('campaign_pictures/' . $filenameSimpan, $processedImage);
                            
                            // Buat thumbnail untuk preview
                            try {
                                $imgForThumb = $manager->read($image->getPathname());
                                $resizedImage = $imgForThumb->scaleDown(height: 115)->encodeByExtension($extension);
                                Storage::disk('public')->put('campaign_pictures/resized_' . $filenameSimpan, (string) $resizedImage);
                            } catch (\Exception $e) {
                                // Jika gagal buat thumbnail, gunakan gambar asli
                                Storage::disk('public')->put('campaign_pictures/resized_' . $filenameSimpan, $processedImage);
                            }
                        } else {
                            // Jika extension tidak tersedia, simpan file asli
                            $image->storeAs('public/campaign_pictures/', $filenameSimpan);
                            // Copy untuk thumbnail (tanpa resize)
                            Storage::disk('public')->copy('campaign_pictures/' . $filenameSimpan, 'campaign_pictures/resized_' . $filenameSimpan);
                        }

                        CampaignImage::create([
                            'program_id' => $campaign->id,
                            'picture_path' => $filenameSimpan,
                        ]);
                    } catch (\Exception $e) {
                        throw new \Exception('Gagal memproses gambar: ' . $e->getMessage());
                    }
                }
            }
            DB::Commit();

            return redirect()->route('campaign.list')->withSuccess('Campaign berhasil ditambahkan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan campaign: ' . $e->getMessage()])
                ->withInput();
        }
    }

    function details($id)
    {
        $data = Campaign::with('image','category','donationDetail')->find($id);
        return view('pages.campaign.campaign-details', compact('data'));
    }

    function edit($id)
    {
        $data = Campaign::with('image')->find($id);
        $categories = CampaignCategory::all();
        return view('pages.campaign.edit-campaign', compact('data', 'categories'));
    }

    function update(Request $request)
    {
        // Validasi input dengan custom messages
        $request->validate([
            'name' => 'required|string|max:150',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'description' => 'required',
            'pic' => 'required|string|max:150',
            'campaign_picture.*' => 'nullable|image|mimes:jpeg,png,jpg,gif', // Validasi untuk foto (ukuran akan di-handle di logic)
        ], [
            'name.required' => 'Nama campaign wajib diisi',
            'name.max' => 'Nama campaign maksimal 150 karakter',
            'start_date.required' => 'Tanggal mulai wajib diisi',
            'start_date.date' => 'Format tanggal mulai tidak valid',
            'end_date.required' => 'Tanggal selesai wajib diisi',
            'end_date.date' => 'Format tanggal selesai tidak valid',
            'description.required' => 'Deskripsi wajib diisi',
            'pic.required' => 'PIC wajib diisi',
            'pic.max' => 'Nama PIC maksimal 150 karakter',
            'campaign_picture.*.image' => 'File harus berupa gambar',
            'campaign_picture.*.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif',
        ]);

        DB::beginTransaction();

        try {

            $campaign = Campaign::findOrFail($request->id);
            $campaign->update([
                'name' => $request->name,
                'category_id' => $request->category,
                'start_date' => date("Y-m-d", strtotime($request->start_date)),
                'end_date' => date("Y-m-d", strtotime($request->end_date)),
                'target_amount' => $request->target_amount,
                'description' => $request->description,
                'pic' => $request->pic,
                'close_type' => $request->close_type,
                'status' => (strtotime($request->start_date) > strtotime(now())) ? 1 : ((strtotime($request->end_date) < strtotime(now())) ? 3 : 2),
            ]);


            //remove deleted image
            if ($request->has('existing_images')) {
                // $result = array_values($array);  // Converts to ["str"]

                $jsonResult = array_values($request->existing_images);
                $deletedImages = CampaignImage::where('program_id', $request->id)->whereNotIn('id', $jsonResult)->get();
                foreach ($deletedImages as $image) {
                    if (Storage::exists("public/campaign_pictures/" . $image->picture_path)) {
                        Storage::disk('public')->delete('campaign_pictures/'.$image->picture_path);
                        Storage::disk('public')->delete('campaign_pictures/resized_' . $image->picture_path);
                    }
                    $image->delete();
                }
            }


            if ($request->hasFile('campaign_picture')) {
                foreach ($request->file('campaign_picture') as $image) {
                    try {
                        $extension = $image->getClientOriginalExtension();
                        $filenameSimpan = Str::random(16) . '_' . time() .'.'. $extension;
                        
                        if ($this->isImageProcessingAvailable()) {
                            $manager = new ImageManager(new Driver());
                            
                            // Proses gambar: compress jika melebihi 2MB
                            $processedImage = $this->processImage($image, $manager, $extension);
                            
                            // Simpan gambar asli (yang sudah di-compress jika perlu)
                            Storage::disk('public')->put('campaign_pictures/' . $filenameSimpan, $processedImage);
                            
                            // Buat thumbnail untuk preview
                            try {
                                $imgForThumb = $manager->read($image->getPathname());
                                $resizedImage = $imgForThumb->scaleDown(height:115)->encodeByExtension($extension);
                                Storage::disk('public')->put('campaign_pictures/resized_' . $filenameSimpan, (string) $resizedImage);
                            } catch (\Exception $e) {
                                // Jika gagal buat thumbnail, gunakan gambar asli
                                Storage::disk('public')->put('campaign_pictures/resized_' . $filenameSimpan, $processedImage);
                            }
                        } else {
                            // Jika extension tidak tersedia, simpan file asli
                            $image->storeAs('public/campaign_pictures/', $filenameSimpan);
                            // Copy untuk thumbnail (tanpa resize)
                            Storage::disk('public')->copy('campaign_pictures/' . $filenameSimpan, 'campaign_pictures/resized_' . $filenameSimpan);
                        }
                        
                        CampaignImage::create([
                            'program_id' => $campaign->id,
                            'picture_path' => $filenameSimpan,
                        ]);
                    } catch (\Exception $e) {
                        throw new \Exception('Gagal memproses gambar: ' . $e->getMessage());
                    }
                }
            }
            DB::commit();

            return redirect()->route('campaign.list')->withSuccess('Campaign berhasil diupdate');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat mengupdate campaign: ' . $e->getMessage()])
                ->withInput();
        }
    }

    function destroy($id)
    {
        try {
            $data = Campaign::with('image')->findOrFail($id);
            foreach ($data->image as $image) {
                $images = CampaignImage::find($image->id);
                if (Storage::exists("public/campaign_pictures/" . $image->picture_path)) {
                    Storage::disk('public')->delete('campaign_pictures/'.$image->picture_path);
                    // Storage::disk('public')->delete('campaign_pictures/resized_'.$image->picture_path);
                }
                $images->delete();
            }
            $data->delete();

            return response()->json(['success' => true, 'message' => 'Success delete data']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Oops. Something wrong: ' . $e->getMessage()], 500);
        }
    }

    function getPendingCampaign()
    {
        $status = 1;
        return view('pages.campaign.list', compact('status'));

    }

    function getRunningCampaign()
    {
        $status = 2;
        return view('pages.campaign.list', compact('status'));

    }

    function getClosedCampaign()
    {
        $status = 3;
        return view('pages.campaign.list', compact('status'));
    }

    /**
     * Process image: compress dan resize jika melebihi ukuran maksimal
     */
    private function processImage($image, $manager, $extension)
    {
        $maxSize = 2048 * 1024; // 2MB dalam bytes
        $fileSize = $image->getSize();
        
        // Jika ukuran file sudah di bawah 2MB, langsung return
        if ($fileSize <= $maxSize) {
            return file_get_contents($image->getPathname());
        }
        
        // Baca gambar
        $img = $manager->read($image->getPathname());
        
        // Hitung rasio kompresi yang diperlukan
        $compressionRatio = $maxSize / $fileSize;
        $quality = max(60, min(90, (int)($compressionRatio * 100))); // Quality antara 60-90
        
        // Resize jika terlalu besar (max width 1920px untuk menjaga kualitas)
        $width = $img->width();
        if ($width > 1920) {
            $img->scaleDown(width: 1920);
        }
        
        // Encode dengan quality untuk JPEG, atau PNG dengan optimasi
        if (in_array(strtolower($extension), ['jpg', 'jpeg'])) {
            $encoded = $img->toJpeg($quality);
        } else {
            $encoded = $img->encodeByExtension($extension);
        }
        
        // Jika masih melebihi, kurangi quality lebih lanjut
        $encodedString = (string) $encoded;
        $attempts = 0;
        while (strlen($encodedString) > $maxSize && $attempts < 5 && $quality > 40) {
            $quality -= 10;
            if (in_array(strtolower($extension), ['jpg', 'jpeg'])) {
                $encoded = $img->toJpeg($quality);
            } else {
                // Untuk PNG, coba resize lagi
                $img->scale(width: (int)($img->width() * 0.9));
                $encoded = $img->encodeByExtension($extension);
            }
            $encodedString = (string) $encoded;
            $attempts++;
        }
        
        return $encodedString;
    }
}
