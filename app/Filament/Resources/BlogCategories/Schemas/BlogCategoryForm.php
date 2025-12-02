<?php

namespace App\Filament\Resources\BlogCategories\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class BlogCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
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
                            ->unique(table: 'blog_categories', ignoreRecord: true),

                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->disk('backblaze')
                            ->directory('blog-categories')
                            ->visibility('public')
                            ->previewable(false)
                            ->openable(true)
                            ->downloadable(true)
                            ->saveUploadedFileUsing(function (UploadedFile $file) {
                                $manager = new ImageManager(new Driver());
                                $image = $manager->read($file);
                                
                                // Optimize
                                if ($image->width() > 1200) {
                                    $image->scale(width: 1200);
                                }
                                
                                $encoded = $image->toWebp(quality: 80);
                                
                                $filename = pathinfo($file->hashName(), PATHINFO_FILENAME) . '.webp';
                                $path = 'blog-categories/' . $filename;
                                
                                Storage::disk('backblaze')->put($path, (string) $encoded, [
                                    'visibility' => 'public',
                                    'mimetype' => 'image/webp'
                                ]);
                                
                                return $path;
                            })
                            ->columnSpanFull(),

                        Forms\Components\Hidden::make('storage_type')
                            ->default('backblaze'),

                        Forms\Components\Toggle::make('is_active')
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
