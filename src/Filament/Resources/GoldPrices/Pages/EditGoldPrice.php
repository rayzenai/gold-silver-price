<?php

namespace RayzenAI\GoldSilverPrice\Filament\Resources\GoldPrices\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use RayzenAI\GoldSilverPrice\Filament\Resources\GoldPrices\GoldPriceResource;

class EditGoldPrice extends EditRecord
{
    protected static string $resource = GoldPriceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
