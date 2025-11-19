<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use App\Models\BlogCategory;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Post Details')
                    ->schema([
                        Forms\Components\Select::make('blog_category_id')
                            ->label('Category')
                            ->relationship('blogCategory', 'name')
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $set('slug', Str::slug($state));
                                }
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(table: 'blog_posts', ignoreRecord: true),

                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_published')
                            ->required(),

                        Forms\Components\TextInput::make('sort_order')
                            ->required()
                            ->numeric()
                            ->default(0),
                        ]),
     

                Section::make('SEO Metadata')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title'),
                        Forms\Components\Textarea::make('meta_description')
                            ->rows(3),
                    ]),
            ])->columns(1);
    }
}
