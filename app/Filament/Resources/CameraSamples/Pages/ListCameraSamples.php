<?php

namespace App\Filament\Resources\CameraSamples\Pages;

use App\Filament\Resources\CameraSamples\CameraSampleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCameraSamples extends ListRecords
{
    protected static string $resource = CameraSampleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //CreateAction::make(),
        ];
    }
}
