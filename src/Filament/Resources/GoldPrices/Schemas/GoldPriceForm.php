<?php

namespace RayzenAI\GoldSilverPrice\Filament\Resources\GoldPrices\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GoldPriceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->label('Date')
                    ->required()
                    ->columnSpanFull()
                    ->default(today())
                    ->unique(ignoreRecord: true)
                    ->native(false)
                    ->displayFormat('M d, Y')
                    ->helperText('Select the date for this price record'),

                Section::make('Gold Prices')
                    ->description('Fine Gold (9999) prices in Nepali Rupees')
                    ->schema([
                        TextInput::make('gold_per_tola')
                            ->label('Gold per Tola')
                            ->required()
                            ->numeric()
                            ->integer()
                            ->minValue(0)
                            ->prefix('Rs.')
                            ->helperText('Price of fine gold per tola (11.664g)'),

                        TextInput::make('gold_per_10g')
                            ->label('Gold per 10 Grams')
                            ->required()
                            ->numeric()
                            ->integer()
                            ->minValue(0)
                            ->prefix('Rs.')
                            ->helperText('Price of fine gold per 10 grams'),
                    ])
                    ->columns(2),

                Section::make('Silver Prices')
                    ->description('Silver prices in Nepali Rupees')
                    ->schema([
                        TextInput::make('silver_per_tola')
                            ->label('Silver per Tola')
                            ->required()
                            ->numeric()
                            ->integer()
                            ->minValue(0)
                            ->prefix('Rs.')
                            ->helperText('Price of silver per tola (11.664g)'),

                        TextInput::make('silver_per_10g')
                            ->label('Silver per 10 Grams')
                            ->required()
                            ->numeric()
                            ->integer()
                            ->minValue(0)
                            ->prefix('Rs.')
                            ->helperText('Price of silver per 10 grams'),
                    ])
                    ->columns(2),
            ]);
    }
}
