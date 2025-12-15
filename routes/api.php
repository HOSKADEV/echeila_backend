<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TripController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\WilayaController;
use App\Http\Controllers\Api\PassengerController;
use App\Http\Controllers\Api\SeatPriceController;
use App\Http\Controllers\Api\TripCargoController;
use App\Http\Controllers\Api\TripClientController;
use App\Http\Controllers\Api\TripReviewController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\LostAndFoundController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\VehicleModelController;
use App\Http\Controllers\Api\DocumentationController;
use App\Http\Controllers\Api\SettingController;

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

Route::prefix('v1')->group(function () {

    Route::middleware('auth:sanctum')->get('/user', [AuthController::class, 'user']);

    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/check-phone', [AuthController::class, 'checkPhone']);
        Route::post('/forget-password', [AuthController::class, 'forgetPassword']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/logout', [AuthController::class, 'logout']);
            Route::post('/reset-password', [AuthController::class, 'resetPassword']);
            Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);
        });
    });

    // Wilaya routes
    Route::prefix('wilayas')->group(function () {
        Route::get('/', [WilayaController::class, 'index']);
    });

    // Seat Price routes
    Route::prefix('seat-prices')->group(function () {
        Route::get('/', [SeatPriceController::class, 'index']);
        Route::get('/{startingWilayaId}/{arrivalWilayaId}', [SeatPriceController::class, 'show']);
    });

    // Brand routes
    Route::prefix('brands')->group(function () {
        Route::get('/', [BrandController::class, 'index']);
    });

    // Vehicle Model routes
    Route::prefix('models')->group(function () {
        Route::get('/', [VehicleModelController::class, 'index']);
    });

    // Color routes
    Route::prefix('colors')->group(function () {
        Route::get('/', [ColorController::class, 'index']);
    });

    Route::prefix('documentations')->group(function () {
        Route::get('/{key}', [DocumentationController::class, 'index']);
    });

    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingController::class, 'index']);
    });

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        // Passenger routes
        Route::prefix('passengers')->group(function () {
            Route::patch('/', [PassengerController::class, 'update']);
        });

        // Driver routes
        Route::prefix('drivers')->group(function () {
            Route::post('/', [DriverController::class, 'store']);
            Route::patch('/', [DriverController::class, 'update']);
        });

        Route::prefix('trips')->group(function () {
            Route::post('/{type}', [TripController::class, 'store']);
            Route::patch('/{id}', [TripController::class, 'update']);
            Route::delete('/{id}', [TripController::class, 'destroy']);
            //Route::post('/available', [TripController::class, 'available']);
            Route::get('/{id}/reviews', [TripReviewController::class, 'tripReviews']);
        });

        Route::post('/available-trips', [TripController::class, 'available']);

        // TripClient routes
        Route::prefix('trip-clients')->group(function () {
            Route::get('/', [TripClientController::class, 'index']);
            Route::post('/', [TripClientController::class, 'store']);
            Route::delete('/{tripClient}', [TripClientController::class, 'destroy']);
        });

        // TripCargo routes
        Route::prefix('trip-cargos')->group(function () {
            Route::get('/', [TripCargoController::class, 'index']);
            Route::post('/', [TripCargoController::class, 'store']);
            Route::delete('/{tripCargo}', [TripCargoController::class, 'destroy']);
        });

        Route::prefix('passenger')->group(function () {
            Route::get('/trips/{type}', [TripController::class, 'index']);
        });

        Route::prefix('driver')->group(function () {
            Route::get('/trips/{type}', [TripController::class, 'index']);
            Route::post('/income', [DriverController::class, 'income']);
            Route::get('/stats', [DriverController::class, 'stats']);
            Route::get('/reviews', [TripReviewController::class, 'driverReviews']);
        });

        Route::prefix('passenger')->group(function () {
            Route::get('/reviews', [TripReviewController::class, 'passengerReviews']);
        });

        // Lost and Found routes
        Route::prefix('lost-and-found')->group(function () {
            Route::get('/', [LostAndFoundController::class, 'index']);
            Route::post('/', [LostAndFoundController::class, 'store']);
        });

        // Trip Review routes
        Route::prefix('trip-reviews')->group(function () {
            Route::post('/', [TripReviewController::class, 'store']);
        });

        Route::prefix('transactions')->group(function () {
            Route::get('/', [TransactionController::class, 'index']);
        });

        // Notification routes
        Route::prefix('notifications')->group(function () {
            Route::get('/', [NotificationController::class, 'index']);
            Route::get('/unread', [NotificationController::class, 'unread']);
            Route::post('/read/{id}', [NotificationController::class, 'markAsRead']);
        });

        Route::prefix('subscriptions')->group(function () {
            Route::post('/', [SubscriptionController::class, 'store']);
        });

    });

});
