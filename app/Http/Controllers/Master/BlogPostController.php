<?php

namespace App\Http\Controllers\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Master\BlogPost;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BlogPostController extends Controller
{
    /**
     * Check if image processing is available
     */
    private function isImageProcessingAvailable()
    {
        return extension_loaded('gd') || extension_loaded('imagick');
    }
    function index()
    {
        if (request()->ajax()) {
            $data = BlogPost::with('creator')->orderBy('created_at', 'desc');
            return DataTables::of($data->get())
                ->addIndexColumn()
                ->editColumn('title', function ($data) {
                    return '<a href="' . route('blog.details', $data->id) . '">' . $data->title . '</a>';
                })
                ->editColumn('status', function ($data) {
                    if ($data->status) {
                        $badge = 'success';
                        $label = 'Published';
                    } else {
                        $badge = 'warning';
                        $label = 'Draft';
                    }
                    return '<span class="badge bg-outline-' . $badge . '">' . $label . '</span>';
                })
                ->editColumn('published_at', function ($data) {
                    if ($data->published_at) {
                        return date('d-M-Y H:i', strtotime($data->published_at));
                    }
                    return '-';
                })
                ->editColumn('created_at', function ($data) {
                    return date('d-M-Y H:i', strtotime($data->created_at));
                })
                ->addColumn('creator_name', function ($data) {
                    return $data->creator ? $data->creator->name : '-';
                })
                ->addColumn('action', function ($data) {
                    if ((auth()->user()->level == 'administrator') || (auth()->user()->level == 'root')) {
                        return
                            '<div class="edit-delete-action">
                                <a class="edit me-2 p-2" href="' . route('blog.edit', $data->id) . '">
                                    <i data-feather="edit" class="feather-edit"></i>
                                </a>
                                <a class="confirm-text p-2 delete" href="javascript:void(0);" data-id="' . $data->id . '">
                                    <i data-feather="trash-2" class="feather-trash-2"></i>
                                </a>
                            </div>';
                    } else {
                        return '<div class="edit-delete-action"></div>';
                    }
                })
                ->rawColumns(['title', 'status', 'action'])
                ->make();
        }
        return view('pages.blog.list');
    }

    function create()
    {
        return view('pages.blog.add-blog');
    }

    function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'content' => 'required',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'nullable|in:on,1,true',
            'published_at' => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {
            $slug = Str::slug($request->title);
            $slugCount = BlogPost::where('slug', $slug)->count();
            if ($slugCount > 0) {
                $slug = $slug . '-' . ($slugCount + 1);
            }

            // Convert checkbox status to boolean
            $status = $request->has('status') && ($request->status == 'on' || $request->status == '1' || $request->status == 'true' || $request->status === true);

            // Parse published_at date
            $publishedAt = null;
            if ($request->published_at) {
                // Format dari datetimepicker: DD-MM-YYYY
                $dateParts = explode('-', $request->published_at);
                if (count($dateParts) == 3) {
                    $publishedAt = date('Y-m-d H:i:s', strtotime($dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0]));
                } else {
                    $publishedAt = date('Y-m-d H:i:s', strtotime($request->published_at));
                }
            }

            $blogPost = BlogPost::create([
                'title' => $request->title,
                'slug' => $slug,
                'content' => $request->content,
                'excerpt' => $request->excerpt,
                'status' => $status,
                'published_at' => $publishedAt,
                'created_by' => auth()->id(),
            ]);

            if ($request->hasFile('featured_image')) {
                $image = $request->file('featured_image');
                $extension = $image->getClientOriginalExtension();
                $filenameSimpan = Str::random(16) . '_' . time() . '.' . $extension;
                
                if ($this->isImageProcessingAvailable()) {
                    try {
                        $manager = new ImageManager(new Driver());
                        $image->storeAs('public/blog_pictures/', $filenameSimpan);
                        $resizedImage = $manager->read($image->getPathname())->scaleDown(height: 500)->encodeByExtension($extension);
                        Storage::disk('public')->put('blog_pictures/resized_' . $filenameSimpan, (string) $resizedImage);
                    } catch (\Exception $e) {
                        // Jika gagal resize, gunakan gambar asli
                        $image->storeAs('public/blog_pictures/', $filenameSimpan);
                        Storage::disk('public')->copy('blog_pictures/' . $filenameSimpan, 'blog_pictures/resized_' . $filenameSimpan);
                    }
                } else {
                    // Jika extension tidak tersedia, simpan file asli
                    $image->storeAs('public/blog_pictures/', $filenameSimpan);
                    Storage::disk('public')->copy('blog_pictures/' . $filenameSimpan, 'blog_pictures/resized_' . $filenameSimpan);
                }

                $blogPost->update([
                    'featured_image' => $filenameSimpan,
                ]);
            }

            DB::commit();

            return redirect()->route('blog.list')->withSuccess('Berhasil menambahkan blog post');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    function details($id)
    {
        $data = BlogPost::with('creator')->findOrFail($id);
        return view('pages.blog.blog-details', compact('data'));
    }

    function edit($id)
    {
        $data = BlogPost::findOrFail($id);
        return view('pages.blog.edit-blog', compact('data'));
    }

