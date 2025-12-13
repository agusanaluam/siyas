<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Master\BlogPost;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BlogPostController extends Controller
{
    public function index()
    {
        $user = auth('sanctum')->user();
        
        $query = BlogPost::with(['creator', 'category'])
            ->orderBy('created_at', 'desc');

        // Jika user login tapi bukan administrator/root, filter by created_by
        if ($user && !in_array($user->level, ['administrator', 'root'])) {
            $query->where('created_by', $user->id);
        }
        
        // Jika guest (tidak login), mungkin kita mau filter hanya yang published? 
        // Tapi request user sekarang "return sesuai role".
        // Asumsi: jika guest, behavior eksisting (return all) atau return all? 
        // User bilang "jika role root atau administrator munculkan semua blog", imply "selain itu dibatasi".
        // Tapi untuk public viewing (guest), biasanya butuh semua tapi yang 'active'. 
        // Saat ini logic saya: Guest ($user null) -> skip if -> return all. 
        // Volunteer ($user exist) -> masuk if -> return own.
        // Admin ($user exist) -> skip if -> return all.
        
        // Tambahan constraint untuk guest? (Optional: $query->where('status', true))
        // Mengikuti instruksi user mentah-mentah: "hanya perlu meminculkan blog yang dibuat oleh user yang login"
        // Ini berisiko menyembunyikan blog orang lain dari guest.
        // Namun konteksnya adalah "Admin/Manage" page.
        // Mari kita stick to the "User Login" logic.
        
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
                $file = $request->file('featured_image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('blog_images', $filename, 'public');

                $manager = new ImageManager(new Driver());
                $image = $manager->read(storage_path('app/public/' . $path));
                $image->scaleDown(width: 1200);
                $image->save(storage_path('app/public/' . $path), quality: 85);

                $blogData['featured_image'] = $filename;
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
                if ($blog->featured_image && Storage::exists("public/blog_images/" . $blog->featured_image)) {
                    Storage::disk('public')->delete('blog_images/' . $blog->featured_image);
                }

                $file = $request->file('featured_image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('blog_images', $filename, 'public');

                $manager = new ImageManager(new Driver());
                $image = $manager->read(storage_path('app/public/' . $path));
                $image->scaleDown(width: 1200);
                $image->save(storage_path('app/public/' . $path), quality: 85);

                $blogData['featured_image'] = $filename;
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
            
            if ($blog->featured_image && Storage::exists("public/blog_images/" . $blog->featured_image)) {
                Storage::disk('public')->delete('blog_images/' . $blog->featured_image);
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
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('blog_images', $filename, 'public');

            $url = asset('storage/' . $path);

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

