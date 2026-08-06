<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class BlogPost extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'm_blog_post';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\BlogCategory::class, 'category_id', 'id');
    }

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if ($this->featured_image) {
            // 1. Full URL (dari GeminiImageService atau R2) — kembalikan langsung
            if (filter_var($this->featured_image, FILTER_VALIDATE_URL)) {
                return $this->featured_image;
            }
            // 2. URL-path mengandung prefix storage (data lama dari ImageUploadService)
            if (str_starts_with($this->featured_image, '/storage/') || str_starts_with($this->featured_image, 'storage/')) {
                return asset(ltrim($this->featured_image, '/'));
            }
            // 3. Bare filename — prepend folder
            return asset('storage/blog_images/' . $this->featured_image);
        }
        return null;
    }
}

