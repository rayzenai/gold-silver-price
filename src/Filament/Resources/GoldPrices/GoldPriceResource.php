<?php

namespace RayzenAI\GoldSilverPrice\Filament\Resources\GoldPrices;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use RayzenAI\GoldSilverPrice\Filament\Resources\GoldPrices\Pages\CreateGoldPrice;
use RayzenAI\GoldSilverPrice\Filament\Resources\GoldPrices\Pages\EditGoldPrice;
use RayzenAI\GoldSilverPrice\Filament\Resources\GoldPrices\Pages\ListGoldPrices;
use RayzenAI\GoldSilverPrice\Filament\Resources\GoldPrices\Schemas\GoldPriceForm;
use RayzenAI\GoldSilverPrice\Filament\Resources\GoldPrices\Tables\GoldPricesTable;
use RayzenAI\GoldSilverPrice\Models\GoldPrice;
use UnitEnum;

class GoldPriceResource extends Resource
{
    protected static ?string $model = GoldPrice::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    public static function getNavigationLabel(): string
    {
        return config('gold-silver-price.filament.navigation_label', 'Gold & Silver Prices');
    }

    public static function getNavigationGroup(): ?string
    {
        return config('gold-silver-price.filament.navigation_group', 'Content');
    }

    public static function getNavigationSort(): ?int
    {
        return config('gold-silver-price.filament.navigation_sort');
    }

    protected static ?string $modelLabel = 'Gold Price';

    protected static ?string $pluralModelLabel = 'Gold Prices';

    public static function form(Schema $schema): Schema
    {
        return GoldPriceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GoldPricesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGoldPrices::route('/'),
            'create' => CreateGoldPrice::route('/create'),
            'edit' => EditGoldPrice::route('/{record}/edit'),
        ];
    }
}
