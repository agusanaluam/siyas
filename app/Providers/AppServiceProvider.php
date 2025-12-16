<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('currency', function ( $expression ) {
                return "Rp. <?php echo number_format($expression,0,',','.'); ?>";
          });

        // Macro untuk menambahkan CORS headers otomatis ke semua JSON response
        Response::macro('jsonWithCors', function ($data, $status = 200, array $headers = [], $options = 0) {
            $response = response()->json($data, $status, $headers, $options);

            $origin = request()->header('Origin');
            $allowedOrigins = [
                'http://localhost:3000',
                'http://127.0.0.1:3000',
                'http://localhost:3001',
                'http://127.0.0.1:3001',
            ];
            $allowedOrigin = in_array($origin, $allowedOrigins) ? $origin : $allowedOrigins[0];

            return $response
                ->header('Access-Control-Allow-Origin', $allowedOrigin)
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS, PATCH, HEAD')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, Accept, Origin, X-CSRF-TOKEN')
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header('Vary', 'Origin');
        });
    }
}
