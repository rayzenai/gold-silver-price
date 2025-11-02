<?php

use Illuminate\Support\Facades\Http;
use RayzenAI\GoldSilverPrice\Models\GoldPrice;
use RayzenAI\GoldSilverPrice\Services\GoldPriceService;

beforeEach(function () {
    $this->service = new GoldPriceService;
});

test('does not save when all four prices are identical to previous price', function () {
    // Create a price from 2 days ago
    GoldPrice::factory()->create([
        'date' => today()->subDays(2),
        'gold_per_tola' => 200000,
        'gold_per_10g' => 171500,
        'silver_per_tola' => 2500,
        'silver_per_10g' => 2143,
    ]);

    // Mock HTTP response with identical prices
    Http::fake([
        'fenegosida.org' => Http::response(mockHtmlWithPrices(200000, 171500, 2500, 2143)),
    ]);

    $result = $this->service->fetchAndStore();

    // Should return null (not save)
    expect($result)->toBeNull();

    // Should not have created today's price
    expect(GoldPrice::whereDate('date', today())->exists())->toBeFalse();
});

test('saves when at least one price is different from previous price', function () {
    // Create a price from 2 days ago
    GoldPrice::factory()->create([
        'date' => today()->subDays(2),
        'gold_per_tola' => 200000,
        'gold_per_10g' => 171500,
        'silver_per_tola' => 2500,
        'silver_per_10g' => 2143,
    ]);

    // Mock HTTP response with one different price
    Http::fake([
        'fenegosida.org' => Http::response(mockHtmlWithPrices(210000, 171500, 2500, 2143)),
    ]);

    $result = $this->service->fetchAndStore();

    // Should save the new price
    expect($result)->toBeInstanceOf(GoldPrice::class);
    expect($result->gold_per_tola)->toBe(210000);

    // Should have created today's price
    expect(GoldPrice::whereDate('date', today())->exists())->toBeTrue();
});

test('compares with latest available price when there are gaps in data', function () {
    // Create a price from 5 days ago (simulating weekend gap)
    GoldPrice::factory()->create([
        'date' => today()->subDays(5),
        'gold_per_tola' => 200000,
        'gold_per_10g' => 171500,
        'silver_per_tola' => 2500,
        'silver_per_10g' => 2143,
    ]);

    // Mock HTTP response with identical prices
    Http::fake([
        'fenegosida.org' => Http::response(mockHtmlWithPrices(200000, 171500, 2500, 2143)),
    ]);

    $result = $this->service->fetchAndStore();

    // Should return null (prices haven't changed from 5 days ago)
    expect($result)->toBeNull();

    // Should not have created today's price
    expect(GoldPrice::whereDate('date', today())->exists())->toBeFalse();
});

test('saves when there is no previous price to compare', function () {
    // No existing prices in database

    // Mock HTTP response
    Http::fake([
        'fenegosida.org' => Http::response(mockHtmlWithPrices(200000, 171500, 2500, 2143)),
    ]);

    $result = $this->service->fetchAndStore();

    // Should save the price (first time)
    expect($result)->toBeInstanceOf(GoldPrice::class);
    expect(GoldPrice::whereDate('date', today())->exists())->toBeTrue();
});

test('does not fetch if today price already exists', function () {
    // Create today's price
    $existingPrice = GoldPrice::factory()->create([
        'date' => today(),
        'gold_per_tola' => 200000,
    ]);

    Http::fake(); // Fake should not be called

    $result = $this->service->fetchAndStore();

    // Should return existing price without making HTTP request
    expect($result->id)->toBe($existingPrice->id);

    Http::assertNothingSent();
});

test('saves when only gold_per_tola is different', function () {
    GoldPrice::factory()->create([
        'date' => today()->subDay(),
        'gold_per_tola' => 200000,
        'gold_per_10g' => 171500,
        'silver_per_tola' => 2500,
        'silver_per_10g' => 2143,
    ]);

    Http::fake([
        'fenegosida.org' => Http::response(mockHtmlWithPrices(210000, 171500, 2500, 2143)),
    ]);

    $result = $this->service->fetchAndStore();

    expect($result)->toBeInstanceOf(GoldPrice::class);
    expect(GoldPrice::whereDate('date', today())->exists())->toBeTrue();
});

test('saves when only silver_per_10g is different', function () {
    GoldPrice::factory()->create([
        'date' => today()->subDay(),
        'gold_per_tola' => 200000,
        'gold_per_10g' => 171500,
        'silver_per_tola' => 2500,
        'silver_per_10g' => 2143,
    ]);

    Http::fake([
        'fenegosida.org' => Http::response(mockHtmlWithPrices(200000, 171500, 2500, 2200)),
    ]);

    $result = $this->service->fetchAndStore();

    expect($result)->toBeInstanceOf(GoldPrice::class);
    expect(GoldPrice::whereDate('date', today())->exists())->toBeTrue();
});

// Helper function to generate mock HTML
function mockHtmlWithPrices(int $goldTola, int $gold10g, int $silverTola, int $silver10g): string
{
    return <<<HTML
    <html>
    <body>
        <div>FINE GOLD (9999)<br><span>per 1 tola<br><br>रु</span> <b>{$goldTola}</b></div>
        <div>FINE GOLD (9999)<br><span>per 10 grm<br><br>Nrs</span> <b>{$gold10g}</b>/-</div>
        <div>SILVER<br><span>per 1 tola<br><br>रु</span> <b>{$silverTola}</b></div>
        <div>SILVER<br><span>per 10 grm<br><br>Nrs</span> <b>{$silver10g}</b>/-</div>
    </body>
    </html>
    HTML;
}
