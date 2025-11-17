<?php

namespace App\Filament\Resources\ProductVariantPrices\Schemas;

use App\Models\Product;
use Filament\Forms;
use Filament\Schemas\Schema;

class ProductVariantPriceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('product_id')
                    ->label('Product')
                    ->options(Product::all()->pluck('title', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('ram')
                    ->maxLength(255),

                Forms\Components\TextInput::make('storage')
                    ->maxLength(255),

                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('currency')
                    ->required()
                    ->maxLength(255)
                    ->default('BDT'),
            ]);
    }
}
