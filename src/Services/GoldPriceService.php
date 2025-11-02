<?php

namespace RayzenAI\GoldSilverPrice\Services;

use Illuminate\Support\Facades\Http;
use RayzenAI\GoldSilverPrice\Models\GoldPrice;

class GoldPriceService
{
    protected string $url;

    public function __construct()
    {
        $this->url = config('gold-silver-price.source_url', 'https://fenegosida.org');
    }

    /**
     * Fetch and store gold/silver prices
     */
    public function fetchAndStore(): ?GoldPrice
    {
        // Check if we already have today's price
        $existingPrice = GoldPrice::whereDate('date', today())->first();
        if ($existingPrice) {
            return $existingPrice;
        }

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.9',
            ])
                ->timeout(config('gold-silver-price.fetching.timeout', 90))
                ->connectTimeout(config('gold-silver-price.fetching.connect_timeout', 60))
                ->retry(
                    config('gold-silver-price.fetching.retry_times', 1),
                    config('gold-silver-price.fetching.retry_delay', 5000)
                )
                ->withOptions(['verify' => config('gold-silver-price.fetching.verify_ssl', false)])
                ->get($this->url);

            if (! $response->successful()) {
                return null;
            }

            $html = $response->body();
        } catch (\Exception $e) {
            return null;
        }

        $prices = $this->parsePrices($html);

        if (! $prices) {
            return null;
        }

        // Get latest price before today for comparison
        $previousPrice = GoldPrice::whereDate('date', '<', today())
            ->orderBy('date', 'desc')
            ->first();

        // If previous price exists and all four values are identical, consider it not updated
        if ($previousPrice &&
            (int) $previousPrice->gold_per_tola === $prices['gold_per_tola'] &&
            (int) $previousPrice->gold_per_10g === $prices['gold_per_10g'] &&
            (int) $previousPrice->silver_per_tola === $prices['silver_per_tola'] &&
            (int) $previousPrice->silver_per_10g === $prices['silver_per_10g']) {
            return null; // Source hasn't updated yet
        }

        // Update or create today's price record
        $goldPrice = GoldPrice::updateOrCreate(
            ['date' => today()],
            $prices
        );

        return $goldPrice;
    }

    /**
     * Parse prices from HTML
     */
    protected function parsePrices(string $html): ?array
    {
        $prices = [];

        // Gold per tola (Fine Gold 9999)
        $goldTolaPattern = '/FINE GOLD \(9999\)<br><span>per 1 tola<br><br>रु<\/span> <b>(\d+)<\/b>/';
        if (preg_match($goldTolaPattern, $html, $matches)) {
            $prices['gold_per_tola'] = (int) $matches[1];
        }

        // Gold per 10 grams (has /- at the end)
        $gold10gPattern = '/FINE GOLD \(9999\)<br><span>per 10 grm<br><br>Nrs<\/span> <b>(\d+)<\/b>\/-/';
        if (preg_match($gold10gPattern, $html, $matches)) {
            $prices['gold_per_10g'] = (int) $matches[1];
        }

        // Silver per tola
        $silverTolaPattern = '/SILVER<br><span>per 1 tola<br><br>रु<\/span> <b>(\d+)<\/b>/';
        if (preg_match($silverTolaPattern, $html, $matches)) {
            $prices['silver_per_tola'] = (int) $matches[1];
        }

        // Silver per 10 grams (has decimal and /-)
        $silver10gPattern = '/SILVER<br><span>per 10 grm<br><br>Nrs<\/span> <b>([\d.]+)<\/b>\/-/';
        if (preg_match($silver10gPattern, $html, $matches)) {
            $prices['silver_per_10g'] = (int) round((float) $matches[1]);
        }

        // Ensure all prices were found
        if (count($prices) !== 4) {
            return null;
        }

        return $prices;
    }

    /**
     * Get the latest stored prices
     */
    public function getLatest(): ?GoldPrice
    {
        return GoldPrice::latest();
    }
}
