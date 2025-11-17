<?php

namespace App\Filament\Resources\ProductSpecItems\Pages;

use App\Filament\Resources\ProductSpecItems\ProductSpecItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductSpecItem extends EditRecord
{
    protected static string $resource = ProductSpecItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
