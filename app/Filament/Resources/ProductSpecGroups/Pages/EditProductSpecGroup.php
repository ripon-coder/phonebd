<?php

namespace App\Filament\Resources\ProductSpecGroups\Pages;

use App\Filament\Resources\ProductSpecGroups\ProductSpecGroupResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProductSpecGroup extends EditRecord
{
    protected static string $resource = ProductSpecGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
