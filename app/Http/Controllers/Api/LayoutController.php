<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HeroSlide;
use App\Models\Partner;
use Illuminate\Support\Facades\Storage;

class LayoutController extends Controller
{
    // Hero Slides
    public function getHeroSlides()
    {
        return response()->json(HeroSlide::orderBy('order')->get());
    }

    public function storeHeroSlide(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $path = $request->file('image')->store('public/hero-slides');
        $url = Storage::url($path);

        $slide = HeroSlide::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $url,
            'order' => HeroSlide::max('order') + 1,
        ]);

        return response()->json($slide);
    }

    public function updateHeroSlide(Request $request, $id)
    {
        $slide = HeroSlide::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|max:10240',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            // Storage::url returns /storage/..., we need public/...
            $oldPath = str_replace('/storage/', 'public/', $slide->image);
            if (Storage::exists($oldPath)) {
                Storage::delete($oldPath);
            }

            $path = $request->file('image')->store('public/hero-slides');
            $slide->image = Storage::url($path);
        }

        $slide->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json($slide);
    }

    public function deleteHeroSlide($id)
    {
        $slide = HeroSlide::findOrFail($id);
        
        $oldPath = str_replace('/storage/', 'public/', $slide->image);
        if (Storage::exists($oldPath)) {
            Storage::delete($oldPath);
        }

        $slide->delete();
        return response()->json(['message' => 'Slide deleted']);
    }

    // Partners
    public function getPartners()
    {
        return response()->json(Partner::orderBy('order')->get());
    }

    public function storePartner(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240',
            'name' => 'required|string',
        ]);

        $path = $request->file('image')->store('public/partners');
        $url = Storage::url($path);

        $partner = Partner::create([
            'name' => $request->name,
            'image' => $url,
            'order' => Partner::max('order') + 1,
        ]);

        return response()->json($partner);
    }

    public function deletePartner($id)
    {
        $partner = Partner::findOrFail($id);
        
        $oldPath = str_replace('/storage/', 'public/', $partner->image);
        if (Storage::exists($oldPath)) {
            Storage::delete($oldPath);
        }

        $partner->delete();
        return response()->json(['message' => 'Partner deleted']);
    }
}
