<?php

namespace App\Jobs;

use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchCurrencyTrends implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $base;
    public array $currencies;

    public function __construct(string $base = 'USD', array $currencies = ['EUR', 'GBP', 'JPY'])
    {
        $this->base = strtoupper($base);
        $this->currencies = $currencies;
    }

    public function handle(): void
    {
        // 🔒 Check if exchange rate updates are enabled
        if (Setting::get('exchange_rates_enabled') !== '1') {
            Log::info('[ExchangeRate] Skipped: exchange rates disabled in settings.');
            return;
        }

        $apiKey = Setting::get('exchange_api_key');
        if (!$apiKey) {
            Log::warning('[HistoricalRates] Skipped: No API key found.');
            return;
        }

        $dates = collect(range(0, 4))->map(fn($i) => now()->subDays($i)->format('Y-m-d'))->reverse();
        $trends = [];

        foreach ($this->currencies as $currency) {
            $trends[$currency] = [];
        }

        foreach ($dates as $date) {
            $response = Http::get("https://api.exchangerate.host/historical", [
                'access_key' => $apiKey,
                'date' => $date,
                'source' => $this->base,
                'currencies' => implode(',', $this->currencies),
                'format' => 1,
            ]);

            $data = $response->json();

            if (!($data['success'] ?? false)) {
                Log::error('[HistoricalRates] API failed', ['date' => $date, 'response' => $data]);
                continue;
            }

            foreach ($data['quotes'] ?? [] as $pair => $rate) {
                $target = substr($pair, 3);
                $trends[$target][] = $rate;
            }
        }

        // ⏱ Cache the trend data
        Cache::put("historical_trends_{$this->base}", $trends, now()->addHours(1));
        Log::info('[HistoricalRates] Cached trends', ['base' => $this->base]);
    }
}