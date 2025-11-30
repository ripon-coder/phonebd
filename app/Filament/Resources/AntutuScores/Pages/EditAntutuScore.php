<?php

namespace App\Filament\Resources\AntutuScores\Pages;

use App\Filament\Resources\AntutuScores\AntutuScoreResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAntutuScore extends EditRecord
{
    protected static string $resource = AntutuScoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
