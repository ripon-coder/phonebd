<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Brand;
use App\Models\Category;
use Filament\Forms;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Forms\Components\Select::make('brand_id')
                    ->label('Brand')
                    ->options(Brand::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->options(Category::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Textarea::make('short_description')
                    ->maxLength(65535),

                Forms\Components\Select::make('status')
                    ->options([
                        'official' => 'Official',
                        'unofficial' => 'Unofficial',
                        'upcoming' => 'Upcoming',
                        'discontinued' => 'Discontinued',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('base_price')
                    ->numeric()
                    ->required(),

                Forms\Components\Toggle::make('is_published')
                    ->required(),

                Forms\Components\TextInput::make('meta_title')
                    ->maxLength(255),

                Forms\Components\Textarea::make('meta_description')
                    ->maxLength(65535),

                Forms\Components\TagsInput::make('meta_keywords'),

                Forms\Components\FileUpload::make('meta_image')
                    ->image(),
            ]);
    }
}
