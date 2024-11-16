<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use App\Models\Master\CampaignCategory;
use Illuminate\Support\Facades\Log;


class CampaignCategoryController extends Controller
{
    function index()
    {
        if (request()->ajax()) {
            $data = CampaignCategory::orderBy('created_at', 'asc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($data) {
                    $formatDate = date('d-M-Y H:i', strtotime($data->created_at));
                    return $formatDate;
                })
                ->addColumn('statusLabel', function ($data) {
                    $checked = "";
                    if ($data->status) {
                        $checked = "checked";
                    }
                    return '
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="' . $data->id . '" name="status" ' . $checked . '>
                        </div>
                            ';
                })
                ->addColumn('action', function ($data) {
                    if (auth()->user()->level == 'administrator') {
                        return
                            '<div class="edit-delete-action">
                                <a class="edit me-2 p-2" href="javascript:void(0);" data-id="' . $data->id . '" data-bs-toggle="modal"
                                    data-bs-target="#edit-campaign-category">
                                    <i data-feather="edit" class="feather-edit"></i>
                                </a>
                                <a class="confirm-text p-2 delete" href="javascript:void(0);" data-id="' . $data->id . '">
                                    <i data-feather="trash-2" class="feather-trash-2"></i>
                                </a>
                            </div>
                            ';
                    } else {
                        return '';
                    };
                })
                ->rawColumns(['statusLabel', 'action'])
                ->make();
        }
        return view('pages.campaign.category-list');
    }

    function create()
    {

    }

    function store(Request $request)
    {

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'required|string',
            'pic' => 'required|string|max:150',
        ]);

        try {

            CampaignCategory::create([
                'name'     => $request->name,
                'description'     => $request->description,
                'pic'   => $request->pic,
                'status' => ($request->status == 'on')? true : false,
            ]);

            return response()->json(['success' => true, 'message' => 'Success insert data']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Oops. Something wrong: ' . $e->getMessage()], 500);
        }
    }

    function edit($id)
    {
        $data = CampaignCategory::find($id);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    function update(Request $request)
    {
        Log::info('Data Update', $request->all());

        // Validasi input
        $request->validate([
            'id' => 'required',
            'name' => 'required|string|max:150',
            'description' => 'required|string',
            'pic' => 'required|string|max:150',
        ]);

        try {

            $data = CampaignCategory::find($request->id);
            $data->name =  $request->name;
            $data->description = $request->description;
            $data->pic = $request->pic;
            $data->status = ($request->status == 'on') ? true : false;
            $data->save();

            return response()->json(['success' => true, 'message' => 'Success update data']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Oops. Something wrong: ' . $e->getMessage()], 500);
        }
    }

    function destroy($id)
    {
        try {

            $data = CampaignCategory::find($id);
            $data->delete();

            return response()->json(['success' => true, 'message' => 'Success delete data']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Oops. Something wrong: ' . $e->getMessage()], 500);
        }
    }


}
