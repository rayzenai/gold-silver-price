<?php

namespace RayzenAI\GoldSilverPrice;

use RayzenAI\GoldSilverPrice\Commands\FetchGoldPriceCommand;
use RayzenAI\GoldSilverPrice\Services\GoldPriceService;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class GoldSilverPriceServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('gold-silver-price')
            ->hasConfigFile()
            ->hasMigration('2025_01_01_000001_create_gold_prices_table')
            ->hasCommands([
                FetchGoldPriceCommand::class,
            ]);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton('gold-silver-price', function ($app) {
            return new GoldPriceService();
        });
    }

    public function packageBooted(): void
    {
        //
    }
}
