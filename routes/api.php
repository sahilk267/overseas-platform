<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\TwoFactorController;

/* |-------------------------------------------------------------------------- | API Routes |-------------------------------------------------------------------------- | | Here is where you can register API routes for your application. These | routes are loaded by the RouteServiceProvider and all of them will | be assigned to the "api" middleware group. Make something great! | */

// Public routes (no authentication required)
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class , 'register']);
    Route::post('/login', [AuthController::class , 'login']);
    Route::post('/2fa/verify', [TwoFactorController::class , 'verify']);
});

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {

    // Auth routes
    Route::prefix('auth')->group(function () {
            Route::get('/me', [AuthController::class , 'me']);
            Route::post('/logout', [AuthController::class , 'logout']);
            Route::post('/logout-all', [AuthController::class , 'logoutAll']);
            Route::post('/refresh', [AuthController::class , 'refresh']);
        }
        );

        // Profile management routes
        Route::prefix('profiles')->group(function () {
            Route::get('/', [ProfileController::class , 'index']);
            Route::get('/current', [ProfileController::class , 'current']);
            Route::post('/switch', [ProfileController::class , 'switch']);
            Route::get('/{id}', [ProfileController::class , 'show']);
            Route::get('/{id}/permissions', [ProfileController::class , 'permissions']);
            Route::post('/check-permission', [ProfileController::class , 'checkPermission']);
        }
        );

        // Two-Factor Authentication routes
        Route::prefix('2fa')->group(function () {
            Route::get('/status', [TwoFactorController::class , 'status']);
            Route::post('/enable', [TwoFactorController::class , 'enable']);
            Route::post('/disable', [TwoFactorController::class , 'disable']);
            Route::post('/regenerate-codes', [TwoFactorController::class , 'regenerateRecoveryCodes']);
        }
        );

        // Profile activation middleware (Ensures a profile is selected)
        Route::middleware('profile.active')->group(function () {

            // Campaign routes
            Route::apiResource('campaigns', \App\Http\Controllers\Api\CampaignController::class);
            Route::put('campaigns/{campaign}/status', [\App\Http\Controllers\Api\CampaignController::class , 'updateStatus']);

            // Execution routes
            Route::post('executions/book', [\App\Http\Controllers\Api\ExecutionController::class , 'book']);
            Route::get('executions/{execution}', [\App\Http\Controllers\Api\ExecutionController::class , 'show']);
            Route::put('executions/{execution}/status', [\App\Http\Controllers\Api\ExecutionController::class , 'updateStatus']);

            // Payment routes
            Route::apiResource('payments', \App\Http\Controllers\Api\PaymentController::class)->only(['index', 'store', 'show']);
            Route::post('payments/{payment}/refund', [\App\Http\Controllers\Api\PaymentController::class , 'refund']);

            // Invoice routes
            Route::apiResource('invoices', \App\Http\Controllers\Api\InvoiceController::class)->only(['index', 'store', 'show']);

            // Promotion routes
            Route::apiResource('promotions', \App\Http\Controllers\Api\PromotionController::class)->only(['index', 'store']);
            Route::post('promotions/{promotion}/assign', [\App\Http\Controllers\Api\PromotionController::class , 'assign']);

            // Appointment routes
            Route::apiResource('appointments', \App\Http\Controllers\Api\AppointmentController::class)->only(['index', 'store']);
            Route::put('appointments/{appointment}/status', [\App\Http\Controllers\Api\AppointmentController::class , 'updateStatus']);

            // Dispute routes
            Route::apiResource('disputes', \App\Http\Controllers\Api\DisputeController::class)->only(['index', 'store', 'show']);
            Route::post('disputes/{dispute}/messages', [\App\Http\Controllers\Api\DisputeController::class , 'addMessage']);
        }
        );    });
