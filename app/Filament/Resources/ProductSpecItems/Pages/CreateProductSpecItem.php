<?php

namespace App\Filament\Resources\ProductSpecItems\Pages;

use App\Filament\Resources\ProductSpecItems\ProductSpecItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductSpecItem extends CreateRecord
{
    protected static string $resource = ProductSpecItemResource::class;
}
