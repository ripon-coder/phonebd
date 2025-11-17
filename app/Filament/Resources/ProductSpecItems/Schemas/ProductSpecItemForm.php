<?php

namespace App\Filament\Resources\ProductSpecItems\Schemas;

use App\Models\ProductSpecGroup;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Illuminate\Support\Str;

class ProductSpecItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Specification Item Details')
                ->schema([

                    Select::make('product_spec_group_id')
                        ->label('Specification Group')
                        ->options(ProductSpecGroup::pluck('name', 'id'))
                        ->searchable()
                        ->required(),

                    TextInput::make('label')
                        ->label('Label')
                        ->required()
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, callable $set) {
                            if ($state) {
                                $set('slug', Str::slug($state));
                            }
                        }),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(table: 'product_spec_items', ignoreRecord: true),

                    Select::make('input_type')
                        ->label('Input Type')
                        ->options([
                            'text' => 'Text',
                            'number' => 'Number',
                            'select' => 'Select',
                            'boolean' => 'Boolean',
                        ])
                        ->required(),

                    TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->label('Sort Order'),

                ])
                ->columns(2),

        ])->columns(1);
    }
}
