<?php

namespace App\Filament\Resources\ProductSpecItems\Schemas;

use App\Models\ProductSpecGroup;
use Filament\Forms;
use Filament\Schemas\Schema;

class ProductSpecItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('product_spec_group_id')
                    ->label('Spec Group')
                    ->options(ProductSpecGroup::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('key')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('value')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
