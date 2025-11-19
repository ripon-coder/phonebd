<?php

namespace App\Filament\Resources\ProductSpecItems;

use App\Filament\Resources\ProductSpecItems\Pages\CreateProductSpecItem;
use App\Filament\Resources\ProductSpecItems\Pages\EditProductSpecItem;
use App\Filament\Resources\ProductSpecItems\Pages\ListProductSpecItems;
use App\Filament\Resources\ProductSpecItems\Schemas\ProductSpecItemForm;
use App\Filament\Resources\ProductSpecItems\Tables\ProductSpecItemsTable;
use App\Models\ProductSpecItem;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductSpecItemResource extends Resource
{
    protected static ?string $model = ProductSpecItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    protected static UnitEnum|string|null $navigationGroup = 'Products';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return ProductSpecItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductSpecItemsTable::configure($table);
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
            'index' => ListProductSpecItems::route('/'),
            'create' => CreateProductSpecItem::route('/create'),
            'edit' => EditProductSpecItem::route('/{record}/edit'),
        ];
    }
}
