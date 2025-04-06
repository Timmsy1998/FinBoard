<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\ExchangeRate;
use App\Models\Setting;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
| These routes are available to logged-in users only. Everything here is 
| protected by 'auth' and 'verified' middleware.
|
| This is the core of the FinBoard dashboard — contributors can easily 
| expand this section to include new pages like reports, budgeting, etc.
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Main dashboard landing page after login
    Route::get('/', function () {
        $baseCurrency = Setting::get('base_currency', 'USD');

        return inertia('Home', [
            'exchangeRates' => ExchangeRate::where('base_currency', $baseCurrency)
                ->orderBy('target_currency')
                ->get()
        ]);
    })->name('dashboard');

    // User's portfolio overview
    Route::get('/portfolio', function () {
        return inertia('Portfolio');
    })->name('portfolio');

    // List of financial transactions
    Route::get('/transactions', function () {
        return inertia('Transactions');
    })->name('transactions');

    // Charts, KPIs, and financial insights
    Route::get('/analytics', function () {
        return inertia('Analytics');
    })->name('analytics');
});

Route::middleware(['auth', 'verified', 'role:admin,superuser'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/admin/settings', [SettingController::class, 'index'])->name('admin.settings');
    Route::post('/admin/settings', [SettingController::class, 'update'])->name('admin.settings.update');
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
| Any routes placed here should be accessible without login.
|
| In the future, you might want to add a public landing page, marketing site,
| legal pages (Terms, Privacy), or referral system.
|
| By default, this section only includes the Breeze authentication routes.
*/
require __DIR__ . '/auth.php';

Route::fallback(function () {
    return inertia('Errors/NotFound')->toResponse(request())->setStatusCode(404);
});