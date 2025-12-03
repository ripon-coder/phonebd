<?php

namespace App\Filament\Resources\Ads\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AdForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ad Details')
                    ->schema([

                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('type')
                            ->options([
                                'image' => 'Image Banner',
                                'script' => 'Script (AdSense/JS)',
                                'code' => 'Custom HTML/Code',
                            ])
                            ->required()
                            ->reactive()
                            ->default('script'),

                        Forms\Components\Select::make('position')
                            ->options([
                                'header_top' => 'Header Top',
                                'sidebar_right' => 'Sidebar Right',
                                'article_inline' => 'Article Inline',
                                'footer_sticky' => 'Footer Sticky',
                                'product_below_hero' => 'Product: Below Hero',
                                'product_below_specs' => 'Product: Below Specs',
                                'product_below_faq' => 'Product: Below FAQ',
                                'sidebar_middle' => 'Sidebar Middle',
                                'sidebar_bottom' => 'Sidebar Bottom',
                            ])
                            ->required(),

                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->disk('backblaze')
                            ->directory('ads')
                            ->visibility('public')
                            ->previewable(true)
                            ->openable(true)
                            ->downloadable(true)
                            ->visible(fn ($get) => $get('type') === 'image')
                            ->required(fn ($get) => $get('type') === 'image')
                            ->saveUploadedFileUsing(function (UploadedFile $file) {
                                $manager = new ImageManager(new Driver());
                                $image = $manager->read($file);

                                // Resize if too large
                                if ($image->width() > 1200) {
                                    $image->scale(width: 1200);
                                }

                                // Encode to WebP
                                $encoded = $image->toWebp(quality: 80);

                                $filename = pathinfo($file->hashName(), PATHINFO_FILENAME) . '.webp';
                                $path = 'ads/' . $filename;

                                Storage::disk('backblaze')->put($path, (string) $encoded, [
                                    'visibility' => 'public',
                                    'mimetype' => 'image/webp',
                                ]);

                                return $path;
                            })
                            ->columnSpanFull(),

                        Forms\Components\Hidden::make('storage_type')
                            ->default('backblaze'),

                        Forms\Components\TextInput::make('link')
                            ->maxLength(255)
                            ->url()
                            ->visible(fn ($get) => $get('type') === 'image')
                            ->required(fn ($get) => $get('type') === 'image'),

                        Forms\Components\Textarea::make('script')
                            ->rows(5)
                            ->visible(fn ($get) => in_array($get('type'), ['script', 'code']))
                            ->required(fn ($get) => in_array($get('type'), ['script', 'code']))
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_active')
                            ->default(true)
                            ->required(),

                        Forms\Components\DatePicker::make('start_date'),
                    ])->columns(2),
            ])->columns(1);
    }
}
