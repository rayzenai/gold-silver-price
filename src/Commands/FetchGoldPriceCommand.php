<?php

namespace RayzenAI\GoldSilverPrice\Commands;

use Illuminate\Console\Command;
use RayzenAI\GoldSilverPrice\Services\GoldPriceService;

class FetchGoldPriceCommand extends Command
{
    protected $signature = 'gold-silver-price:fetch';

    protected $description = 'Fetch and store the latest gold and silver prices';

    public function handle(GoldPriceService $goldPriceService): int
    {
        $this->info('Fetching gold and silver prices from '.config('gold-silver-price.source_url').'...');

        $goldPrice = $goldPriceService->fetchAndStore();

        if ($goldPrice) {
            $this->info('✓ Prices updated successfully for '.$goldPrice->date->format('Y-m-d'));
            $this->table(
                ['Metal', 'Per Tola', 'Per 10g'],
                [
                    ['Gold', 'Rs. '.number_format($goldPrice->gold_per_tola), 'Rs. '.number_format($goldPrice->gold_per_10g)],
                    ['Silver', 'Rs. '.number_format($goldPrice->silver_per_tola), 'Rs. '.number_format($goldPrice->silver_per_10g)],
                ]
            );

            return Command::SUCCESS;
        } else {
            $this->error('✗ Could not fetch the gold/silver prices.');
            $this->warn('This could mean:');
            $this->warn('  - Today\'s prices already exist in the database');
            $this->warn('  - The source hasn\'t updated prices yet');
            $this->warn('  - There was an error fetching from the source');

            return Command::FAILURE;
        }
    }
}
