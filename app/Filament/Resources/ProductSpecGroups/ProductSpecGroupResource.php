<?php

namespace App\Filament\Resources\ProductSpecGroups;

use App\Filament\Resources\ProductSpecGroups\Pages\CreateProductSpecGroup;
use App\Filament\Resources\ProductSpecGroups\Pages\EditProductSpecGroup;
use App\Filament\Resources\ProductSpecGroups\Pages\ListProductSpecGroups;
use App\Filament\Resources\ProductSpecGroups\Schemas\ProductSpecGroupForm;
use App\Filament\Resources\ProductSpecGroups\Tables\ProductSpecGroupsTable;
use App\Models\ProductSpecGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductSpecGroupResource extends Resource
{
    protected static ?string $model = ProductSpecGroup::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ProductSpecGroupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductSpecGroupsTable::configure($table);
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
            'index' => ListProductSpecGroups::route('/'),
            'create' => CreateProductSpecGroup::route('/create'),
            'edit' => EditProductSpecGroup::route('/{record}/edit'),
        ];
    }
}
