<?php

namespace App\Jobs;

use App\Models\ExchangeRate;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RefreshExchangeRates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $base;

    public function __construct(string $base = 'USD')
    {
        $this->base = strtoupper($base);
    }

    public function handle(): void
    {
        $apiKey = env('EXCHANGE_RATE_API_KEY');

        $response = Http::get("https://api.exchangerate.host/live", [
            'access_key' => $apiKey,
            'source' => $this->base,
        ]);

        $data = $response->json();

        if (!($data['success'] ?? false)) {
            logger()->error('[ExchangeRate] API failed', ['response' => $data]);
            return;
        }

        foreach ($data['quotes'] ?? [] as $pair => $rate) {
            $target = substr($pair, 3);

            ExchangeRate::updateOrCreate(
                ['base_currency' => $this->base, 'target_currency' => $target],
                ['rate' => $rate, 'fetched_at' => now()]
            );
        }

        logger()->info('[ExchangeRate] Updated successfully', ['base' => $this->base]);
    }
}
