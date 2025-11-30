<?php

namespace App\Filament\Resources\ProductPerformances\Pages;

use App\Filament\Resources\ProductPerformances\ProductPerformanceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductPerformances extends ListRecords
{
    protected static string $resource = ProductPerformanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
