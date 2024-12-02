<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Group;
use Yajra\DataTables\DataTables;


class GroupController extends Controller
{
    function index()
    {
        if (request()->ajax()) {
            $data = Group::with('volunteer.user')->whereRelation('volunteer.user', 'level', 'volunteer')->orderBy('created_at', 'asc');
            return DataTables::of($data->get())
                ->addIndexColumn()
                ->addColumn('leader', function ($data) {
                    if (count($data->volunteer) > 0) {
                        foreach ($data->volunteer as $leader) {
                            return ($leader->user->level == "leader")? $leader->name: '-';
                        }
                    } else {
                        return '-';
                    }
                })
                ->editColumn('created_at', function ($data) {
                    $formatDate = date('d-M-Y H:i', strtotime($data->created_at));
                    return $formatDate;
                })
                ->addColumn('total_volunteer', function ($data) {
                    if (isset($data->volunteer)) {
                        return count($data->volunteer);
                    }
                    return 0;
                })
                ->addColumn('action', function ($data) {
                    return
                        '
                        <div class="edit-delete-action">
                            <a class="edit me-2 p-2" href="javascript:void(0);" data-id="' . $data->id . '" data-bs-toggle="modal"
                                data-bs-target="#edit-group">
                                <i data-feather="edit" class="feather-edit"></i>
                            </a>
                            <a class="confirm-text p-2 delete" href="javascript:void(0);" data-id="' . $data->id . '">
                                <i data-feather="trash-2" class="feather-trash-2"></i>
                            </a>
                        </div>
                        ';
                })
                ->rawColumns(['total_volunteer','action'])
                ->make();
        }
        return view('pages.volunteer.group-list');
    }

    function data()
    {
        $data = Group::select('id','name')->get();
        return response()->json(['success' => true, 'data' => $data]);
    }

    function store(Request $request)
    {

        // Validasi input
        $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'required|string',
        ]);

        try {

            Group::create([
                'name'     => $request->name,
                'description'     => $request->description,
            ]);

            return response()->json(['success' => true, 'message' => 'Success insert data']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Oops. Something wrong: ' . $e->getMessage()], 500);
        }
    }

    function edit($id)
    {
        $data = Group::find($id);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'id' => 'required',
            'name' => 'required|string|max:150',
            'description' => 'required|string',
        ]);

        try {

            $data = Group::find($request->id);
            $data->name =  $request->name;
            $data->description = $request->description;
            $data->save();

            return response()->json(['success' => true, 'message' => 'Success update data']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Oops. Something wrong: ' . $e->getMessage()], 500);
        }
    }

    function destroy($id)
    {
        try {

            $data = Group::find($id);
            $data->delete();

            return response()->json(['success' => true, 'message' => 'Success delete data']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Oops. Something wrong: ' . $e->getMessage()], 500);
        }
    }
}