    function update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'title' => 'required|string|max:200',
            'content' => 'required',
            'excerpt' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'nullable|in:on,1,true',
            'published_at' => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {
            $blogPost = BlogPost::findOrFail($request->id);

            $slug = Str::slug($request->title);
            if ($slug != $blogPost->slug) {
                $slugCount = BlogPost::where('slug', $slug)->where('id', '!=', $request->id)->count();
                if ($slugCount > 0) {
                    $slug = $slug . '-' . ($slugCount + 1);
                }
            }

            // Convert checkbox status to boolean
            $status = $request->has('status') && ($request->status == 'on' || $request->status == '1' || $request->status == 'true' || $request->status === true);

            // Parse published_at date
            $publishedAt = null;
            if ($request->published_at) {
                // Format dari datetimepicker: DD-MM-YYYY
                $dateParts = explode('-', $request->published_at);
                if (count($dateParts) == 3) {
                    $publishedAt = date('Y-m-d H:i:s', strtotime($dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0]));
                } else {
                    $publishedAt = date('Y-m-d H:i:s', strtotime($request->published_at));
                }
            }

            $blogPost->update([
                'title' => $request->title,
                'slug' => $slug,
                'content' => $request->content,
                'excerpt' => $request->excerpt,
                'status' => $status,
                'published_at' => $publishedAt,
                'updated_by' => auth()->id(),
            ]);

            if ($request->hasFile('featured_image')) {
                if ($blogPost->featured_image && Storage::exists("public/blog_pictures/" . $blogPost->featured_image)) {
                    Storage::disk('public')->delete('blog_pictures/' . $blogPost->featured_image);
                    Storage::disk('public')->delete('blog_pictures/resized_' . $blogPost->featured_image);
                }

                $image = $request->file('featured_image');
                $extension = $image->getClientOriginalExtension();
                $filenameSimpan = Str::random(16) . '_' . time() . '.' . $extension;
                
                if ($this->isImageProcessingAvailable()) {
                    try {
                        $manager = new ImageManager(new Driver());
                        $image->storeAs('public/blog_pictures/', $filenameSimpan);
                        $resizedImage = $manager->read($image->getPathname())->scaleDown(height: 500)->encodeByExtension($extension);
                        Storage::disk('public')->put('blog_pictures/resized_' . $filenameSimpan, (string) $resizedImage);
                    } catch (\Exception $e) {
                        // Jika gagal resize, gunakan gambar asli
                        $image->storeAs('public/blog_pictures/', $filenameSimpan);
                        Storage::disk('public')->copy('blog_pictures/' . $filenameSimpan, 'blog_pictures/resized_' . $filenameSimpan);
                    }
                } else {
                    // Jika extension tidak tersedia, simpan file asli
                    $image->storeAs('public/blog_pictures/', $filenameSimpan);
                    Storage::disk('public')->copy('blog_pictures/' . $filenameSimpan, 'blog_pictures/resized_' . $filenameSimpan);
                }

                $blogPost->update([
                    'featured_image' => $filenameSimpan,
                ]);
            }

            DB::commit();

            return redirect()->route('blog.list')->withSuccess('Berhasil mengupdate blog post');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            ]);

            if (!$request->hasFile('image')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gambar tidak ditemukan'
                ], 400);
            }

            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $filenameSimpan = Str::random(16) . '_' . time() . '.' . $extension;
            
            // Proses dan simpan gambar
            if ($this->isImageProcessingAvailable()) {
                try {
                    $manager = new ImageManager(new Driver());
                    
                    // Compress dan resize jika terlalu besar
                    $maxSize = 2048 * 1024; // 2MB
                    $fileSize = $image->getSize();
                    
                    if ($fileSize > $maxSize) {
                        $img = $manager->read($image->getPathname());
                        
                        // Resize jika terlalu besar
                        if ($img->width() > 1920) {
                            $img->scaleDown(width: 1920);
                        }
                        
                        // Compress dengan quality yang sesuai
                        $quality = 85;
                        if (in_array(strtolower($extension), ['jpg', 'jpeg'])) {
                            $processedImage = $img->toJpeg($quality);
                        } else {
                            $processedImage = $img->encodeByExtension($extension);
                        }
                        
                        Storage::disk('public')->put('blog_pictures/content/' . $filenameSimpan, (string) $processedImage);
                    } else {
                        // Simpan asli jika ukurannya sudah kecil
                        $image->storeAs('public/blog_pictures/content/', $filenameSimpan);
                    }
                } catch (\Exception $e) {
                    // Fallback: simpan file asli
                    $image->storeAs('public/blog_pictures/content/', $filenameSimpan);
                }
            } else {
                // Jika extension tidak tersedia, simpan file asli
                $image->storeAs('public/blog_pictures/content/', $filenameSimpan);
            }

            $url = asset('storage/blog_pictures/content/' . $filenameSimpan);

            return response()->json([
                'success' => true,
                'url' => $url,
                'message' => 'Gambar berhasil diupload'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    function destroy($id)
    {
        try {
            $data = BlogPost::findOrFail($id);
            
            if ($data->featured_image && Storage::exists("public/blog_pictures/" . $data->featured_image)) {
                Storage::disk('public')->delete('blog_pictures/' . $data->featured_image);
                Storage::disk('public')->delete('blog_pictures/resized_' . $data->featured_image);
            }
            
            $data->delete();

            return response()->json(['success' => true, 'message' => 'Berhasil menghapus blog post']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}

