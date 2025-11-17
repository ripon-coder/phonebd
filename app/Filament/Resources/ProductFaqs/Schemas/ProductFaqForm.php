<?php

namespace App\Filament\Resources\ProductFaqs\Schemas;

use App\Models\Product;
use Filament\Forms;
use Filament\Schemas\Schema;

class ProductFaqForm
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

                Forms\Components\TextInput::make('question')
                    ->required()
                    ->maxLength(255),

                Forms\Components\RichEditor::make('answer')
                    ->required()
                    ->maxLength(65535),

                Forms\Components\TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
