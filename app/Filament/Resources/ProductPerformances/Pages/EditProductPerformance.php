<?php

namespace App\Filament\Resources\ProductPerformances\Pages;

use App\Filament\Resources\ProductPerformances\ProductPerformanceResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductPerformance extends EditRecord
{
    protected static string $resource = ProductPerformanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
