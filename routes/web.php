<?php

use Illuminate\Support\Facades\Route;

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- | | Here is where you can register web routes for your application. These | routes are loaded by the RouteServiceProvider and all of them will | be assigned to the "web" middleware group. Make something great! | */

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WebAuthController;
use App\Http\Controllers\WebProfileController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [WebAuthController::class, 'login']);
Route::get('/register', [WebAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [WebAuthController::class, 'register']);
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

Route::get('/verify-email', [WebAuthController::class, 'showVerifyForm'])->name('verification.notice');
Route::post('/verify-email', [WebAuthController::class, 'verifyCode'])->name('verification.verify');
Route::post('/verify-email/resend', [WebAuthController::class, 'resendCode'])->name('verification.resend');

// Password Reset Routes
Route::get('/forgot-password', [WebAuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [WebAuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [WebAuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [WebAuthController::class, 'resetPassword'])->name('password.update');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profiles', [WebProfileController::class, 'index'])->name('profiles.index');
    Route::get('/profiles/create', [WebProfileController::class, 'create'])->name('profiles.create');
    Route::post('/profiles', [WebProfileController::class, 'store'])->name('profiles.store');
    Route::get('/profiles/{profile}/edit', [WebProfileController::class, 'edit'])->name('profiles.edit');
    Route::put('/profiles/{profile}', [WebProfileController::class, 'update'])->name('profiles.update');
    Route::post('/profiles/switch', [WebProfileController::class, 'switchProfile'])->name('profiles.switch');

    Route::resource('campaigns', \App\Http\Controllers\WebAdCampaignController::class);
    Route::resource('events', \App\Http\Controllers\WebEventController::class);
    Route::resource('inventory', \App\Http\Controllers\WebAdInventoryController::class);
    Route::resource('disputes', \App\Http\Controllers\WebDisputeController::class);

    // Agency Lead Management
    Route::get('/agency/leads', [\App\Http\Controllers\AgencyLeadController::class, 'index'])->name('agency.leads.index');
    Route::post('/agency/leads/{notification}/accept', [\App\Http\Controllers\AgencyLeadController::class, 'accept'])->name('agency.leads.accept');
    Route::post('/agency/leads/{notification}/pass', [\App\Http\Controllers\AgencyLeadController::class, 'pass'])->name('agency.leads.pass');

    // Admin Specific Routes
    Route::group(
        ['prefix' => 'admin', 'as' => 'admin.'],
        function () {
            Route::get('/campaigns', [\App\Http\Controllers\Admin\AdminCampaignController::class, 'index'])->name('campaigns.index');
            Route::post('/campaigns/{campaign}/approve', [\App\Http\Controllers\Admin\AdminCampaignController::class, 'approve'])->name('campaigns.approve');
            Route::post('/campaigns/{campaign}/reject', [\App\Http\Controllers\Admin\AdminCampaignController::class, 'reject'])->name('campaigns.reject');
            Route::post('/campaigns/{campaign}/allocate', [\App\Http\Controllers\Admin\AdminCampaignController::class, 'allocate'])->name('campaigns.allocate');

            // Cleanup Routes
            Route::get('/cleanup', [\App\Http\Controllers\Admin\AdminCleanupController::class, 'index'])->name('cleanup.index');
            Route::post('/cleanup', [\App\Http\Controllers\Admin\AdminCleanupController::class, 'cleanup'])->name('cleanup.perform');
        }
    );

    // Messaging Routes
    Route::get('/messages', [\App\Http\Controllers\WebMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{profile}', [\App\Http\Controllers\WebMessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [\App\Http\Controllers\WebMessageController::class, 'store'])->name('messages.store');

    // Temporary Cache Clear
    Route::get('/clear-cache', [\App\Http\Controllers\WebCacheController::class, 'clear']);
});
