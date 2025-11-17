<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use App\Models\BlogCategory;
use Filament\Forms;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('blog_category_id')
                    ->label('Category')
                    ->options(BlogCategory::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                Forms\Components\FileUpload::make('featured_image')
                    ->image()
                    ->required(),

                Forms\Components\RichEditor::make('content')
                    ->required()
                    ->maxLength(65535),

                Forms\Components\Toggle::make('published')
                    ->required(),

                Forms\Components\TextInput::make('meta_title')
                    ->maxLength(255),

                Forms\Components\Textarea::make('meta_description')
                    ->maxLength(65535),

                Forms\Components\TagsInput::make('meta_keywords'),
            ]);
    }
}
