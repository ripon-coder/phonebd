<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $specGroups = $data['specValues'] ?? [];
        dd($specGroups);
        // $flat = [];

        // foreach ($specGroups as $group) {
        //     foreach ($group['items'] as $item) {
        //         $flat[] = [
        //             'product_spec_item_id' => $item['product_spec_item_id'],
        //             'value' => $item['value'],
        //         ];
        //     }
        // }

        // $data['specValues'] = $flat;

        // unset($data['specGroups']);

        // return $data;
    }
}
