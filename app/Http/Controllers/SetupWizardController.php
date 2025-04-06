<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use App\Models\SetupLog;


class SetupWizardController extends Controller
{
    public function index()
    {
        if (Setting::get('setup_complete') === 'true') {
            return redirect()->route('login');
        }

        return Inertia::render('Setup/Wizard');
    }

    public function step(Request $request)
    {
        $request->validate([
            'step' => 'required|integer',
        ]);

        session(['setup.wizard.step' => $request->step]);
        session([
            'setup.wizard.data' => array_merge(
                session('setup.wizard.data', []),
                $request->only(['app_name', 'base_currency', 'api_key', 'timezone', 'logo', 'user_name', 'email', 'password'])
            )
        ]);

        return response()->noContent();
    }

    public function complete(Request $request)
    {
        if (Setting::get('setup_complete') === 'true') {
            return redirect()->route('login');
        }
        $data = $request->all();

        User::create([
            'name' => $data['user_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_superuser' => true,
        ]);

        Setting::set('app_name', $data['app_name']);
        Setting::set('base_currency', $data['base_currency']);
        Setting::set('exchange_api_key', $data['api_key']);
        Setting::set('timezone', $data['timezone']);
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            Setting::set('board_logo', $path);
        } else {
            Setting::set('board_logo', null);
        }
        Setting::set('setup_complete', 'true');

        SetupLog::create([
            'event' => 'setup_completed',
            'payload' => [
                'email' => $data['email'],
                'base_currency' => $data['base_currency'],
                'app_name' => $data['app_name'],
                'logo_uploaded' => $request->hasFile('logo'),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ],
        ]);

        session()->forget('setup.wizard');

        return redirect()->route('login')->with('success', 'Setup complete. Please log in.');
    }
}
