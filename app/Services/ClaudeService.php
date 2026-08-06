<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClaudeService
{
    private string $apiKey;
    // private string $apiUrl = 'https://api.anthropic.com/v1/messages';
    // private string $model = 'claude-haiku-4-5-20251001';
    private string $apiUrl = 'https://openrouter.ai/api/v1/chat/completions';
    private string $model = 'anthropic/claude-haiku-4-5';

    public function __construct()
    {
        $this->apiKey = config('services.anthropic.api_key');
    }

    public function generateArticle(string $topic, ?string $additionalInstructions = null): array
    {
        $systemPrompt = $this->buildSystemPrompt();
        $userPrompt = $this->buildUserPrompt($topic, $additionalInstructions);

        // $response = Http::withHeaders([
        //     'x-api-key' => $this->apiKey,
        //     'anthropic-version' => '2023-06-01',
        //     'content-type' => 'application/json',
        // ])->timeout(120)->post($this->apiUrl, [
        //     'model' => $this->model,
        //     'max_tokens' => 4096,
        //     'system' => $systemPrompt,
        //     'messages' => [
        //         ['role' => 'user', 'content' => $userPrompt],
        //     ],
        // ]);
        $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . $this->apiKey,
        'content-type' => 'application/json',
    ])->timeout(120)->post($this->apiUrl, [
        'model' => $this->model,
        'max_tokens' => 3000,
        'messages' => [
        ['role' => 'system', 'content' => $systemPrompt],
        ['role' => 'user', 'content' => $userPrompt],
        ],
    ]);

        if (!$response->successful()) {
            Log::error('Claude API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('Gagal mengenerate artikel: ' . $response->body());
        }

        $content = $response->json('choices.0.message.content');
        return $this->parseArticleResponse($content);
    }

    private function buildSystemPrompt(): string
    {
        return <<<'PROMPT'
Kamu adalah seorang penulis artikel profesional untuk Yayasan Cahaya Ayah Bunda (YCAB).
Tugas kamu adalah membuat artikel dalam Bahasa Indonesia yang SEO-friendly dan relevan dengan kegiatan yayasan.

Keyword target yang harus dimasukkan secara natural dalam artikel:
- YCAB
- Cahaya Ayah Bunda
- Ayah Bunda
- Cahaya Ayah
- Cahaya Bunda

Panduan penulisan:
- Gunakan bahasa Indonesia yang baik dan benar
- Artikel harus informatif, menarik, dan bermanfaat bagi pembaca
- Masukkan keyword secara natural, jangan dipaksakan
- Gunakan heading (h2, h3) untuk struktur yang baik
- Minimal 500 kata untuk konten artikel

Kamu HARUS mengembalikan response dalam format JSON yang valid dengan struktur berikut:
{
  "title": "Judul artikel yang menarik dan mengandung keyword",
  "excerpt": "Ringkasan singkat 1-2 kalimat (max 200 karakter)",
  "content": "Konten artikel lengkap dalam format HTML (gunakan tag h2, h3, p, ul, li, strong, em)",
  "meta_description": "Deskripsi meta SEO 150-160 karakter yang mengandung keyword utama",
  "meta_keywords": "keyword1, keyword2, keyword3 (comma-separated, max 10 keywords)"
}

PENTING: Hanya kembalikan JSON yang valid, tanpa teks tambahan di luar JSON.
PROMPT;
    }

    private function buildUserPrompt(string $topic, ?string $additionalInstructions): string
    {
        $prompt = "Buatkan artikel tentang: {$topic}";

        if ($additionalInstructions) {
            $prompt .= "\n\nInstruksi tambahan: {$additionalInstructions}";
        }

        return $prompt;
    }

    private function parseArticleResponse(string $content): array
    {
        $content = trim($content);

        // Remove markdown code fences if present
        if (preg_match('/```(?:json)?\s*([\s\S]*?)```/', $content, $matches)) {
            $content = trim($matches[1]);
        }

        $parsed = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Gagal parsing response dari Claude: ' . json_last_error_msg());
        }

        $required = ['title', 'content', 'excerpt', 'meta_description', 'meta_keywords'];
        foreach ($required as $field) {
            if (empty($parsed[$field])) {
                throw new \Exception("Field '{$field}' tidak ditemukan dalam response Claude.");
            }
        }

        return $parsed;
    }
}
