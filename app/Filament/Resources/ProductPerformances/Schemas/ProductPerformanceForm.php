<?php

namespace App\Filament\Resources\ProductPerformances\Schemas;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Actions;
use Filament\Actions\Action;

use Filament\Schemas\Schema;

class ProductPerformanceForm
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
                            ->default(request()->query('product_id'))
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $service = app(\App\Services\ProductPerformanceService::class);
                                    $set('gaming_specs_text', $service->getSpecsText($state, ['Performance', 'Platform', 'Memory', 'Processor', 'Chipset', 'GPU']));
                                    $set('battery_specs_text', $service->getSpecsText($state, ['Battery', 'Charging', 'Display', 'Platform']));
                                    $set('camera_specs_text', $service->getSpecsText($state, ['Camera', 'Video']));
                                }
                            }),
                        ]),

                Section::make('Gaming Performance')
                    ->schema([
                        Textarea::make('gaming_specs_text')
                            ->label('Relevant Specs (Editable)')
                            ->rows(5)
                            ->default(fn ($get) => app(\App\Services\ProductPerformanceService::class)->getSpecsText($get('product_id'), ['Performance', 'Platform', 'Memory', 'Processor', 'Chipset', 'GPU']))
                            ->afterStateHydrated(function (Textarea $component, $state, $record, $get) {
                                if (filled($state)) return;
                                $productId = $get('product_id') ?? ($record?->product_id);
                                if ($productId) {
                                    $component->state(app(\App\Services\ProductPerformanceService::class)->getSpecsText($productId, ['Performance', 'Platform', 'Memory', 'Processor', 'Chipset', 'GPU']));
                                }
                            }),
                        
                        Actions::make([
                            Action::make('calculate_gaming')
                                ->label('Calculate Gaming Score')
                                ->icon('heroicon-o-calculator')
                                ->action(function (callable $get, callable $set) {
                                    $specs = $get('gaming_specs_text');
                                    $score = app(\App\Services\ProductPerformanceService::class)->calculateGamingScore($specs);
                                    $set('gaming_fps', $score); 
                                }),
                        ]),

                        TextInput::make('gaming_fps')
                            ->label('Gaming FPS'),
                    ]),

                Section::make('Battery Performance')
                    ->schema([
                        Textarea::make('battery_specs_text')
                            ->label('Relevant Specs (Editable)')
                            ->rows(5)
                            ->default(fn ($get) => app(\App\Services\ProductPerformanceService::class)->getSpecsText($get('product_id'), ['Battery', 'Charging', 'Display', 'Platform']))
                            ->afterStateHydrated(function (Textarea $component, $state, $record, $get) {
                                if (filled($state)) return;
                                $productId = $get('product_id') ?? ($record?->product_id);
                                if ($productId) {
                                    $component->state(app(\App\Services\ProductPerformanceService::class)->getSpecsText($productId, ['Battery', 'Charging', 'Display', 'Platform']));
                                }
                            }),

                        Actions::make([
                            Action::make('calculate_battery')
                                ->label('Calculate Battery Score')
                                ->icon('heroicon-o-calculator')
                                ->action(function (callable $get, callable $set) {
                                    $specs = $get('battery_specs_text');
                                    $score = app(\App\Services\ProductPerformanceService::class)->calculateBatteryScore($specs);
                                    $set('battery_sot', $score);
                                }),
                        ]),

                        TextInput::make('battery_sot')
                            ->label('Battery SOT'),
                    ]),

                Section::make('Camera Performance')
                    ->schema([
                        Textarea::make('camera_specs_text')
                            ->label('Relevant Specs (Editable)')
                            ->rows(5)
                            ->default(fn ($get) => app(\App\Services\ProductPerformanceService::class)->getSpecsText($get('product_id'), ['Camera', 'Video']))
                            ->afterStateHydrated(function (Textarea $component, $state, $record, $get) {
                                if (filled($state)) return;
                                $productId = $get('product_id') ?? ($record?->product_id);
                                if ($productId) {
                                    $component->state(app(\App\Services\ProductPerformanceService::class)->getSpecsText($productId, ['Camera', 'Video']));
                                }
                            }),

                        Actions::make([
                            Action::make('calculate_camera')
                                ->label('Calculate Camera Score')
                                ->icon('heroicon-o-calculator')
                                ->action(function (callable $get, callable $set) {
                                    $specs = $get('camera_specs_text');
                                    $score = app(\App\Services\ProductPerformanceService::class)->calculateCameraScore($specs);
                                    $set('camera_score', $score);
                                }),
                        ]),

                        TextInput::make('camera_score')
                            ->label('Camera Score'),
                    ]),
            ])->columns(1);
    }
}
