<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Filament\Forms\Components\FileUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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

                    TextInput::make('sort_order')
                        ->label('Sort Order')
                        ->numeric()
                        ->default(0),

                    FileUpload::make('image')
                        ->image()
                        ->disk('backblaze')
                        ->directory('brands')
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
                            $path = 'brands/' . $filename;
                            
                            Storage::disk('backblaze')->put($path, (string) $encoded, [
                                'visibility' => 'public',
                                'mimetype' => 'image/webp'
                            ]);
                            
                            return $path;
                        })
                        ->columnSpanFull(),

                    \Filament\Forms\Components\Hidden::make('storage_type')
                        ->default('backblaze'),
                ])
                ->columns(3),

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
