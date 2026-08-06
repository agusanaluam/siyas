<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    /**
     * Add CORS headers to response
     */
    protected function corsResponse($response)
    {
        $origin = request()->header('Origin');
        $allowedOrigins = array_filter(explode(',', env('CORS_ALLOWED_ORIGINS', 'http://localhost:3000')));

        // Tambahkan localhost untuk development
        if (config('app.env') !== 'production') {
            $allowedOrigins = array_merge($allowedOrigins, [
                'http://localhost:3000',
                'http://127.0.0.1:3000',
                'http://localhost:3001',
                'http://127.0.0.1:3001',
            ]);
        }

        $allowedOrigin = in_array($origin, $allowedOrigins) ? $origin : ($allowedOrigins[0] ?? '*');

        return $response
            ->header('Access-Control-Allow-Origin', $allowedOrigin)
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, PATCH, HEAD')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin, X-CSRF-TOKEN')
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Vary', 'Origin');
    }

    /**
     * Handle exception dengan aman (tidak expose sensitive info di production)
     */
    protected function handleException(\Exception $e, string $defaultMessage = 'Terjadi kesalahan pada server'): JsonResponse
    {
        $isProduction = config('app.env') === 'production';

        // Log error detail untuk debugging
        \Illuminate\Support\Facades\Log::error('Controller Exception', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        $response = [
            'message' => $defaultMessage,
        ];

        // Hanya expose error detail di development
        if (!$isProduction) {
            $response['error'] = $e->getMessage();
            $response['file'] = $e->getFile();
            $response['line'] = $e->getLine();
        }

        return response()->json($response, 500);
    }
}
