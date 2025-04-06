<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\RefreshExchangeRates;
use App\Models\ExchangeRate;
use App\Models\Setting;
use App\Jobs\FetchCurrencyTrends;
use Illuminate\Support\Facades\Cache;

class ExchangeRateController extends Controller
{
    public function fetch(Request $request)
    {
        $base = Setting::get('base_currency', 'USD');

        // 🔁 Trigger job synchronously (or use dispatch if async)
        dispatch_sync(new RefreshExchangeRates($base));

        $rates = ExchangeRate::where('base_currency', $base)
            ->orderBy('target_currency')
            ->get();

        return response()->json([
            'base_currency' => $base,
            'rates' => $rates,
        ]);
    }

    public function trends()
    {
        $base = Setting::get('base_currency', 'USD');
        $targets = ['EUR', 'GBP', 'JPY', 'INR', 'AUD', 'USD'];

        // Dispatch job synchronously to ensure data
        dispatch_sync(new FetchCurrencyTrends($base, $targets));

        $trends = Cache::get("historical_trends_{$base}", []);

        return response()->json([
            'base' => $base,
            'trends' => $trends,
        ]);
    }
}