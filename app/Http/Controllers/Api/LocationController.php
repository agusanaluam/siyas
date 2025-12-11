<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location\Provinsi;
use App\Models\Location\Kotakab;
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
        $kota = Kotakab::where('provinsi_code', $provinsiId)
            ->orderBy('name', 'asc')
            ->get(['code', 'name']);
        return response()->json($kota);
    }

    public function getKecamatanByKota($kotaId)
    {
        $kecamatan = Kecamatan::where('kotakab_code', $kotaId)
            ->orderBy('name', 'asc')
            ->get(['code', 'name']);
        return response()->json($kecamatan);
    }

    public function getDesabyKecamatan($kecamatanId)
    {
        $desa = Desa::where('kecamatan_code', $kecamatanId)
            ->orderBy('name', 'asc')
            ->get(['code', 'name']);
        return response()->json($desa);
    }
}

