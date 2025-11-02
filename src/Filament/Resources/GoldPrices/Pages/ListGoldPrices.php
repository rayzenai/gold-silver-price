<?php

namespace RayzenAI\GoldSilverPrice\Filament\Resources\GoldPrices\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use RayzenAI\GoldSilverPrice\Filament\Resources\GoldPrices\GoldPriceResource;

class ListGoldPrices extends ListRecords
{
    protected static string $resource = GoldPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
