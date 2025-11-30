<?php

namespace App\Filament\Resources\ProductPerformances;

use App\Filament\Resources\ProductPerformances\Pages\CreateProductPerformance;
use App\Filament\Resources\ProductPerformances\Pages\EditProductPerformance;
use App\Filament\Resources\ProductPerformances\Pages\ListProductPerformances;
use App\Filament\Resources\ProductPerformances\Schemas\ProductPerformanceForm;
use App\Filament\Resources\ProductPerformances\Tables\ProductPerformancesTable;
use App\Models\ProductPerformance;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductPerformanceResource extends Resource
{
    protected static ?string $model = ProductPerformance::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ProductPerformanceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductPerformancesTable::configure($table);
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
            'index' => ListProductPerformances::route('/'),
            'create' => CreateProductPerformance::route('/create'),
            'edit' => EditProductPerformance::route('/{record}/edit'),
        ];
    }
}
