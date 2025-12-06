<?php

namespace App\Filament\Resources\CameraSamples;

use App\Filament\Resources\CameraSamples\Pages\CreateCameraSample;
use App\Filament\Resources\CameraSamples\Pages\EditCameraSample;
use App\Filament\Resources\CameraSamples\Pages\ListCameraSamples;
use App\Filament\Resources\CameraSamples\Schemas\CameraSampleForm;
use App\Filament\Resources\CameraSamples\Tables\CameraSamplesTable;
use App\Models\CameraSample;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CameraSampleResource extends Resource
{
    protected static ?string $model = CameraSample::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCamera;
    protected static string|\UnitEnum|null $navigationGroup = 'User Interaction';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CameraSampleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CameraSamplesTable::configure($table);
    }

    public static function infolist(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Sample Details')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('product.title')->label('Product'),
                        \Filament\Infolists\Components\TextEntry::make('name')->label('Uploader'),
                        \Filament\Infolists\Components\TextEntry::make('variant'),
                        \Filament\Infolists\Components\ImageEntry::make('images')
                            ->disk('backblaze')
                            ->visibility('public')
                            ->columnSpanFull(),
                    ])->columns(2),
                \Filament\Schemas\Components\Section::make('User Info')
                    ->schema([
                        \Filament\Infolists\Components\TextEntry::make('ip_address')->copyable(),
                        \Filament\Infolists\Components\TextEntry::make('finger_print'),
                        \Filament\Infolists\Components\IconEntry::make('is_approve')->boolean()->label('Approved'),
                        \Filament\Infolists\Components\IconEntry::make('is_ip_banned')->boolean()->label('IP Banned'),
                    ])->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCameraSamples::route('/'),
            //'create' => CreateCameraSample::route('/create'),
            //'edit' => EditCameraSample::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_approve', false)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('is_approve', false)->exists() ? 'danger' : 'primary';
    }
}
