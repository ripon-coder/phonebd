<?php

namespace App\Filament\Resources\Ads\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;

class AdForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('position')
                    ->options([
                        'product_top' => 'Product Top',
                        'product_middle' => 'Product Middle',
                        'product_bottom' => 'Product Bottom',
                    ])
                    ->required(),

                Forms\Components\FileUpload::make('image')
                    ->image(),

                Forms\Components\TextInput::make('link')
                    ->maxLength(255),

                Forms\Components\Textarea::make('script')
                    ->maxLength(65535),

                Forms\Components\Toggle::make('status')
                    ->required(),

                Forms\Components\DatePicker::make('start_date'),

                Forms\Components\DatePicker::make('end_date'),
            ]);
    }
}
