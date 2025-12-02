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

                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->disk('backblaze')
                            ->directory('ads')
                            ->visibility('public')
                            ->previewable(false)
                            ->openable(true)
                            ->downloadable(true)
                            ->required()
                            ->saveUploadedFileUsing(function (UploadedFile $file) {
                                $manager = new ImageManager(new Driver());
                                $image = $manager->read($file);
                                
                                // Optimize
                                if ($image->width() > 1200) {
                                    $image->scale(width: 1200);
                                }
                                
                                $encoded = $image->toWebp(quality: 80);
                                
                                $filename = pathinfo($file->hashName(), PATHINFO_FILENAME) . '.webp';
                                $path = 'ads/' . $filename;
                                
                                Storage::disk('backblaze')->put($path, (string) $encoded, [
                                    'visibility' => 'public',
                                    'mimetype' => 'image/webp'
                                ]);
                                
                                return $path;
                            })
                            ->columnSpanFull(),

                        Forms\Components\Hidden::make('storage_type')
                            ->default('backblaze'),

                        Forms\Components\TextInput::make('link')
                            ->required()
                            ->maxLength(255)
                            ->url(),

                        Forms\Components\Select::make('position')
                            ->options([
                                'home_top' => 'Home Top',
                                'home_middle' => 'Home Middle',
                                'home_bottom' => 'Home Bottom',
                                'sidebar' => 'Sidebar',
                            ])
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->required(),

                        Forms\Components\TextInput::make('sort_order')
                            ->required()
                            ->numeric()
                            ->default(0),
                            ]),

                Forms\Components\Textarea::make('script')
                    ->maxLength(65535),

                Forms\Components\Toggle::make('status')
                    ->required(),

                Forms\Components\DatePicker::make('end_date'),
            ])->columns(1);
    }
}
