<?php

namespace App\Filament\Resources\ProductSpecGroups\Pages;

use App\Filament\Resources\ProductSpecGroups\ProductSpecGroupResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductSpecGroups extends ListRecords
{
    protected static string $resource = ProductSpecGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
