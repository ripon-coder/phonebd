<?php

namespace App\Filament\Resources\ProductFaqs\Schemas;

use App\Models\Product;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductFaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('FAQ Details')
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->label('Product')
                            ->relationship('product', 'title')
                            ->searchable()
                            ->required()
                            ->default(request()->query('product_id')),

                        Forms\Components\TextInput::make('question')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('answer')
                            ->required()
                            ->maxLength(65535)
                            ->rows(5)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('sort_order')
                            ->required()
                            ->numeric()
                            ->default(0),
                    ])
                    
            ])->columns(1);
    }
    public static function makeRepeater(): Forms\Components\Repeater
    {
        return Forms\Components\Repeater::make('faqs')
            ->relationship('faqs')
            ->label('Frequently Asked Questions')
            ->schema([
                Forms\Components\TextInput::make('question')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('answer')
                    ->required()
                    ->maxLength(65535)
                    ->rows(5)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->label('Sort Order'),
            ])
            ->itemLabel(fn (array $state): ?string => $state['question'] ?? null)
            ->collapsed()
            ->collapseAllAction(fn ($action) => $action->label('Collapse All'))
            ->defaultItems(0)
            ->columnSpanFull();
    }
}
