<?php

namespace RayzenAI\GoldSilverPrice\Filament\Resources\GoldPrices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GoldPricesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable()
                    ->searchable()
                    ->description(fn ($record) => $record->date->diffForHumans()),

                TextColumn::make('gold_per_tola')
                    ->label('Gold/Tola')
                    ->money('NPR', locale: 'en_NP')
                    ->sortable()
                    ->alignEnd(),

                TextColumn::make('gold_per_10g')
                    ->label('Gold/10g')
                    ->money('NPR', locale: 'en_NP')
                    ->sortable()
                    ->alignEnd(),

                TextColumn::make('silver_per_tola')
                    ->label('Silver/Tola')
                    ->money('NPR', locale: 'en_NP')
                    ->sortable()
                    ->alignEnd(),

                TextColumn::make('silver_per_10g')
                    ->label('Silver/10g')
                    ->money('NPR', locale: 'en_NP')
                    ->sortable()
                    ->alignEnd(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
