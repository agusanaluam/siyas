<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Master\Volunteer;
use App\Models\Location\Provinsi;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function profile()
    {
        $user = User::with(['profile'])->findOrFail(Auth::id());
        
        $profile = Volunteer::leftJoin('dt_desakel as dd', "m_volunteer.address_code", '=', 'dd.code')
            ->leftJoin('dt_kecamatan as dk', DB::raw('SUBSTR(m_volunteer.address_code,1,8)'), '=', 'dk.code')
            ->leftJoin('dt_kotakab as dkk', DB::raw('SUBSTR(m_volunteer.address_code,1,5)'), '=', 'dkk.code')
            ->leftJoin('dt_provinsi as dp', DB::raw('SUBSTR(m_volunteer.address_code,1,2)'), '=', 'dp.code')
            ->select('m_volunteer.*', 'dd.name as desa', 'dk.name as kecamatan', 'dkk.name as kota', 'dp.name as provinsi')
            ->where('m_volunteer.id', $user->volunteer_id)
            ->first();

        $provinsi = Provinsi::all();

        return response()->json([
            'user' => $user,
            'profile' => $profile,
            'provinsi' => $provinsi,
        ]);
    }

    public function updateProfile(Request $request)
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
            $user = User::findOrFail(Auth::id());
            $profile = Volunteer::findOrFail($user->volunteer_id);

            $profile->nik = $request->nik;
            $profile->name = $request->name;
            $user->name = $request->name;
            $profile->sex = $request->sex;
            $profile->birth_date = $request->birth_date;
            $profile->phone_number = $request->phone_number;
            $user->phone_number = $request->phone_number;
            $profile->address = $request->address;
            $profile->address_code = $request->address_code;

            if ($request->hasFile('profile_picture')) {
                if ($profile->profile_picture) {
                    Storage::delete('public/' . $profile->profile_picture);
                }
                
                $extension = $request->file('profile_picture')->getClientOriginalExtension();
                $filenameSimpan = Str::random(16) . '_' . time() . '.' . $extension;
                $request->file('profile_picture')->storeAs('public/profile_pictures', $filenameSimpan);
                $profile->profile_picture = 'profile_pictures/' . $filenameSimpan;
            }

            $profile->save();
            $user->save();

            return response()->json([
                'message' => 'Profil berhasil diupdate',
                'data' => $user->load('profile'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengupdate profil',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

