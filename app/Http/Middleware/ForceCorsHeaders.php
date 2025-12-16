<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ForceCorsHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path();

        // Only apply to API routes
        if (!str_starts_with($path, 'api/') && $path !== 'sanctum/csrf-cookie') {
            return $next($request);
        }

        // Debug logging
        Log::info('ForceCorsHeaders middleware executed', [
            'path' => $path,
            'full_path' => $request->fullUrl(),
            'method' => $request->method(),
            'origin' => $request->header('Origin'),
            'is_api' => str_starts_with($path, 'api/'),
        ]);

        $origin = $request->header('Origin');
        $allowedOrigins = [
            'http://localhost:3000',
            'http://127.0.0.1:3000',
            'http://localhost:3001',
            'http://127.0.0.1:3001',
        ];

        // Handle preflight OPTIONS request
        if ($request->getMethod() === 'OPTIONS') {
            $allowedOrigin = $this->getAllowedOrigin($origin, $allowedOrigins);
            return response('', 200)
                ->header('Access-Control-Allow-Origin', $allowedOrigin)
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, PATCH, HEAD')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin, X-CSRF-TOKEN')
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header('Access-Control-Max-Age', '86400')
                ->header('Vary', 'Origin');
        }

        // Determine allowed origin first
        $allowedOrigin = $this->getAllowedOrigin($origin, $allowedOrigins);

        // Process the request
        try {
            $response = $next($request);
        } catch (\Exception $e) {
            // Even for exceptions, we need to add CORS headers
            $response = response()->json([
                'message' => $e->getMessage()
            ], 500)
                ->header('Access-Control-Allow-Origin', $allowedOrigin)
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, PATCH, HEAD')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin, X-CSRF-TOKEN')
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header('Vary', 'Origin');

            return $response;
        }

        // Check if response is 404 and convert HTML to JSON if needed
        if ($response->getStatusCode() === 404 && str_contains($response->headers->get('Content-Type', ''), 'text/html')) {
            $response = response()->json([
                'message' => 'Resource not found'
            ], 404);
        }

        // Force add CORS headers - remove any existing CORS headers first to avoid conflicts
        $response->headers->remove('Access-Control-Allow-Origin');
        $response->headers->remove('Access-Control-Allow-Methods');
        $response->headers->remove('Access-Control-Allow-Headers');
        $response->headers->remove('Access-Control-Allow-Credentials');
        $response->headers->remove('Access-Control-Max-Age');

        // Set CORS headers using header() method for maximum compatibility
        $response->headers->set('Access-Control-Allow-Origin', $allowedOrigin, false);
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, PATCH, HEAD', false);
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin, X-CSRF-TOKEN', false);
        $response->headers->set('Access-Control-Allow-Credentials', 'true', false);
        $response->headers->set('Access-Control-Max-Age', '86400', false);

        // Add Vary header if not exists
        if (!$response->headers->has('Vary')) {
            $response->headers->set('Vary', 'Origin', false);
        } else {
            $vary = $response->headers->get('Vary');
            if (stripos($vary, 'Origin') === false) {
                $response->headers->set('Vary', $vary . ', Origin', false);
            }
        }

        Log::info('ForceCorsHeaders: CORS headers added', [
            'path' => $request->path(),
            'origin' => $origin,
            'allowed_origin' => $allowedOrigin,
            'response_status' => $response->getStatusCode(),
            'content_type' => $response->headers->get('Content-Type'),
        ]);

        return $response;
    }

    /**
     * Get allowed origin based on request origin
     */
    private function getAllowedOrigin(?string $origin, array $allowedOrigins): string
    {
        // Default to first allowed origin
        $allowedOrigin = $allowedOrigins[0];

        if ($origin) {
            // Check exact match first
            if (in_array($origin, $allowedOrigins)) {
                $allowedOrigin = $origin;
            }
            // Check if origin matches pattern (localhost or 127.0.0.1 with port 3000-3001)
            elseif (preg_match('/^http:\/\/(localhost|127\.0\.0\.1):(3000|3001)$/', $origin)) {
                $allowedOrigin = $origin;
            }
        }

        return $allowedOrigin;
    }
}
