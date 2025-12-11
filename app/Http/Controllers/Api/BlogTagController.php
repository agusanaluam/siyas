<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\BlogTag;

class BlogTagController extends Controller
{
    public function index()
    {
        $tags = BlogTag::orderBy('created_at', 'desc')->get();
        return response()->json($tags);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('blog_tags', 'name')->whereNull('deleted_at'),
            ],
            'status' => 'boolean',
        ]);

        $tag = BlogTag::create([
            'name' => $request->name,
            'status' => $request->status ?? true,
        ]);

        return response()->json([
            'message' => 'Tag created successfully',
            'data' => $tag,
        ], 201);
    }

    public function show($id)
    {
        $tag = BlogTag::findOrFail($id);
        return response()->json($tag);
    }

    public function update(Request $request, $id)
    {
        $tag = BlogTag::findOrFail($id);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('blog_tags', 'name')->ignore($id)->whereNull('deleted_at'),
            ],
            'status' => 'boolean',
        ]);

        $tag->update([
            'name' => $request->name,
            'status' => $request->status ?? $tag->status,
        ]);

        return response()->json([
            'message' => 'Tag updated successfully',
            'data' => $tag,
        ]);
    }

    public function destroy($id)
    {
        $tag = BlogTag::findOrFail($id);
        $tag->delete();

        return response()->json([
            'message' => 'Tag deleted successfully',
        ]);
    }
}
