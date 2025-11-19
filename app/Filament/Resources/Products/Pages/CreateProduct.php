<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $specifications = $data['specifications'] ?? [];
        unset($data['specifications']);

        $record = static::getModel()::create($data);

        if (!empty($specifications)) {
            $flatSpecs = [];
            foreach ($specifications as $group) {
                foreach ($group['items'] as $item) {
                    if (isset($item['value']) && $item['value'] !== '') {
                        $flatSpecs[] = [
                            'product_id' => $record->id,
                            'product_spec_group_id' => \App\Models\ProductSpecGroup::where('name', $group['group_name'])->first()?->id,
                            'product_spec_item_id' => $item['product_spec_item_id'],
                            'value' => $item['value'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }

            if (!empty($flatSpecs)) {
                \App\Models\ProductSpecValue::insert($flatSpecs);
            }
        }

        return $record;
    }
}
