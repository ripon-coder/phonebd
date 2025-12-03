<?php

namespace App\Filament\Resources\DynamicPages;

use App\Filament\Resources\DynamicPages\Pages\CreateDynamicPage;
use App\Filament\Resources\DynamicPages\Pages\EditDynamicPage;
use App\Filament\Resources\DynamicPages\Pages\ListDynamicPages;
use App\Filament\Resources\DynamicPages\Schemas\DynamicPageForm;
use App\Filament\Resources\DynamicPages\Tables\DynamicPagesTable;
use App\Models\DynamicPage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DynamicPageResource extends Resource
{
    protected static ?string $model = DynamicPage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;
    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return DynamicPageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DynamicPagesTable::configure($table);
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
            'index' => ListDynamicPages::route('/'),
            'create' => CreateDynamicPage::route('/create'),
            'edit' => EditDynamicPage::route('/{record}/edit'),
        ];
    }
}
