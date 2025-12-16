<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Add CORS middleware first in API middleware stack
        $middleware->api(prepend: [
            \App\Http\Middleware\ForceCorsHeaders::class,
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);
        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Ensure CORS headers are added even for exceptions and 404 errors
        $exceptions->render(function (\Throwable $e, $request) {
            $path = $request->path();

            // Only handle API routes
            if (str_starts_with($path, 'api/')) {
                $origin = $request->header('Origin');
                $allowedOrigins = [
                    'http://localhost:3000',
                    'http://127.0.0.1:3000',
                    'http://localhost:3001',
                    'http://127.0.0.1:3001',
                ];

                $allowedOrigin = in_array($origin, $allowedOrigins) ? $origin : $allowedOrigins[0];

                // Determine status code
                $statusCode = 500;
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                    $statusCode = 404;
                } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                    $statusCode = 405;
                } elseif (method_exists($e, 'getStatusCode')) {
                    $statusCode = $e->getStatusCode();
                } elseif ($e->getCode() >= 400 && $e->getCode() < 600) {
                    $statusCode = $e->getCode();
                }

                $response = response()->json([
                    'message' => $statusCode === 404 ? 'Resource not found' : $e->getMessage(),
                    'error' => $e->getMessage(),
                ], $statusCode);

                // Add CORS headers using header() method
                $response->headers->set('Access-Control-Allow-Origin', $allowedOrigin, false);
                $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, PATCH, HEAD', false);
                $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin, X-CSRF-TOKEN', false);
                $response->headers->set('Access-Control-Allow-Credentials', 'true', false);
                $response->headers->set('Access-Control-Max-Age', '86400', false);
                $response->headers->set('Vary', 'Origin', false);

                \Illuminate\Support\Facades\Log::info('Exception handler: CORS headers added', [
                    'path' => $path,
                    'status_code' => $statusCode,
                    'origin' => $origin,
                    'allowed_origin' => $allowedOrigin,
                ]);

                return $response;
            }
        });
    })->create();
