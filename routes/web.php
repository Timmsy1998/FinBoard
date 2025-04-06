<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
        return inertia('Home');
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
