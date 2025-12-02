<?php

namespace App\Filament\Resources\BlogPosts\Schemas;

use App\Models\BlogCategory;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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

                        Forms\Components\FileUpload::make('featured_image')
                            ->image()
                            ->disk('backblaze')
                            ->directory('blog-posts')
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
                                $path = 'blog-posts/' . $filename;
                                
                                Storage::disk('backblaze')->put($path, (string) $encoded, [
                                    'visibility' => 'public',
                                    'mimetype' => 'image/webp'
                                ]);
                                
                                return $path;
                            })
                            ->columnSpanFull(),

                        Forms\Components\Hidden::make('storage_type')
                            ->default('backblaze'),

                        Forms\Components\Toggle::make('is_published')
                            ->required(),

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
