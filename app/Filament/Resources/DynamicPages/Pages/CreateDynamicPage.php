<?php

namespace App\Filament\Resources\DynamicPages\Pages;

use App\Filament\Resources\DynamicPages\DynamicPageResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDynamicPage extends CreateRecord
{
    protected static string $resource = DynamicPageResource::class;
}
