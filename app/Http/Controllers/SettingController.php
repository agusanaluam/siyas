<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function profile()
    {
        $setting = Setting::getSettings();
        return view('pages.settings.profile', compact('setting'));
    }

    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'address' => 'required|string',
            'phone_number' => 'required|string|max:15',
            'description' => 'required|string',
            'gmaps' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $setting = Setting::first();

            if (!$setting) {
                // Jika belum ada data, buat baru
                $setting = new Setting();
            }

            $setting->name = $request->name;
            $setting->address = $request->address;
            $setting->phone_number = $request->phone_number;
            $setting->description = $request->description;
            $setting->gmaps = $request->gmaps;

            // Cek apakah ada file gambar
            if ($request->hasFile('photo')) {
                // Hapus foto lama jika ada
                if ($setting->photo && Storage::exists('public/' . $setting->photo)) {
                    Storage::delete('public/' . $setting->photo);
                }
                
                // Upload gambar baru
                $extension = $request->file('photo')->getClientOriginalExtension();
                $filenameSimpan = Str::random(16) . '_' . time() . '.' . $extension;
                $request->file('photo')->storeAs('public/settings/', $filenameSimpan);
                $setting->photo = 'settings/' . $filenameSimpan;
            }

            $setting->save();

            return response()->json(['success' => true, 'message' => 'Berhasil mengupdate profile yayasan']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}

