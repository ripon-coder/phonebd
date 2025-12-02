<?php

namespace App\Filament\Resources\GeneralSettings\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class GeneralSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Site Information')
                ->schema([
                    TextInput::make('site_name')
                        ->label('Site Name')
                        ->required(),

                    TextInput::make('site_tagline')
                        ->label('Tagline'),
                ])->columns(2),

            Section::make('Branding')
                ->schema([
                    FileUpload::make('site_logo')
                        ->disk('public')
                        ->label('Site Logo')
                        ->image()
                        ->directory('settings')
                        ->saveUploadedFileUsing(function (UploadedFile $file) {
                            $manager = new ImageManager(new Driver());
                            $image = $manager->read($file);
                            
                            // Optimize
                            if ($image->width() > 1200) {
                                $image->scale(width: 1200);
                            }
                            
                            $encoded = $image->toWebp(quality: 80);
                            
                            $filename = pathinfo($file->hashName(), PATHINFO_FILENAME) . '.webp';
                            $path = 'settings/' . $filename;
                            
                            Storage::disk('public')->put($path, (string) $encoded, [
                                'visibility' => 'public',
                                'mimetype' => 'image/webp'
                            ]);
                            
                            return $path;
                        }),

                    FileUpload::make('site_favicon')
                        ->disk('public')
                        ->label('Favicon')
                        ->image()
                        ->directory('settings')
                        ->saveUploadedFileUsing(function (UploadedFile $file) {
                            $manager = new ImageManager(new Driver());
                            $image = $manager->read($file);
                            
                            // Optimize
                            if ($image->width() > 1200) {
                                $image->scale(width: 1200);
                            }
                            
                            $encoded = $image->toWebp(quality: 80);
                            
                            $filename = pathinfo($file->hashName(), PATHINFO_FILENAME) . '.webp';
                            $path = 'settings/' . $filename;
                            
                            Storage::disk('public')->put($path, (string) $encoded, [
                                'visibility' => 'public',
                                'mimetype' => 'image/webp'
                            ]);
                            
                            return $path;
                        }),
                        
                    \Filament\Forms\Components\Hidden::make('storage_type')
                        ->default('public'),
                ])->columns(2),

            Section::make('Appearance')
                ->schema([
                    ColorPicker::make('primary_color')
                        ->label('Primary Color'),

                    ColorPicker::make('secondary_color')
                        ->label('Secondary Color'),
                ])->columns(2),

            Section::make('SEO Settings')
                ->schema([
                    TextInput::make('meta_title')
                        ->label('Meta Title'),

                    Textarea::make('meta_description')
                        ->label('Meta Description'),

                    Textarea::make('meta_keywords')
                        ->label('Meta Keywords'),
                ])->columns(1),

            Section::make('Contact Information')
                ->schema([
                    TextInput::make('contact_email')
                        ->label('Contact Email'),

                    TextInput::make('contact_phone')
                        ->label('Contact Phone'),

                    Textarea::make('contact_address')
                        ->label('Address'),
                ])->columns(1),

            Section::make('Social Links')
                ->schema([
                    TextInput::make('facebook_link')->label('Facebook'),
                    TextInput::make('youtube_link')->label('YouTube'),
                    TextInput::make('instagram_link')->label('Instagram'),
                    TextInput::make('twitter_link')->label('Twitter'),
                ])->columns(2),

            Section::make('Maintenance Mode')
                ->schema([
                    Toggle::make('is_maintenance_mode')
                        ->label('Enable Maintenance Mode'),

                    Textarea::make('maintenance_message')
                        ->label('Maintenance Message')
                        ->placeholder('We are currently under maintenance...'),
                ])->columns(1),
        ])->columns(1);
    }
}
