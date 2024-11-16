<?php

namespace App\Http\Controllers;

use App\Models\Location\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Master\Volunteer;
use App\Models\Location\Provinsi;
use Illuminate\Support\Str;


class UsersController extends Controller
{
    public function profile()
    {
        $provinsi = Provinsi::all();
        $user = Volunteer::leftJoin('users', "users.volunteer_id", '=', 'm_volunteer.id')
            ->leftJoin('dt_desakel as dd', "m_volunteer.address_code", '=', 'dd.code')
            ->leftJoin('dt_kecamatan as dk', DB::raw('SUBSTR(m_volunteer.address_code,1,8)'), '=', 'dk.code')
            ->leftJoin('dt_kotakab as dkk', DB::raw('SUBSTR(m_volunteer.address_code,1,5)'), '=', 'dkk.code')
            ->leftJoin('dt_provinsi as dp', DB::raw('SUBSTR(m_volunteer.address_code,1,2)'), '=', 'dp.code')
            ->select('m_volunteer.*', 'users.id as user_id', 'users.level', 'dd.name as desa', 'dk.name as kecamatan', 'dkk.name as kota', 'dp.name as provinsi')
            ->where('users.id', Auth::user()->id)->first();
        return view('pages.users.profile', compact('user','provinsi'));
    }

    public function profileUpdate(Request $request)
    {
        // Validasi input
        $request->validate([
            'nik' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi untuk foto
            'sex' => 'in:L,P'
        ]);

        try {
            $user = User::where('id', Auth::user()->id)->first();
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
                    Storage::delete('public/'.$profile->profile_picture); // Hapus foto lama
                }
                // Upload gambar baru
                $extension = $request->file('profile_picture')->getClientOriginalExtension();
                $filenameSimpan = Str::random(16).'_'.time().'.'.$extension;
                $request->file('profile_picture')->storeAs('public/profile_pictures/'.$filenameSimpan);
                $profile->profile_picture = 'profile_pictures/'.$filenameSimpan; // Simpan foto baru
            }

            // Simpan perubahan
            $profile->save();
            $user->save(); // Simpan user jika ada perubahan

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat memperbarui profil: ' . $e->getMessage()], 500);
        }
    }
}
