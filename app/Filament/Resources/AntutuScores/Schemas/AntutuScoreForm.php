<?php

namespace App\Filament\Resources\AntutuScores\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class AntutuScoreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product Selection')
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'title')
                            ->searchable()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->default(request()->query('product_id')),
                    ]),

                Section::make('Antutu Benchmark Scores')
                    ->schema([
                        TextInput::make('total_score')
                            ->label('Total Score')
                            ->numeric(),
                        
                        TextInput::make('cpu_score')
                            ->label('CPU Score')
                            ->numeric(),
                        
                        TextInput::make('gpu_score')
                            ->label('GPU Score')
                            ->numeric(),
                        
                        TextInput::make('mem_score')
                            ->label('MEM Score')
                            ->numeric(),
                        
                        TextInput::make('ux_score')
                            ->label('UX Score')
                            ->numeric(),

                        TextInput::make('version')
                            ->label('Antutu Version')
                            ->default('v10'),
                    ])->columns(2),
            ])->columns(1);
    }
}
