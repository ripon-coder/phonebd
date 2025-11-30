<?php

namespace App\Filament\Resources\AntutuScores;

use App\Filament\Resources\AntutuScores\Pages\CreateAntutuScore;
use App\Filament\Resources\AntutuScores\Pages\EditAntutuScore;
use App\Filament\Resources\AntutuScores\Pages\ListAntutuScores;
use App\Filament\Resources\AntutuScores\Schemas\AntutuScoreForm;
use App\Filament\Resources\AntutuScores\Tables\AntutuScoresTable;
use App\Models\AntutuScore;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AntutuScoreResource extends Resource
{
    protected static ?string $model = AntutuScore::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFire;

    protected static UnitEnum|string|null $navigationGroup = 'Products';

    protected static ?int $navigationSort = 10;

    protected static ?string $recordTitleAttribute = 'total_score';

    public static function form(Schema $schema): Schema
    {
        return AntutuScoreForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AntutuScoresTable::configure($table);
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
            'index' => ListAntutuScores::route('/'),
            'create' => CreateAntutuScore::route('/create'),
            'edit' => EditAntutuScore::route('/{record}/edit'),
        ];
    }
}
