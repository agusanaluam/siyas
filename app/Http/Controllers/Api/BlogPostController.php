<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Master\BlogPost;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\ImageUploadService;

class BlogPostController extends Controller
{

    protected $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index()
    {
        $user = auth('sanctum')->user();
        
        $query = BlogPost::with(['creator', 'category'])
            ->orderBy('created_at', 'desc');

        // Jika user login tapi bukan administrator/root, filter by created_by
        if ($user && !in_array($user->level, ['administrator', 'root'])) {
            $query->where('created_by', $user->id);
        }
        
        $blogs = $query->get();
        
        return response()->json($blogs);
    }

    public function show($id)
    {
        $blog = BlogPost::with(['creator', 'category'])->findOrFail($id);
        return response()->json($blog);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'nullable|integer|exists:blog_categories,id',
            'status' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {
            $slug = Str::slug($request->title);
            $slugCount = BlogPost::where('slug', $slug)->count();
            if ($slugCount > 0) {
                $slug = $slug . '-' . ($slugCount + 1);
            }

            $blogData = [
                'title' => $request->title,
                'slug' => $slug,
                'content' => $request->content,
                'excerpt' => $request->excerpt,
                'category_id' => $request->category_id,
                'status' => $request->status ?? false,
                'published_at' => $request->published_at ?? ($request->status ? now() : null),
                'created_by' => auth()->id(),
            ];

            if ($request->hasFile('featured_image')) {
                $url = $this->imageService->uploadImage($request->file('featured_image'), 'blog_images');
                $blogData['featured_image'] = $url;
            }

            $blog = BlogPost::create($blogData);

            DB::commit();

            return response()->json([
                'message' => 'Blog berhasil dibuat',
                'data' => $blog->load(['creator', 'category']),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal membuat blog',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'nullable|integer|exists:blog_categories,id',
            'status' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {
            $blog = BlogPost::findOrFail($id);

            $slug = Str::slug($request->title);
            if ($slug !== $blog->slug) {
                $slugCount = BlogPost::where('slug', $slug)->where('id', '!=', $id)->count();
                if ($slugCount > 0) {
                    $slug = $slug . '-' . ($slugCount + 1);
                }
            }

            $blogData = [
                'title' => $request->title,
                'slug' => $slug,
                'content' => $request->content,
                'excerpt' => $request->excerpt,
                'category_id' => $request->category_id ?? $blog->category_id,
                'status' => $request->status ?? $blog->status,
                'published_at' => $request->published_at ?? $blog->published_at,
            ];

            if ($request->hasFile('featured_image')) {
                $this->imageService->deleteImage($blog->featured_image);
                $url = $this->imageService->uploadImage($request->file('featured_image'), 'blog_images');
                $blogData['featured_image'] = $url;
            }

            $blog->update($blogData);

            DB::commit();

            return response()->json([
                'message' => 'Blog berhasil diupdate',
                'data' => $blog->load(['creator', 'category']),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal mengupdate blog',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $blog = BlogPost::findOrFail($id);
            
            if ($blog->featured_image) {
                $this->imageService->deleteImage($blog->featured_image);
            }
            
            $blog->delete();

            return response()->json([
                'message' => 'Blog berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus blog',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $url = $this->imageService->uploadImage($request->file('image'), 'blog_images');

            return response()->json([
                'success' => true,
                'url' => $url,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload gambar',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

