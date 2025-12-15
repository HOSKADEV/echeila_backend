<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\Auth\ForgotPassword;
use App\Http\Controllers\front_pages\Landing;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\pages\MiscComingSoon;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\pages\MiscNotAuthorized;
use App\Http\Controllers\Dashboard\BrandController;
use App\Http\Controllers\Dashboard\ColorController;
use App\Http\Controllers\Dashboard\WilayaController;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\SeatPriceController;
use App\Http\Controllers\Dashboard\Admin\AdminController;
use App\Http\Controllers\Dashboard\Roles\RolesController;
use App\Http\Controllers\Dashboard\Users\UsersController;
use App\Http\Controllers\Dashboard\LostAndFoundController;
use App\Http\Controllers\Dashboard\TripController;
use App\Http\Controllers\Dashboard\Users\DriverController;
use App\Http\Controllers\Dashboard\VehicleModelController;
use App\Http\Controllers\Dashboard\Users\PassengerController;
use App\Http\Controllers\Dashboard\Settings\SettingController;
use App\Http\Controllers\Dashboard\Users\FederationController;
use App\Http\Controllers\Dashboard\Permissions\PermissionsController;
use App\Http\Controllers\Dashboard\Documentation\DocumentationController;
use App\Http\Controllers\Dashboard\Notifications\NotificationsController;
use App\Http\Controllers\Dashboard\AdminActionController;

// locale
Route::get('/{locale}', function ($locale) {
    session()->put('locale', $locale);

    return redirect()->back();
})->where('locale', 'en|fr|ar');

Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');
Route::get('/pages/misc-comingsoon', [MiscComingSoon::class, 'index'])->name('pages-misc-comingsoon');
Route::get('/pages/misc-not-authorized', [MiscNotAuthorized::class, 'index'])->name('unauthorized');

// Front Pages
Route::get('/', [Landing::class, 'index'])->name('front-pages-landing');
Route::get('/privacy-policy', [DocumentationController::class, 'privacyPolicy']);
Route::get('/about-us', [DocumentationController::class, 'aboutUs']);
Route::get('/delete-account', [DocumentationController::class, 'deleteAccount']);

// Auth Routes
Route::group(['prefix' => 'admin'], function () {

    Route::get('login', [LoginController::class, 'index'])->middleware('guest')->name('login');
    Route::post('login', [LoginController::class, 'action'])->middleware('guest')->name('login.action');
    Route::get('forgot-password', [ForgotPassword::class, 'index'])->middleware('guest')->name('reset-password');
    Route::get('logout', [LogoutController::class, 'action'])->middleware('auth')->name('logout');
    Route::get('register', [RegisterController::class, 'index'])->middleware('guest')->name('register');
    Route::post('register', [RegisterController::class, 'action'])->middleware('guest')->name('register.action');

    Route::group(['middleware' => ['auth']], function () {
        // navbar routes
        Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::post('profile', [ProfileController::class, 'store'])->name('profile.store');
        Route::post('profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

        Route::get('/dashboard', function () {
            return view('dashboard.index');
        })->name('dashboard');

        Route::get('/', function () {
            return redirect()->route('dashboard');
        })->name('home');

        Route::resource('roles', RolesController::class);

        Route::get('permissions', [PermissionsController::class, 'index'])->name('permissions.index');
        Route::post('permissions', [PermissionsController::class, 'update'])->name('permissions.update');

        Route::resource('settings', SettingController::class)->only('index', 'store');
        Route::resource('admin-actions', AdminActionController::class)->only('index');
        Route::resource('documentations', DocumentationController::class)->only('index', 'store');
        Route::resource('admins', AdminController::class)->except(['show']);
        Route::resource('wilayas', WilayaController::class)->except(['show']);
        Route::resource('seat-prices', SeatPriceController::class)->except(['show']);
        Route::resource('brands', BrandController::class)->except(['show']);
        Route::resource('vehicle-models', VehicleModelController::class)->except(['show']);
        Route::resource('colors', ColorController::class)->except(['show']);
        Route::resource('users', UsersController::class)->except(['show']);
        Route::post('users/update-status', [UsersController::class, 'updateStatus'])->name('users.status.update');
        Route::post('users/charge-wallet', [UsersController::class, 'chargeWallet'])->name('users.wallet.charge');
        Route::post('users/withdraw-sum', [UsersController::class, 'withdrawSum'])->name('users.wallet.withdraw');

        Route::prefix('passengers')->group(function () {
            Route::get('/', [PassengerController::class, 'index'])->name('passengers.index');
            Route::get('/{id}', [PassengerController::class, 'show'])->name('passengers.show');
        });

        Route::prefix('drivers')->group(function () {
            Route::get('/', [DriverController::class, 'index'])->name('drivers.index');
            Route::get('/{id}', [DriverController::class, 'show'])->name('drivers.show');
            Route::post('/update-status', [DriverController::class, 'updateStatus'])->name('drivers.status.update');
            Route::post('/purchase-subscription', [DriverController::class, 'purchaseSubscription'])->name('drivers.subscription.purchase');
            Route::post('/add-to-federation', [DriverController::class, 'addToFederation'])->name('drivers.federation.add');
            Route::post('/remove-from-federation', [DriverController::class, 'removeFromFederation'])->name('drivers.federation.remove');
        });

        Route::prefix('federations')->group(function () {
            Route::get('/', [FederationController::class, 'index'])->name('federations.index');
            Route::get('/create', [FederationController::class, 'create'])->name('federations.create');
            Route::get('/{id}', [FederationController::class, 'show'])->name('federations.show');
            Route::post('/', [FederationController::class, 'store'])->name('federations.store');
        });

        Route::prefix('trips')->group(function () {
            Route::get('/{type}', [\App\Http\Controllers\Dashboard\TripController::class, 'index'])->name('trips.index');
            Route::get('/show/{id}', [\App\Http\Controllers\Dashboard\TripController::class, 'show'])->name('trips.show');
        });

        Route::get('send-notification', [NotificationsController::class, 'index'])->name('send-notification');
        Route::post('send-notification', [NotificationsController::class, 'send'])->name('send-notification.send');

        Route::resource('documentations', DocumentationController::class)->only('index', 'store');

        Route::resource('lost-and-founds', LostAndFoundController::class);
        Route::post('lost-and-founds/update-status', [LostAndFoundController::class, 'updateStatus'])->name('lost-and-founds.status.update');

        Route::resource('admin-actions', AdminActionController::class)->only('index');
        
    });
});
