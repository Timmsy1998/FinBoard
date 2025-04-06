<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Inertia\Inertia;

class SettingController extends Controller
{

    public function index()
    {
        return Inertia::render('Admin/Settings', [
            'settings' => [
                'app_name' => Setting::get('app_name', 'FinBoard'),
                'exchange_api_key' => Setting::get('exchange_api_key', ''),
                'base_currency' => Setting::get('base_currency', 'USD'),
                'timezone' => Setting::get('timezone', 'UTC'),
                'demo_mode' => Setting::get('demo_mode', '0'),
            ],
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'exchange_api_key' => 'nullable|string|max:255',
            'base_currency' => 'required|string|max:3',
            'timezone' => 'required|string|max:100',
            'demo_mode' => 'nullable|boolean',
        ]);

        Setting::set('app_name', $validated['app_name']);
        Setting::set('exchange_api_key', $validated['exchange_api_key']);
        Setting::set('base_currency', $validated['base_currency']);
        Setting::set('timezone', $validated['timezone']);

        Setting::set('exchange_rates_enabled', filled($validated['exchange_api_key']) ? '1' : '0');
        Setting::set('demo_mode', $request->boolean('demo_mode') ? '1' : '0');

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
