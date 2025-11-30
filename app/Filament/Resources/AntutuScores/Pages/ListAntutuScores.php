<?php

namespace App\Filament\Resources\AntutuScores\Pages;

use App\Filament\Resources\AntutuScores\AntutuScoreResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAntutuScores extends ListRecords
{
    protected static string $resource = AntutuScoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
