<?php

namespace App\Filament\Resources\CameraSamples\Pages;

use App\Filament\Resources\CameraSamples\CameraSampleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCameraSample extends EditRecord
{
    protected static string $resource = CameraSampleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
