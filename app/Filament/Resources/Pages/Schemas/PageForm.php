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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

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
                            ->disk('backblaze')
                            ->image()
                            ->directory('pages')
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
                                $path = 'pages/' . $filename;
                                
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
