<?php

namespace App\Filament\Resources\ProductSpecGroups\Schemas;

use App\Models\Product;
use Filament\Forms;
use Filament\Schemas\Schema;

class ProductSpecGroupForm
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

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
