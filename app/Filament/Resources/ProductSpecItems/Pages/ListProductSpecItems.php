<?php

namespace App\Filament\Resources\ProductSpecItems\Pages;

use App\Filament\Resources\ProductSpecItems\ProductSpecItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductSpecItems extends ListRecords
{
    protected static string $resource = ProductSpecItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
