<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\CampaignCategory;

class CampaignCategoryController extends Controller
{
    public function index()
    {
        $categories = CampaignCategory::orderBy('name', 'asc')->get();
        return $this->corsResponse(response()->json($categories));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150|unique:m_program_category,name',
            'pic' => 'required|string|max:150',
        ]);

        $category = CampaignCategory::create([
            'name' => $request->name,
            'pic' => $request->pic,
        ]);

        return response()->json([
            'message' => 'Kategori berhasil dibuat',
            'data' => $category,
        ], 201);
    }

    public function show($id)
    {
        $category = CampaignCategory::findOrFail($id);
        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:150|unique:m_program_category,name,' . $id,
            'pic' => 'required|string|max:150',
        ]);

        $category = CampaignCategory::findOrFail($id);
        $category->update([
            'name' => $request->name,
            'pic' => $request->pic,
        ]);

        return response()->json([
            'message' => 'Kategori berhasil diupdate',
            'data' => $category,
        ]);
    }

    public function destroy($id)
    {
        $category = CampaignCategory::findOrFail($id);
        $category->delete();

        return response()->json([
            'message' => 'Kategori berhasil dihapus',
        ]);
    }
}

