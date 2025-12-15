<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\ImageUploadService;

class SettingController extends Controller
{

    protected $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function about()
    {
        $setting = Setting::getSettings();

        return response()->json([
            'about_content' => $setting->about_content,
            'about_photo' => $setting->about_photo,
            'about_service' => $setting->about_service,
        ]);
    }

    public function profile()
    {
        $setting = Setting::getSettings();
        return response()->json($setting);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'address' => 'required|string',
            'phone_number' => 'required|string|max:15',
            'email' => 'nullable|email|max:150',
            'description' => 'required|string',
            'gmaps' => 'nullable|string',
            'facebook' => 'nullable|string|url',
            'instagram' => 'nullable|string|url',
            'twitter' => 'nullable|string|url',
            'youtube' => 'nullable|string|url',
            'tiktok' => 'nullable|string|url',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $setting = Setting::first();

            if (!$setting) {
                $setting = new Setting();
            }

            $setting->name = $request->name;
            $setting->address = $request->address;
            $setting->phone_number = $request->phone_number;
            $setting->email = $request->email;
            $setting->description = $request->description;
            $setting->gmaps = $request->gmaps;
            $setting->facebook = $request->facebook;
            $setting->instagram = $request->instagram;
            $setting->twitter = $request->twitter;
            $setting->youtube = $request->youtube;
            $setting->tiktok = $request->tiktok;

            if ($request->hasFile('photo')) {
                // Delete old image
                if ($setting->photo) {
                    $this->imageService->deleteImage($setting->photo);
                }
                
                $url = $this->imageService->uploadImage($request->file('photo'), 'settings');
                $setting->photo = $url;
            }

            $setting->save();

            return response()->json([
                'message' => 'Profile yayasan berhasil diupdate',
                'data' => $setting,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengupdate profile yayasan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateAbout(Request $request)
    {
        $request->validate([
            'about_content' => 'required|string',
            'about_service' => 'nullable|array',
            'about_service.*' => 'nullable|string|max:255',
            'about_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $setting = Setting::first();

            if (!$setting) {
                $setting = new Setting();
            }

            $setting->about_content = $request->about_content;
            $setting->about_service = $request->about_service ? array_values(array_filter($request->about_service)) : null;

            if ($request->hasFile('about_photo')) {
                // Delete old image
                if ($setting->about_photo) {
                    $this->imageService->deleteImage($setting->about_photo);
                }

                $url = $this->imageService->uploadImage($request->file('about_photo'), 'settings');
                $setting->about_photo = $url;
            }

            $setting->save();

            return response()->json([
                'message' => 'Konten about berhasil disimpan',
                'data' => [
                    'about_content' => $setting->about_content,
                    'about_photo' => $setting->about_photo,
                    'about_service' => $setting->about_service,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menyimpan konten about',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

