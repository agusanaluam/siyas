<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Group;
use App\Models\Master\Volunteer;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;




class VolunteerController extends Controller
{
    function index()
    {
        if (request()->ajax()) {

            $status = request()->input('status');
            $data = Volunteer::with('group','user')->whereRelation('user','level','volunteer');
            if ($status != "all") {
                $data = $data->where('status', $status);
            }
            if (auth()->user()->level == "leader") {
                $data = $data->where('group_id', auth()->user()->profile->group_id);
            }
            return DataTables::of($data->get())
                ->addIndexColumn()
                ->addColumn('profile', function ($data) {
                    $storage = "storage/" . $data->profile_picture;
                    return '<div class="productimgname">
                                <a href="javascript:void(0);" class="product-img stock-img">
                                    <img src="' . asset($storage) . '"
                                        alt="product">
                                </a>
                                <a href="javascript:void(0);">' . $data->name . ' </a>
                            </div>';
                })
                ->editColumn('birth_date', function ($data) {
                    $formatDate = date('d-M-Y', strtotime($data->birth_date));
                    return $formatDate;
                })
                ->addColumn('verified_at', function ($data) {
                    if ($data->user->email_verified_at == null) {
                        return '<span class="badge bg-warning">Not Verified</span>';
                    }
                    $formatDate = date('d-M-Y H:i', strtotime($data->user->email_verified_at));
                    return $formatDate;
                })
                ->addColumn('statusLabel', function ($data) {
                    switch ($data->status) {
                        case 1:
                            $formatType = "Active";
                            $badge = 'success';
                            break;
                        case 2:
                            $formatType = "Inactive";
                            $badge = 'danger';
                            break;
                    }

                    return '<span class="badge bg-outline-' . $badge . '">' . $formatType . '</span>';
                })
                ->addColumn('action', function ($data) {
                    if (auth()->user()->level == 'administrator') {
                        return
                            '<div class="edit-delete-action">
                                <a class="edit me-2 p-2" href="#">
                                    <i data-feather="edit" class="feather-edit"></i>
                                </a>
                                <a class="confirm-text p-2 delete" href="javascript:void(0);" data-id="' . $data->id . '">
                                    <i data-feather="trash-2" class="feather-trash-2"></i>
                                </a>
                            </div>';
                    } else {
                        return '<div class="edit-delete-action"></div>';
                    }
                })
                ->rawColumns(['profile', 'verified_at','statusLabel', 'action'])
                ->make();
        }
        $status = "all";

        return view('pages.volunteer.list', compact('status'));
    }

    function getInactiveVolunteer()
    {
        $status = "0";
        return view('pages.volunteer.list', compact('status'));
    }

    public function search(Request $request)
    {
        $query = $request->query('query');
        $data = Volunteer::where('name', 'LIKE', "%{$query}%")->get();

        // Kembalikan data sebagai JSON
        return response()->json($data);
    }

    function create()
    {
        $group = Group::all();
        return view('pages.campaign.add-volunteer', compact('group'));
    }

    function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|min:5',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'confirmpassword' => 'required|min:6',
                'group' => 'required'
            ],
            [
                'name.required' => 'Userame is required',
                'email.required' => 'Email is required',
                'password.required' => 'Password is required',
                'confirmpassword.required' => 'Confirm Password is required or mismatch with password',
                'group.required' => 'Group is required'
            ]
        );

        DB::beginTransaction();

        try {

            // Simpan data ke tabel user_profiles
            $userProfile = Volunteer::create([
                'group_id' => $request->group,
                'name' => $request->name,
                'email' => $request->email,
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'volunteer_id' => $userProfile->id,
                'email_verified_at' => date('Y-m-d H:i:s', strtotime(now())),
                'level' => 'volunteer',
            ]);

            // Commit transaksi jika semuanya berhasil
            DB::commit();

            return redirect()->route('volunteer.list')->withSuccess('Success insert data');
        } catch (\Exception $e) {

            DB::Rollback();

            return redirect()->back()->withErrors(['error' => 'Oops. Something wrong: ' . $e->getMessage()])
                ->onlyInput('name', 'pic', 'description', 'start_date', 'end_date', 'category', 'close_type', 'target_amount');
        }
    }

    function edit($id)
    {
        $data = Volunteer::find($id);
        $group = Group::all();
        return view('pages.campaign.edit-volunteer', compact('data', 'group'));
    }

    function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'id' => 'required',
            'nik' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk foto
            'sex' => 'in:L,P'
        ]);

        try {
            $user = User::where('id', $request->id)->first();
            $profile = Volunteer::where('id', $user->volunteer_id)->first();
            if (!$profile) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat memperbarui profil: No profile data'], 404);
            }

            // Update data profile
            $profile->nik = $request->nik;
            $profile->name = $request->name;
            $user->name = $request->name;
            $profile->sex = $request->sex;
            $profile->birth_date = date("Y-m-d", strtotime($request->birth_date));
            $profile->phone_number = $request->phone_number;
            $user->phone_number = $request->phone_number;
            $profile->address = $request->address;
            $profile->address_code = $request->address_code;


            // Cek apakah ada file gambar
            if ($request->hasFile('profile_picture')) {
                // Simpan gambar dan hapus yang lama jika ada
                if ($profile->profile_picture) {
                    Storage::delete('public/' . $profile->profile_picture); // Hapus foto lama
                }
                // Upload gambar baru
                $extension = $request->file('profile_picture')->getClientOriginalExtension();
                $filenameSimpan = Str::random(16) . '_' . time() . '.' . $extension;
                $request->file('profile_picture')->storeAs('public/profile_pictures/' . $filenameSimpan);
                $profile->profile_picture = 'profile_pictures/' . $filenameSimpan; // Simpan foto baru
            }

            // Simpan perubahan
            $profile->save();
            $user->save(); // Simpan user jika ada perubahan
            DB::Commit();

            return redirect()->route('volunteer.list')->withSuccess('Success insert data');
        } catch (\Exception $e) {

            DB::Rollback();

            return redirect()->back()->withErrors(['error' => 'Oops. Something wrong: ' . $e->getMessage()])
                ->onlyInput('name', 'pic', 'description', 'start_date', 'end_date', 'category', 'close_type', 'target_amount');
        }
    }

    function destroy($id)
    {
        try {
            $data = Volunteer::findOrFail($id);
            if (isset($data->profile_picute)) {
                if (Storage::exists("public/" . $data->profile_picture)) {
                    Storage::disk('public')->delete($data->profile_picture);
                }
            }
            $data->delete();

            return response()->json(['success' => true, 'message' => 'Success delete data']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Oops. Something wrong: ' . $e->getMessage()], 500);
        }
    }
}
