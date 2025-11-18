<?php

namespace App\Filament\Resources\ProductSpecItems\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Str;

class ProductSpecItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Specification Item Details')
                ->schema([

                    // PERFECT: Relationship-based select
                    Select::make('product_spec_group_id')
                        ->label('Specification Group')
                        ->relationship('group', 'name')
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
                            'textarea' => 'Textarea',
                        ])
                        ->required()
                        ->live(),   // <-- Needed for conditional fields

                    // Conditional field: Only visible when input_type = select
                    Textarea::make('options')
                        ->label('Options (comma separated)')
                        ->visible(fn ($get) => $get('input_type') === 'select')
                        ->placeholder("Example: AMOLED, Super AMOLED, OLED")
                        ->columnSpan(2),

                    TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->label('Sort Order'),
                    
                ])
                ->columns(2),

        ])->columns(1);
    }
}
