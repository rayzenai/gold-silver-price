# Gold & Silver Price Package

A Laravel package for fetching and managing gold and silver prices from fenegosida.org with Filament integration.

## Features

- Automatic price fetching from fenegosida.org
- Database storage of historical prices
- Filament admin panel integration
- Queue support for background fetching
- Artisan command for manual fetching
- Configurable source URL and settings
- Full test coverage with Pest PHP

## Installation

You can install the package via Composer:

```bash
composer require rayzenai/gold-silver-price
```

Publish the migration file (optional):

```bash
php artisan vendor:publish --tag="gold-silver-price-migrations"
```

Run the migrations:

```bash
php artisan migrate
```

Publish the config file (optional):

```bash
php artisan vendor:publish --tag="gold-silver-price-config"
```

## Configuration

The package comes with a configuration file `config/gold-silver-price.php` where you can customize:

- Source URL for fetching prices
- Filament navigation settings
- Database table name
- HTTP client settings (timeout, retries, etc.)

## Usage

### Fetching Prices

Manually fetch prices using the Artisan command:

```bash
php artisan gold-silver-price:fetch
```

Or dispatch the job:

```php
use RayzenAI\GoldSilverPrice\Jobs\FetchGoldPriceJob;

FetchGoldPriceJob::dispatch();
```

### Scheduling Automatic Fetching

To automatically fetch prices every five minutes between 10:00 and 13:00, add the following to your `routes/console.php` file:

```php
use Illuminate\Support\Facades\Schedule;
use RayzenAI\GoldSilverPrice\Jobs\FetchGoldPriceJob;

Schedule::job(FetchGoldPriceJob::class)
    ->everyFiveMinutes()
    ->between('10:00', '13:00');
```

Make sure your Laravel scheduler is running via cron:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### Using the Service

```php
use RayzenAI\GoldSilverPrice\Services\GoldPriceService;

$service = app(GoldPriceService::class);
$goldPrice = $service->fetchAndStore();

// Get latest prices
$latest = $service->getLatest();
```

### Filament Integration

Register the plugin in your Filament panel provider:

```php
use RayzenAI\GoldSilverPrice\GoldSilverPricePlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            GoldSilverPricePlugin::make(),
        ]);
}
```

## Testing

The package includes comprehensive tests using Pest PHP. Run tests with:

```bash
cd /path/to/gold-silver-price
./vendor/bin/pest
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
