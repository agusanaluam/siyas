<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Models\RamadanMurajaah;

class RamadanMurajaahController extends Controller
{
    public function surahList()
    {
        $surahList = Cache::remember('equran_surah_list', 86400, function () {
            $response = Http::get('https://equran.id/api/surat');

            if (!$response->successful()) {
                return null;
            }

            return collect($response->json())->map(function ($surah) {
                return [
                    'nomor' => $surah['nomor'],
                    'nama_latin' => $surah['nama_latin'],
                    'jumlah_ayat' => $surah['jumlah_ayat'],
                ];
            })->toArray();
        });

        if ($surahList === null) {
            return response()->json(['message' => 'Gagal mengambil data surah'], 502);
        }

        return response()->json($surahList);
    }

    public function surahDetail($nomor)
    {
        $cacheKey = "equran_surah_{$nomor}";

        $surahData = Cache::remember($cacheKey, 86400, function () use ($nomor) {
            $response = Http::get("https://equran.id/api/surat/{$nomor}");

            if (!$response->successful()) {
                return null;
            }

            $data = $response->json();

            return [
                'nomor' => $data['nomor'],
                'nama_latin' => $data['nama_latin'],
                'jumlah_ayat' => $data['jumlah_ayat'],
                'ayat' => collect($data['ayat'] ?? [])->map(function ($ayah) {
                    return [
                        'nomor' => $ayah['nomor'],
                        'ar' => $ayah['ar'],
                        'tr' => $ayah['tr'],
                        'idn' => $ayah['idn'],
                    ];
                })->toArray(),
            ];
        });

        if ($surahData === null) {
            return response()->json(['message' => 'Gagal mengambil data surah'], 502);
        }

        return response()->json($surahData);
    }

    public function index()
    {
        $user = Auth::user();

        $murajaah = RamadanMurajaah::where('user_id', $user->id)
            ->orderBy('surah_number')
            ->orderBy('ayah_number')
            ->get();

        return response()->json($murajaah);
    }

    public function store(Request $request)
    {
        $request->validate([
            'surah_number' => 'required|integer|min:1|max:114',
            'surah_name' => 'required|string',
            'ayah_number' => 'required|integer|min:1',
        ]);

        $user = Auth::user();

        // Check if already saved
        $existing = RamadanMurajaah::where('user_id', $user->id)
            ->where('surah_number', $request->surah_number)
            ->where('ayah_number', $request->ayah_number)
            ->first();

        if ($existing) {
            return response()->json(['message' => 'Ayat ini sudah tersimpan'], 409);
        }

        // Fetch ayah data from equran.id
        $cacheKey = "equran_surah_{$request->surah_number}";
        $surahData = Cache::remember($cacheKey, 86400, function () use ($request) {
            $response = Http::get("https://equran.id/api/surat/{$request->surah_number}");
            return $response->successful() ? $response->json() : null;
        });

        if (!$surahData) {
            return response()->json(['message' => 'Gagal mengambil data ayat'], 502);
        }

        $ayahData = collect($surahData['ayat'] ?? [])->firstWhere('nomor', $request->ayah_number);

        if (!$ayahData) {
            return response()->json(['message' => 'Ayat tidak ditemukan'], 404);
        }

        $murajaah = RamadanMurajaah::create([
            'user_id' => $user->id,
            'surah_number' => $request->surah_number,
            'surah_name' => $request->surah_name,
            'ayah_number' => $request->ayah_number,
            'ayah_ar' => $ayahData['ar'],
            'ayah_tr' => $ayahData['tr'],
            'ayah_idn' => $ayahData['idn'],
        ]);

        return response()->json($murajaah, 201);
    }

    public function destroy($id)
    {
        $user = Auth::user();

        $murajaah = RamadanMurajaah::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$murajaah) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $murajaah->delete();

        return response()->json(['message' => 'Berhasil dihapus']);
    }
}
