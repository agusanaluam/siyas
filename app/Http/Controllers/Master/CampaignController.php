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
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Decoders\FilePathImageDecoder;



class CampaignController extends Controller
{
    function index()
    {
        if (request()->ajax()) {
            $status = request()->input('status');
            $data = Campaign::with('category');
            if ($status > 0) {
                $data = $data->where('status', $status);
            }
            return DataTables::of($data->get())
                ->addIndexColumn()
                ->addColumn('campaign', function ($data){
                    $storage = "storage/campaign_pictures/".$data->image[0]->picture_path;
                    return '<div class="productimgname">
                                <a href="javascript:void(0);" class="product-img stock-img">
                                    <img src="'. asset($storage).'"
                                        alt="product">
                                </a>
                                <a href="javascript:void(0);">'.$data->name.' </a>
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
                ->rawColumns(['campaign','statusLabel', 'action'])
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
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:150',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'description' => 'required',
            'pic' => 'required|string|max:150',
            'campaign_picture.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk foto

        ]);

        DB::beginTransaction();

        try {

            $campaign = Campaign::create([
                'name' => $request->name,
                'category_id' => $request->category,
                'start_date' => date("Y-m-d", strtotime($request->start_date)),
                'end_date' => date("Y-m-d", strtotime($request->end_date)),
                'target_amount' => $request->target_amount,
                'description' => $request->description,
                'pic' => $request->pic,
                'close_type' => $request->close_type,
                'status'=> (strtotime($request->start_date) > strtotime(now()))? 1 : ((strtotime($request->end_date) < strtotime(now())) ? 3 : 2),
            ]);


            if ($request->hasFile('campaign_picture')) {
                $manager = new ImageManager(new Driver());

                foreach ($request->file('campaign_picture') as $image) {
                    $extension = $image->getClientOriginalExtension();
                    $filenameSimpan = Str::random(16) . '_' . time() . '.' . $extension;
                    $image->storeAs('public/campaign_pictures/', $filenameSimpan);
                    $resizedImage = $manager->read($image->getPathname())->scaleDown(height: 115)->encodeByExtension($extension);
                    Storage::disk('public')->put('campaign_pictures/resized_' . $filenameSimpan, (string) $resizedImage);

                    CampaignImage::create([
                        'program_id' => $campaign->id,
                        'picture_path' => $filenameSimpan,
                    ]);
                }
            }
            DB::Commit();

            return redirect()->route('campaign.list')->withSuccess('Success insert data');
        } catch (\Exception $e) {

            DB::Rollback();

            return redirect()->back()->withErrors(['error' => 'Oops. Something wrong: ' .$e->getMessage()])
                ->onlyInput('name','pic','description','start_date','end_date','category','close_type', 'target_amount');
        }
    }

    function edit($id)
    {
        $data = Campaign::with('image')->find($id);
        $categories = CampaignCategory::all();
        return view('pages.campaign.edit-campaign', compact('data', 'categories'));
    }

    function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:150',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'description' => 'required',
            'pic' => 'required|string|max:150',
            'campaign_picture.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk foto

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
                $manager = new ImageManager(Driver::class);
                foreach ($request->file('campaign_picture') as $image) {
                    $extension = $image->getClientOriginalExtension();
                    $filenameSimpan = Str::random(16) . '_' . time() .'.'. $extension;
                    $image->storeAs('public/campaign_pictures/', $filenameSimpan);
                    $resizedImage = $manager->read($image->getPathname())->scaleDown(height:115)->encodeByExtension($extension);

                    Storage::disk('public')->put('campaign_pictures/resized_' . $filenameSimpan, (string) $resizedImage);
                    CampaignImage::create([
                        'program_id' => $campaign->id,
                        'picture_path' => $filenameSimpan,
                    ]);
                }
            }
            DB::Commit();

            return redirect()->route('campaign.list')->withSuccess('Success insert data');
        } catch (\Exception $e) {

            DB::Rollback();

            return redirect()->back()->withErrors(['error' => 'Oops. Something wrong: ' . $e->getMessage()])
                ->onlyInput('name', 'pic', 'description', 'start_date', 'end_date', 'category', 'close_type', 'target_amount');
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
}
