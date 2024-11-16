<?php

namespace App\Http\Controllers\Master;


use App\Http\Controllers\Controller;
use App\Models\Location\Desa;
use App\Models\Location\Kecamatan;
use App\Models\Location\Kota;


class LocationController extends Controller
{
    public function getKotaByProvinsi($provinsiId)
    {
        $kota = Kota::where('code', 'like', $provinsiId.'%')->get();
        return response()->json($kota);
    }

    public function getKecamatanByKota($kotaId)
    {
        $kecamatan = Kecamatan::where('code', 'like', $kotaId . '%')->get();
        return response()->json($kecamatan);
    }

    public function getDesaByKecamatan($kecamatanId)
    {
        $desa = Desa::where('code', 'like', $kecamatanId . '%')->get();
        return response()->json($desa);
    }
}
