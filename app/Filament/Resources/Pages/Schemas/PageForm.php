<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Page Details')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (callable $set, ?string $state) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Toggle::make('is_active')
                            ->default(true)
                            ->inline(false),
                        RichEditor::make('content')
                            ->columnSpanFull(),
                        FileUpload::make('featured_image')
                            ->image()
                            ->directory('pages')
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
                Section::make('SEO')
                    ->schema([
                        TextInput::make('meta_title'),
                        Textarea::make('meta_description'),
                        Textarea::make('meta_keywords'),
                    ])
                    ->collapsed()
                    ->columns(1),
            ])
            ->columns(1);
    }
}
