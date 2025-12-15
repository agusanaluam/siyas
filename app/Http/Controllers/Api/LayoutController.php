<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HeroSlide;
use App\Models\Partner;
use Illuminate\Support\Facades\Storage;

class LayoutController extends Controller
{
    protected $imageService;

    public function __construct(\App\Services\ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

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

        $url = $this->imageService->uploadImage($request->file('image'), 'hero-slides');

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
            // Delete old image
            if ($slide->image) {
                $this->imageService->deleteImage($slide->image);
            }

            $url = $this->imageService->uploadImage($request->file('image'), 'hero-slides');
            $slide->image = $url;
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
        
        if ($slide->image) {
            $this->imageService->deleteImage($slide->image);
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

        $url = $this->imageService->uploadImage($request->file('image'), 'partners');

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
        
        if ($partner->image) {
            $this->imageService->deleteImage($partner->image);
        }

        $partner->delete();
        return response()->json(['message' => 'Partner deleted']);
    }
}
