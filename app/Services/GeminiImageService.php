<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GeminiImageService
{
    private string $apiKey;
    private string $disk;

    public function __construct()
    {
        $this->apiKey = config('services.google_ai.api_key');
        $this->disk = env('FILESYSTEM_DISK', config('app.env') === 'local' ? 'public' : 'r2');
    }

    /**
     * Generate a featured image for a blog article and save it to storage.
     *
     * @param string $title The article title
     * @param string $topic The article topic
     * @return string|null The URL of the generated image, or null on failure
     */
    public function generateAndSave(string $title, string $topic): ?string
    {
        try {
            $imageData = $this->generateImage($title, $topic);

            if (!$imageData) {
                return null;
            }

            return $this->saveImage($imageData);
        } catch (\Exception $e) {
            Log::error('Gemini image generation failed', [
                'error' => $e->getMessage(),
                'title' => $title,
            ]);
            return null;
        }
    }

    /**
     * Call Gemini API to generate an image.
     *
     * @return string|null Base64 decoded image data
     */
    private function generateImage(string $title, string $topic): ?string
    {
        $prompt = "Create a professional, high-quality blog featured image for an article titled: \"{$title}\". "
            . "Topic: {$topic}. "
            . "Style: Modern, clean, professional editorial photography or illustration style. "
            . "Suitable for a nonprofit organization website. No text in the image.";

        $response = Http::timeout(120)->post(
            "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash-exp:generateContent?key={$this->apiKey}",
            [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt],
                        ],
                    ],
                ],
                'generationConfig' => [
                    'responseModalities' => ['TEXT', 'IMAGE'],
                ],
            ]
        );

        if (!$response->successful()) {
            Log::error('Gemini API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;
        }

        $data = $response->json();
        $parts = $data['candidates'][0]['content']['parts'] ?? [];

        foreach ($parts as $part) {
            if (isset($part['inlineData'])) {
                return base64_decode($part['inlineData']['data']);
            }
        }

        Log::warning('Gemini response did not contain image data', [
            'response' => $data,
        ]);

        return null;
    }

    /**
     * Save image data to storage.
     *
     * @param string $imageData Raw image binary data
     * @return string The public URL of the saved image
     */
    private function saveImage(string $imageData): string
    {
        $filename = time() . '_' . uniqid() . '_generated.png';
        $path = 'blog_images/' . $filename;

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk($this->disk);
        $disk->put($path, $imageData, 'public');

        return $disk->url($path);
    }
}
