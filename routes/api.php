<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('auth')->group(function () {
    // Rate limiting lebih ketat untuk endpoint autentikasi
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:3,1');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:3,1');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:3,1');
    Route::get('/verify-email', [AuthController::class, 'verifyEmail'])->middleware('throttle:10,1');
    Route::post('/resend-verification', [AuthController::class, 'resendVerification'])->middleware('throttle:3,1');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });
});

Route::get('/groups', [\App\Http\Controllers\Api\GroupController::class, 'index']);

Route::prefix('location')->group(function () {
    Route::get('/provinsi', [\App\Http\Controllers\Api\LocationController::class, 'getProvinsi']);
    Route::get('/kota/{provinsiId}', [\App\Http\Controllers\Api\LocationController::class, 'getKotaByProvinsi']);
    Route::get('/kecamatan/{kotaId}', [\App\Http\Controllers\Api\LocationController::class, 'getKecamatanByKota']);
    Route::get('/desa/{kecamatanId}', [\App\Http\Controllers\Api\LocationController::class, 'getDesabyKecamatan']);
});

// Public Routes
Route::get('/settings/profile', [\App\Http\Controllers\Api\SettingController::class, 'profile']);
Route::get('/settings/about', [\App\Http\Controllers\Api\SettingController::class, 'about']);
Route::get('/donation-accounts', [\App\Http\Controllers\Api\DonationAccountController::class, 'index']);
Route::get('/campaigns', [\App\Http\Controllers\Api\CampaignController::class, 'index']);
Route::get('/campaigns/{id}', [\App\Http\Controllers\Api\CampaignController::class, 'show']);
Route::get('/blogs', [\App\Http\Controllers\Api\BlogPostController::class, 'index']);
Route::get('/blogs/{id}', [\App\Http\Controllers\Api\BlogPostController::class, 'show']);
Route::get('/hero-slides', [\App\Http\Controllers\Api\LayoutController::class, 'getHeroSlides']);
Route::get('/partners', [\App\Http\Controllers\Api\LayoutController::class, 'getPartners']);
Route::post('/donations/public', [\App\Http\Controllers\Api\DonationController::class, 'storePublic']);
Route::get('/donations/public/{liqNumber}', [\App\Http\Controllers\Api\DonationController::class, 'showByLiqNumber']);

// Payment Routes - Rate limiting untuk prevent abuse
Route::post('/checkout', [\App\Http\Controllers\Api\PaymentController::class, 'generateSnapToken'])->middleware('throttle:10,1');

// Webhook Routes (no auth required, but signature verification required)
Route::post('/donations/webhook', [\App\Http\Controllers\Api\WebhookController::class, 'handleDonationWebhook'])
    ->middleware(\App\Http\Middleware\VerifyMidtransSignature::class);

