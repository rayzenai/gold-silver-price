# Gold & Silver Price Package

A Laravel package for fetching and managing gold and silver prices from fenegosida.org with Filament integration.

## Features

- Automatic price fetching from fenegosida.org
- Database storage of historical prices
- Filament admin panel integration
- Queue support for background fetching
- Artisan command for manual fetching
- Configurable source URL and settings

## Installation

You can install the package via Composer:

```bash
composer require rayzenai/gold-silver-price
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

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
