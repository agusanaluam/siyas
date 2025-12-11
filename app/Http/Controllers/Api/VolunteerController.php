<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\Master\Volunteer;
use App\Models\Master\Group;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VolunteerController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $query = Volunteer::with('group', 'user')
            ->whereRelation('user', 'level', 'volunteer');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if (auth()->user()->level === 'leader') {
            $query->where('group_id', auth()->user()->profile->group_id);
        }

        $volunteers = $query->orderBy('created_at', 'desc')->get();

        return response()->json($volunteers);
    }

    public function show($id)
    {
        $volunteer = Volunteer::with('group', 'user')->findOrFail($id);
        return response()->json($volunteer);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:5',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'group_id' => 'required|exists:m_group,id',
        ]);

        DB::beginTransaction();

        try {
            $volunteer = Volunteer::create([
                'group_id' => $request->group_id,
                'name' => $request->name,
                'email' => $request->email,
                'profile_picture' => 'profile_pictures/user-01.jpg',
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'volunteer_id' => $volunteer->id,
                'email_verified_at' => now(),
                'level' => 'volunteer',
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Volunteer berhasil dibuat',
                'data' => $volunteer->load('user'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal membuat volunteer',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sex' => 'required|in:L,P',
            'birth_date' => 'required|date',
            'address' => 'required|string',
            'address_code' => 'required|string',
        ]);

        try {
            $volunteer = Volunteer::findOrFail($id);
            $user = User::where('volunteer_id', $id)->first();

            if (!$user) {
                return response()->json([
                    'message' => 'User tidak ditemukan',
                ], 404);
            }

            $volunteer->nik = $request->nik;
            $volunteer->name = $request->name;
            $user->name = $request->name;
            $volunteer->sex = $request->sex;
            $volunteer->birth_date = $request->birth_date;
            $volunteer->phone_number = $request->phone_number;
            $user->phone_number = $request->phone_number;
            $volunteer->address = $request->address;
            $volunteer->address_code = $request->address_code;

            if ($request->hasFile('profile_picture')) {
                if ($volunteer->profile_picture && Storage::exists('public/' . $volunteer->profile_picture)) {
                    Storage::delete('public/' . $volunteer->profile_picture);
                }
                
                $extension = $request->file('profile_picture')->getClientOriginalExtension();
                $filenameSimpan = Str::random(16) . '_' . time() . '.' . $extension;
                $request->file('profile_picture')->storeAs('public/profile_pictures', $filenameSimpan);
                $volunteer->profile_picture = 'profile_pictures/' . $filenameSimpan;
            }

            $volunteer->save();
            $user->save();

            return response()->json([
                'message' => 'Volunteer berhasil diupdate',
                'data' => $volunteer->load('user'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengupdate volunteer',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $volunteer = Volunteer::findOrFail($id);
            $user = User::where('volunteer_id', $id)->first();

            if ($user) {
                if ($volunteer->profile_picture && Storage::exists('public/' . $volunteer->profile_picture)) {
                    Storage::delete('public/' . $volunteer->profile_picture);
                }
                $user->delete();
            }

            $volunteer->delete();

            return response()->json([
                'message' => 'Volunteer berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus volunteer',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getInactive()
    {
        $volunteers = Volunteer::with('group', 'user')
            ->whereRelation('user', 'level', 'volunteer')
            ->where('status', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($volunteers);
    }
}

