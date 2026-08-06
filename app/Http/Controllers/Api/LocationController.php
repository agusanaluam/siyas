<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location\Provinsi;
use App\Models\Location\Kota;
use App\Models\Location\Kecamatan;
use App\Models\Location\Desa;

class LocationController extends Controller
{
    public function getProvinsi()
    {
        $provinsi = Provinsi::orderBy('name', 'asc')->get(['code', 'name']);
        return response()->json($provinsi);
    }

    public function getKotaByProvinsi($provinsiId)
    {
        $kota = Kota::where('code', 'like', $provinsiId . '%')
            ->orderBy('name', 'asc')
            ->get(['code', 'name']);
        return response()->json($kota);
    }

    public function getKecamatanByKota($kotaId)
    {
        $kecamatan = Kecamatan::where('code', 'like', $kotaId . '%')
            ->orderBy('name', 'asc')
            ->get(['code', 'name']);
        return response()->json($kecamatan);
    }

    public function getDesabyKecamatan($kecamatanId)
    {
        $desa = Desa::where('code', 'like', $kecamatanId . '%')
            ->orderBy('name', 'asc')
            ->get(['code', 'name']);
        return response()->json($desa);
    }
}

