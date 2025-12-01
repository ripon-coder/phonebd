<?php

namespace App\Filament\Resources\DynamicPages\Schemas;

use Filament\Forms;
use Illuminate\Support\Str;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class DynamicPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Page Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn($state, callable $set) => $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('brand_id')
                            ->relationship('brand', 'name')
                            ->preload()
                            ->label('Brand (Optional)'),

                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),

                        Forms\Components\TextInput::make('youtube_link')
                            ->label('YouTube Video ID/Link')
                            ->placeholder('e.g. dQw4w9WgXcQ')
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_active')
                            ->default(true),

                        Forms\Components\Textarea::make('content')
                            ->label('Content')
                            ->rows(5)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Products')
                    ->schema([
                        Forms\Components\Select::make('products')
                            ->relationship('products', 'title')
                            ->multiple()
                            ->searchable()
                            ->label('Select Products')
                            ->helperText('Search and select mobile phones for this page.')
                            ->columnSpanFull(),
                    ]),
            ])
            ->columns(1);
    }
}
