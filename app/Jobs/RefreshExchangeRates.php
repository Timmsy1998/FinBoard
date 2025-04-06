<?php

namespace App\Jobs;

use App\Models\ExchangeRate;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RefreshExchangeRates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $base;

    public function __construct(string $base = 'USD')
    {
        $this->base = strtoupper($base);
    }

    public function handle(): void
    {
        // 🔒 Check if exchange rate updates are enabled
        if (Setting::get('exchange_rates_enabled') !== '1') {
            Log::info('[ExchangeRate] Skipped: exchange rates disabled in settings.');
            return;
        }

        // ⏳ Skip if updated within last 30 minutes
        $lastUpdated = Setting::get('exchange_rates_last_updated');
        if ($lastUpdated && now()->diffInMinutes($lastUpdated) < 30) {
            Log::info('[ExchangeRate] Skipped: updated less than 30 minutes ago.');
            return;
        }

        // 🔑 Fetch the API key from settings
        $apiKey = Setting::get('exchange_api_key');
        if (!$apiKey) {
            Log::warning('[ExchangeRate] Skipped: No API key found in settings.');
            return;
        }

        // 🌐 Make the API call
        $response = Http::get('https://api.exchangerate.host/live', [
            'access_key' => $apiKey,
            'source' => $this->base,
        ]);

        $data = $response->json();

        // ❌ If unsuccessful, log and bail
        if (!($data['success'] ?? false)) {
            Log::error('[ExchangeRate] API failed', ['response' => $data]);
            return;
        }

        // ✅ Store each quote
        foreach ($data['quotes'] ?? [] as $pair => $rate) {
            $target = substr($pair, 3); // Example: USDGBP → GBP

            ExchangeRate::updateOrCreate(
                [
                    'base_currency' => $this->base,
                    'target_currency' => $target,
                ],
                [
                    'rate' => $rate,
                    'fetched_at' => now(),
                ]
            );
        }

        // 🕒 Store unified last-updated value
        Setting::set('exchange_rates_last_updated', now()->toDateTimeString());

        Log::info('[ExchangeRate] Rates refreshed successfully.', [
            'base' => $this->base,
            'fetched' => count($data['quotes'] ?? []),
        ]);
    }
}