<?php

namespace App\Filament\Resources\ProductSpecGroups\Pages;

use App\Filament\Resources\ProductSpecGroups\ProductSpecGroupResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductSpecGroup extends CreateRecord
{
    protected static string $resource = ProductSpecGroupResource::class;
}
