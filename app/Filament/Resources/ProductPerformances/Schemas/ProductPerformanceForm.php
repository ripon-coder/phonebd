<?php

namespace App\Filament\Resources\ProductPerformances\Schemas;

use Filament\Schemas\Schema;

class ProductPerformanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('product_id')
                    ->relationship('product', 'title')
                    ->searchable()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->default(request()->query('product_id')),
                \Filament\Forms\Components\TextInput::make('gaming_fps')
                    ->label('Gaming FPS'),
                \Filament\Forms\Components\TextInput::make('battery_sot')
                    ->label('Battery SOT'),
                \Filament\Forms\Components\TextInput::make('camera_score')
                    ->label('Camera Score'),
            ]);
    }
}
