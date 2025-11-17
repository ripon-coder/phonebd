<?php

namespace App\Filament\Resources\ProductVariantPrices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables;

class ProductVariantPricesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ram')
                    ->searchable(),
                Tables\Columns\TextColumn::make('storage')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('bdt')
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
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
