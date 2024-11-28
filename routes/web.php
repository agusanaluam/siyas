<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\GroupController;
use App\Http\Controllers\Master\LocationController;
use App\Http\Controllers\Master\CampaignController;
use App\Http\Controllers\Master\VolunteerController;
use App\Http\Controllers\Master\CampaignCategoryController;
use App\Http\Controllers\Transaction\DonationController;
use App\Http\Controllers\Transaction\MutationController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthController::class, 'index'])->name('index');
Route::get('/login', [AuthController::class, 'signIn'])->name('login');
Route::post('/user-register', [AuthController::class, 'userRegister'])->name('register.user');
Route::post('/user-login', [AuthController::class, 'userSignin'])->name('login.user');
Route::get('/register', [AuthController::class, 'registration'])->name('register');
Route::get('/email-verification', [AuthController::class, 'emailVerification'])->name('email-verification');
Route::post('/resend-verification', [AuthController::class, 'resendVerification'])->name('email-verification.resend');
Route::get('/email-verify', [AuthController::class, 'verifyEmail'])->name('email-verification.verify');
Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
Route::post('/forgot-password-verify', [AuthController::class, 'forgotPasswordVerification'])->name('forgot-password.verify');
Route::get('/reset-password', [AuthController::class, 'resetPassword'])->name('reset-password');
Route::post('/reset-password-verify', [AuthController::class, 'resetPasswordVerification'])->name('reset-password.verify');


Route::group(['middleware' => 'auth'],function () {
    Route::prefix('/dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/leaderboard',[DashboardController::class, 'volunteerLeaderboard'])->name('dashboard.leaderboard');
    });
    Route::post('signout', [AuthController::class, 'signOut'])->name('signout');

    Route::prefix('user')->group(function() {
        Route::get('/profile', [UsersController::class, 'profile'])->name('user.profile');
        Route::post('/profile/update', [UsersController::class, 'profileUpdate'])->name('user.profile.update');

    });

    Route::prefix('location')->group(function () {
        Route::get('/get-kota/{provinsiId}', [LocationController::class, 'getKotaByProvinsi'])->name('location.kota');
        Route::get('/get-kecamatan/{kotaId}', [LocationController::class, 'getKecamatanByKota'])->name('location.kecamatan');
        Route::get('/get-desa/{kecamatanId}', [LocationController::class, 'getDesabyKecamatan'])->name('location.desa');
    });

    Route::prefix('campaign')->group(function () {
        Route::get('/', [CampaignController::class, 'index'])->name('campaign.list');
        Route::get('/search', [CampaignController::class, 'search'])->name('campaign.search');
        Route::get('/create', [CampaignController::class, 'create'])->name('campaign.create');
        Route::post('/store', [CampaignController::class, 'store'])->name('campaign.store');
        Route::get('/edit/{id}', [CampaignController::class, 'edit'])->name('campaign.edit');
        Route::get('/details/{id}', [CampaignController::class, 'details'])->name('campaign.details');
        Route::post('/update', [CampaignController::class, 'update'])->name('campaign.update');
        Route::delete('/{id}', [CampaignController::class, 'destroy'])->name('campaign.destroy');

        Route::get('/pending', [CampaignController::class, 'getPendingCampaign'])->name('campaign.list.pending');
        Route::get('/running', [CampaignController::class, 'getRunningCampaign'])->name('campaign.list.running');
        Route::get('/close', [CampaignController::class, 'getClosedCampaign'])->name('campaign.list.closed');
        Route::prefix('category')->group(function() {
            Route::get('/', [CampaignCategoryController::class, 'index'])->name('campaign.categories');
            Route::post('/store', [CampaignCategoryController::class, 'store'])->name('campaign.categories.store');
            Route::get('/{id}', [CampaignCategoryController::class, 'edit'])->name('campaign.categories.edit');
            Route::post('/update', [CampaignCategoryController::class, 'update'])->name('campaign.categories.update');
            Route::delete('/{id}', [CampaignCategoryController::class, 'destroy'])->name('campaign.categories.destroy');

        });

    });

    Route::prefix('volunteer')->group(function () {
        Route::get('/', [VolunteerController::class, 'index'])->name('volunteer.list');
        Route::get('/inactive', [VolunteerController::class, 'getInactiveVolunteer'])->name('volunteer.list.inactive');
        Route::get('/create', [VolunteerController::class, 'create'])->name('volunteer.create');
        Route::post('/store', [VolunteerController::class, 'store'])->name('volunteer.store');
        Route::get('/detail/{id}', [VolunteerController::class, 'edit'])->name('volunteer.edit');
        Route::post('/update', [VolunteerController::class, 'update'])->name('volunteer.update');
        Route::delete('/{id}', [VolunteerController::class, 'destroy'])->name('volunteer.destroy');


        Route::prefix('group')->group(function () {
            Route::get('/', [GroupController::class, 'index'])->name('group.list');
            Route::get('/data', [GroupController::class, 'data'])->name('group.data');
            Route::post('/store', [GroupController::class, 'store'])->name('group.store');
            Route::get('/{id}', [GroupController::class, 'edit'])->name('group.edit');
            Route::post('/update', [GroupController::class, 'update'])->name('group.update');
            Route::delete('/{id}', [GroupController::class, 'destroy'])->name('group.destroy');
        });
    });

    Route::prefix('donation')->group(function () {
        Route::get('/', [DonationController::class, 'index'])->name('donation.list');
        Route::get('/create/{liq_number}', [DonationController::class, 'create'])->name('donation.create');
        Route::get('/check/{liq}', [DonationController::class, 'check'])->name('donation.check');
        Route::post('/store', [DonationController::class, 'store'])->name('donation.store');
        Route::put('/update', [DonationController::class, 'update'])->name('donation.update');
        Route::patch('/approve/{id}', [DonationController::class, 'approve'])->name('donation.approve');
        Route::get('/history', [DonationController::class, 'history'])->name('donation.history');
        Route::get('/search', [DonationController::class, 'search'])->name('donation.search');
        Route::get('/detail/{id}', [DonationController::class, 'detailbyID'])->name('donation.detail.id');
        Route::delete('/cancel/{id}', [DonationController::class, 'destroy'])->name('donation.cancel');
        Route::get('/transfer', [DonationController::class, 'getTransferDonation'])->name('donation.list.transfer');
        Route::get('/chart', [DonationController::class, 'chart'])->name('donation.chart');


        Route::prefix('mutations')->group(function() {
            Route::get('/', [MutationController::class, 'index'])->name('mutation.list');
            Route::get('/detail/{id}', [MutationController::class, 'detailbyID'])->name('mutation.detail');
            Route::post('/store', [MutationController::class, 'store'])->name('mutation.store');
            Route::patch('/approve/{id}', [MutationController::class, 'approve'])->name('mutation.approve');
            Route::delete('/cancel/{id}', [MutationController::class, 'destroy'])->name('mutation.cancel');

        });
    });

});
