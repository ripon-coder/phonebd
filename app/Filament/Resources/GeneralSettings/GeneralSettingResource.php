<?php

namespace App\Filament\Resources\GeneralSettings;

use App\Filament\Resources\GeneralSettings\Pages\CreateGeneralSetting;
use App\Filament\Resources\GeneralSettings\Pages\EditGeneralSetting;
use App\Filament\Resources\GeneralSettings\Pages\ListGeneralSettings;
use App\Filament\Resources\GeneralSettings\Schemas\GeneralSettingForm;
use App\Filament\Resources\GeneralSettings\Tables\GeneralSettingsTable;
use App\Models\GeneralSetting;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GeneralSettingResource extends Resource
{
    protected static ?string $model = GeneralSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog;

    protected static ?int $navigationSort = 100;

    protected static UnitEnum|string|null $navigationGroup = 'System';

    public static function form(Schema $schema): Schema
    {
        return GeneralSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GeneralSettingsTable::configure($table);
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
            'index' => ListGeneralSettings::route('/'),
            'create' => CreateGeneralSetting::route('/create'),
            'edit' => EditGeneralSetting::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return ! GeneralSetting::exists();
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
