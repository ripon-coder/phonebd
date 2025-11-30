<?php

namespace App\Filament\Resources\AntutuScores\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class AntutuScoresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.title')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('total_score')
                    ->label('Total Score')
                    ->sortable(),
                
                TextColumn::make('cpu_score')
                    ->label('CPU')
                    ->sortable(),
                
                TextColumn::make('gpu_score')
                    ->label('GPU')
                    ->sortable(),
                
                TextColumn::make('mem_score')
                    ->label('MEM')
                    ->sortable(),
                
                TextColumn::make('ux_score')
                    ->label('UX')
                    ->sortable(),

                TextColumn::make('version')
                    ->label('Ver')
                    ->badge()
                    ->color('warning')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