// Public Categories and Tags
Route::get('/campaign-categories', [\App\Http\Controllers\Api\CampaignCategoryController::class, 'index']);
Route::get('/campaign-categories/{id}', [\App\Http\Controllers\Api\CampaignCategoryController::class, 'show']);
Route::get('/blog-categories', [\App\Http\Controllers\Api\BlogCategoryController::class, 'index']);
Route::get('/blog-categories/{id}', [\App\Http\Controllers\Api\BlogCategoryController::class, 'show']);
Route::get('/blog-tags', [\App\Http\Controllers\Api\BlogTagController::class, 'index']);
Route::get('/blog-tags/{id}', [\App\Http\Controllers\Api\BlogTagController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/groups', [\App\Http\Controllers\Api\GroupController::class, 'store']);
    Route::get('/groups/{id}', [\App\Http\Controllers\Api\GroupController::class, 'show']);
    Route::put('/groups/{id}', [\App\Http\Controllers\Api\GroupController::class, 'update']);
    Route::post('/groups/{id}', [\App\Http\Controllers\Api\GroupController::class, 'update']);
    Route::delete('/groups/{id}', [\App\Http\Controllers\Api\GroupController::class, 'destroy']);
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('/leaderboard', [DashboardController::class, 'volunteerLeaderboard']);
    });

    Route::prefix('campaigns')->group(function () {
        Route::get('/pending', [\App\Http\Controllers\Api\CampaignController::class, 'getPending']);
        Route::get('/running', [\App\Http\Controllers\Api\CampaignController::class, 'getRunning']);
        Route::get('/closed', [\App\Http\Controllers\Api\CampaignController::class, 'getClosed']);
        Route::post('/', [\App\Http\Controllers\Api\CampaignController::class, 'store']);
        Route::put('/{id}', [\App\Http\Controllers\Api\CampaignController::class, 'update']);
        Route::post('/{id}', [\App\Http\Controllers\Api\CampaignController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\CampaignController::class, 'destroy']);
    });

    Route::prefix('campaign-categories')->group(function () {
        Route::post('/', [\App\Http\Controllers\Api\CampaignCategoryController::class, 'store']);
        Route::put('/{id}', [\App\Http\Controllers\Api\CampaignCategoryController::class, 'update']);
        Route::post('/{id}', [\App\Http\Controllers\Api\CampaignCategoryController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\CampaignCategoryController::class, 'destroy']);
    });

    Route::prefix('blogs')->group(function () {
        Route::post('/', [\App\Http\Controllers\Api\BlogPostController::class, 'store']);
        Route::post('/upload-image', [\App\Http\Controllers\Api\BlogPostController::class, 'uploadImage']);
        Route::put('/{id}', [\App\Http\Controllers\Api\BlogPostController::class, 'update']);
        Route::post('/{id}', [\App\Http\Controllers\Api\BlogPostController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\BlogPostController::class, 'destroy']);
    });

    Route::prefix('blog-tags')->group(function () {
        Route::post('/', [\App\Http\Controllers\Api\BlogTagController::class, 'store']);
        Route::put('/{id}', [\App\Http\Controllers\Api\BlogTagController::class, 'update']);
        Route::post('/{id}', [\App\Http\Controllers\Api\BlogTagController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\BlogTagController::class, 'destroy']);
    });

    Route::prefix('blog-categories')->group(function () {
        Route::post('/', [\App\Http\Controllers\Api\BlogCategoryController::class, 'store']);
        Route::put('/{id}', [\App\Http\Controllers\Api\BlogCategoryController::class, 'update']);
        Route::post('/{id}', [\App\Http\Controllers\Api\BlogCategoryController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\BlogCategoryController::class, 'destroy']);
    });

    Route::prefix('user')->group(function () {
        Route::get('/profile', [\App\Http\Controllers\Api\UserController::class, 'profile']);
        Route::put('/profile', [\App\Http\Controllers\Api\UserController::class, 'updateProfile']);
        Route::post('/profile', [\App\Http\Controllers\Api\UserController::class, 'updateProfile']);
    });

    Route::prefix('settings')->group(function () {
        Route::put('/profile', [\App\Http\Controllers\Api\SettingController::class, 'updateProfile']);
        Route::post('/profile', [\App\Http\Controllers\Api\SettingController::class, 'updateProfile']);
        Route::put('/about', [\App\Http\Controllers\Api\SettingController::class, 'updateAbout']);
        Route::post('/about', [\App\Http\Controllers\Api\SettingController::class, 'updateAbout']);
    });

    Route::prefix('donation-accounts')->group(function () {
        Route::post('/', [\App\Http\Controllers\Api\DonationAccountController::class, 'store']);
        Route::put('/{id}', [\App\Http\Controllers\Api\DonationAccountController::class, 'update']);
        Route::post('/{id}', [\App\Http\Controllers\Api\DonationAccountController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\DonationAccountController::class, 'destroy']);
    });

    Route::prefix('donations')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\DonationController::class, 'index']);
        Route::get('/history', [\App\Http\Controllers\Api\DonationController::class, 'getHistory']);
        Route::get('/transfer', [\App\Http\Controllers\Api\DonationController::class, 'getTransfer']);
        Route::post('/', [\App\Http\Controllers\Api\DonationController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\Api\DonationController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Api\DonationController::class, 'update']);
        Route::post('/{id}', [\App\Http\Controllers\Api\DonationController::class, 'update']);
        Route::patch('/{id}/approve', [\App\Http\Controllers\Api\DonationController::class, 'approve']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\DonationController::class, 'destroy']);
    });

    Route::prefix('volunteers')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\VolunteerController::class, 'index']);
        Route::get('/inactive', [\App\Http\Controllers\Api\VolunteerController::class, 'getInactive']);
        Route::post('/', [\App\Http\Controllers\Api\VolunteerController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\Api\VolunteerController::class, 'show']);
        Route::put('/{id}', [\App\Http\Controllers\Api\VolunteerController::class, 'update']);
        Route::post('/{id}', [\App\Http\Controllers\Api\VolunteerController::class, 'update']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\VolunteerController::class, 'destroy']);
    });

    Route::prefix('hero-slides')->group(function () {
        Route::post('/', [\App\Http\Controllers\Api\LayoutController::class, 'storeHeroSlide']);
        Route::post('/{id}', [\App\Http\Controllers\Api\LayoutController::class, 'updateHeroSlide']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\LayoutController::class, 'deleteHeroSlide']);
    });

    Route::prefix('partners')->group(function () {
        Route::post('/', [\App\Http\Controllers\Api\LayoutController::class, 'storePartner']);
        Route::delete('/{id}', [\App\Http\Controllers\Api\LayoutController::class, 'deletePartner']);
    });

    Route::prefix('ramadan')->group(function () {
        Route::get('/habits', [\App\Http\Controllers\Api\RamadanHabitController::class, 'index']);
        Route::post('/habits/save-progress', [\App\Http\Controllers\Api\RamadanHabitController::class, 'saveProgress']);
        Route::get('/leaderboard', [\App\Http\Controllers\Api\RamadanHabitController::class, 'leaderboard']);
        Route::get('/stats', [\App\Http\Controllers\Api\RamadanHabitController::class, 'stats']);

        // Muraja'ah routes
        Route::get('/surah-list', [\App\Http\Controllers\Api\RamadanMurajaahController::class, 'surahList']);
        Route::get('/surah/{nomor}', [\App\Http\Controllers\Api\RamadanMurajaahController::class, 'surahDetail']);
        Route::get('/murajaah', [\App\Http\Controllers\Api\RamadanMurajaahController::class, 'index']);
        Route::post('/murajaah', [\App\Http\Controllers\Api\RamadanMurajaahController::class, 'store']);
        Route::delete('/murajaah/{id}', [\App\Http\Controllers\Api\RamadanMurajaahController::class, 'destroy']);
    });
});

