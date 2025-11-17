<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Brand Details')
                ->schema([
                    TextInput::make('name')
                        ->label('Brand Name')
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
                        ->unique(table: 'brands', ignoreRecord: true),
                ])
                ->columns(2),

            Section::make('SEO Metadata')
                ->schema([
                    TextInput::make('meta_title')
                        ->label('Meta Title'),

                    Textarea::make('meta_description')
                        ->label('Meta Description')
                        ->rows(3)
                        ->maxLength(160)
                        ->hint('Recommended: 150-160 characters'),
                ])

        ])->columns(1);
    }
}