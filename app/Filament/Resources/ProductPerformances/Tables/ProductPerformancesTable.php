<?php

namespace App\Filament\Resources\ProductPerformances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class ProductPerformancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('product.title')
                    ->searchable()
                    ->sortable(),
                \Filament\Tables\Columns\TextColumn::make('gaming_fps'),
                \Filament\Tables\Columns\TextColumn::make('battery_sot'),
                \Filament\Tables\Columns\TextColumn::make('camera_score'),
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
