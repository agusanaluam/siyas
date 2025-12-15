<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageUploadService
{
    protected $disk = 'r2';

    /**
     * Upload an image to R2, optionally resizing it.
     *
     * @param UploadedFile $file The file to upload
     * @param string $folder The folder in the bucket
     * @param int|null $maxWidth The maximum width for resizing (null to skip resizing)
     * @return string The public URL of the uploaded image
     */
    public function uploadImage(UploadedFile $file, string $folder, ?int $maxWidth = 1200): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $folder . '/' . $filename;

        // Try to resize if GD is available and maxWidth is set
        if ($maxWidth && extension_loaded('gd')) {
            try {
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file);
                $image->scaleDown(width: $maxWidth);
                
                // Encode the image to get string content
                $encoded = $image->toJpeg(quality: 85); // Default to jpeg for optimization, or keep original format if needed
                
                // If the original was png/gif, we might want to keep transparency using toPng/toGif, 
                // but usually for photos jpeg is fine. Let's try to match input or just use the resized objects.
                // However, Intervention 3 writes to file system or returns encoded object.
                // Storage::put requires string content or resource.
                
                // Let's keep it simple: upload the modified buffer.
                // Note: toJpeg() returns an EncodedImage object, cast to string for content.
                Storage::disk($this->disk)->put($path, (string)$encoded, 'public');
                
                return $this->getUrl($path);
                
            } catch (\Throwable $e) {
                // Check if it's just an image processing error or something else.
                // If resizing fails, fallback to direct upload below.
            }
        }

        // Fallback: Direct upload without processing
        Storage::disk($this->disk)->putFileAs($folder, $file, $filename, 'public');

        // Note: putFileAs uploads the file stream.
        // If we used put() above, we did specific content.
        // We need to return the URL for the path we just created.
        
        return $this->getUrl($path);
    }

    /**
     * Delete an image from R2.
     *
     * @param string $pathOrUrl The full URL or relative path
     * @return bool
     */
    public function deleteImage(string $pathOrUrl): bool
    {
        if (empty($pathOrUrl)) {
            return false;
        }

        // Extract path from URL if full URL is passed
        $path = $this->parsePathFromUrl($pathOrUrl);

        if (Storage::disk($this->disk)->exists($path)) {
            return Storage::disk($this->disk)->delete($path);
        }

        return false;
    }

    /**
     * Get the full URL for a given path.
     *
     * @param string $path
     * @return string
     */
    protected function getUrl(string $path): string
    {
        return Storage::disk($this->disk)->url($path);
    }

    /**
     * Parse the relative storage path from a full URL.
     *
     * @param string $url
     * @return string
     */
    protected function parsePathFromUrl(string $url): string
    {
        // Check if the URL starts with the configured R2 URL
        $baseUrl = config('filesystems.disks.r2.url');
        
        if ($baseUrl && str_starts_with($url, $baseUrl)) {
            return str_replace($baseUrl . '/', '', $url);
        }

        // Also handle cases where path might already be relative (not a URL)
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }
        
        // If it's a URL but not matching our base (maybe legacy local url?), return as is or handle logic
        // For now assume if it's a URL it matches our structure or we can't extract it easily without more parsing.
        // A simple parse_url approach:
        return parse_url($url, PHP_URL_PATH) ?? $url;
    }
}
