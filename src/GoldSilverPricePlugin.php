<?php

namespace RayzenAI\GoldSilverPrice;

use Filament\Contracts\Plugin;
use Filament\Panel;
use RayzenAI\GoldSilverPrice\Filament\Resources\GoldPrices\GoldPriceResource;

class GoldSilverPricePlugin implements Plugin
{
    public function getId(): string
    {
        return 'gold-silver-price';
    }

    public function register(Panel $panel): void
    {
        if (config('gold-silver-price.filament.enabled', true)) {
            $panel->resources([
                GoldPriceResource::class,
            ]);
        }

        // Register widgets if configured
        if (config('gold-silver-price.filament.widgets', true)) {
            // Add widgets here when created
        }
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return new static;
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }
}
