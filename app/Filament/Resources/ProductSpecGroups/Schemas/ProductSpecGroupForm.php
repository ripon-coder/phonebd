<?php

namespace App\Filament\Resources\ProductSpecGroups\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;

class ProductSpecGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Specification Group Details')
                ->schema([

                    TextInput::make('name')
                        ->label('Group Name')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('sort_order')
                        ->label('Sort Order')
                        ->numeric()
                        ->default(0),

                ])
                ->columns(2),

        ])->columns(1);
    }
}
