<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Http;

class RefreshExchangeRates extends Command
{
    protected $signature = 'rates:refresh {base=USD}';
    protected $description = 'Fetch and store latest exchange rates from exchangerate.host';

    public function handle()
    {
        $base = strtoupper($this->argument('base'));
        $apiKey = env('EXCHANGE_RATE_API_KEY');

        $this->info("Fetching rates for {$base}...");

        $response = Http::get("https://api.exchangerate.host/live", [
            'access_key' => $apiKey,
            'source' => $base,
        ]);

        $data = $response->json();

        if (!($data['success'] ?? false)) {
            $this->error('Failed to fetch exchange rates: ' . json_encode($data['error'] ?? []));
            return Command::FAILURE;
        }

        $quotes = $data['quotes'] ?? [];

        $this->info('Got ' . count($quotes) . ' rates.');

        foreach ($quotes as $pair => $rate) {
            // Example: USDAUD → AUD
            $target = substr($pair, 3);

            ExchangeRate::updateOrCreate(
                ['base_currency' => $base, 'target_currency' => $target],
                [
                    'rate' => $rate,
                    'fetched_at' => now(),
                ]
            );
        }

        $this->info('Exchange rates refreshed successfully.');
        return Command::SUCCESS;
    }
}