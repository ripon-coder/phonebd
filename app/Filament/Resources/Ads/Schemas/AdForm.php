<?php

namespace App\Filament\Resources\Ads\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

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
                            ->required()
                            ->columnSpanFull(),

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
