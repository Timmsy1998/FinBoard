<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\Setting;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),

            'auth' => [
                'user' => $request->user() ?? null,
            ],

            'app' => [
                'name' => Setting::get('app_name', 'FinBoard'),
                'logo' => Setting::get('board_logo')
                    ? asset('storage/' . Setting::get('board_logo'))
                    : null,
                'exchangeRatesEnabled' => (bool) Setting::get('exchange_rates_enabled'),
                'demoMode' => (bool) Setting::get('demo_mode', false),
            ],


            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
            ],

            'settings' => [
                'baseCurrency' => Setting::get('base_currency', 'USD'),
                'exchangeEnabled' => (bool) Setting::get('enable_exchange', false),
            ],
        ];
    }
}
