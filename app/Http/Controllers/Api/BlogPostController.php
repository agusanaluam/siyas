<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Master\BlogPost;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\ImageUploadService;
use App\Services\ClaudeService;
use App\Services\GeminiImageService;

class BlogPostController extends Controller
{

    protected $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function generate(Request $request)
    {
        $user = auth()->user();
        if (!in_array($user->level, ['administrator', 'root'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'topic' => 'required|string|max:500',
            'additional_instructions' => 'nullable|string|max:1000',
            'auto_publish' => 'nullable|boolean',
            'category_id' => 'nullable|integer|exists:blog_categories,id',
        ]);

        try {
            // Generate article text with Claude
            $claudeService = app(ClaudeService::class);
            $article = $claudeService->generateArticle(
                $request->topic,
                $request->additional_instructions
            );

            $slug = Str::slug($article['title']);
            $slugCount = BlogPost::where('slug', $slug)->count();
            if ($slugCount > 0) {
                $slug = $slug . '-' . ($slugCount + 1);
            }

            // Generate featured image with Gemini
            $featuredImageUrl = null;
            try {
                $geminiService = app(GeminiImageService::class);
                $featuredImageUrl = $geminiService->generateAndSave(
                    $article['title'],
                    $request->topic
                );
            } catch (\Exception $e) {
                // Image generation is optional, continue without it
                \Illuminate\Support\Facades\Log::warning('Image generation failed, continuing without image', [
                    'error' => $e->getMessage(),
                ]);
            }

            $blogData = [
                'title' => $article['title'],
                'slug' => $slug,
                'content' => $article['content'],
                'excerpt' => $article['excerpt'],
                'meta_description' => $article['meta_description'],
                'meta_keywords' => $article['meta_keywords'],
                'featured_image' => $featuredImageUrl,
                'category_id' => $request->category_id,
                'status' => $request->auto_publish ?? false,
                'published_at' => ($request->auto_publish) ? now() : null,
                'created_by' => auth()->id(),
            ];

            $blog = BlogPost::create($blogData);

            return response()->json([
                'message' => 'Artikel berhasil digenerate' . ($featuredImageUrl ? ' dengan gambar' : ' tanpa gambar'),
                'data' => $blog->load(['creator', 'category']),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengenerate artikel',
                'error' => $e->getMessage(),
            ], 500);
        }
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

    public function published()
    {
        $blogs = BlogPost::where('status', true)
            ->select('id', 'slug', 'updated_at')
            ->orderBy('published_at', 'desc')
            ->get();

        return response()->json($blogs);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords' => 'nullable|string|max:500',
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
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
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
            'meta_description' => 'nullable|string|max:300',
            'meta_keywords' => 'nullable|string|max:500',
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
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
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

