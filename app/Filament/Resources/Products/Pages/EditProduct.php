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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $product = $this->getRecord();
        $product->load('specValues.productSpecGroup', 'specValues.productSpecItem');

        $specifications = [];
        $grouped = $product->specValues->groupBy('productSpecGroup.name');
        $groupIds = [];

        foreach ($grouped as $groupName => $values) {
            $items = [];
            // Get group ID for selector
            $firstValue = $values->first();
            if ($firstValue) {
                $groupIds[] = $firstValue->product_spec_group_id;
            }

            foreach ($values as $value) {
                $items[] = [
                    'product_spec_item_id' => $value->product_spec_item_id,
                    '_label' => $value->productSpecItem->label ?? '',
                    'value' => $value->value,
                ];
            }

            // Let's re-fetch the group to get all items, merging with saved values.
            $groupId = $values->first()->product_spec_group_id;
            $allGroupItems = \App\Models\ProductSpecItem::where('product_spec_group_id', $groupId)
                ->orderBy('sort_order')
                ->get();

            $mergedItems = $allGroupItems->map(function ($item) use ($values) {
                $savedValue = $values->firstWhere('product_spec_item_id', $item->id);
                return [
                    'product_spec_item_id' => $item->id,
                    '_label' => $item->label,
                    'value' => $savedValue ? $savedValue->value : null,
                ];
            })->toArray();

            $specifications[] = [
                'group_name' => $groupName,
                'items' => $mergedItems,
            ];
        }

        $data['specifications'] = $specifications;
        $data['spec_group_selector'] = array_unique($groupIds);

        return $data;
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $specifications = $data['specifications'] ?? [];
        unset($data['specifications']);
        // Also unset the selector if it exists in data to avoid model error
        unset($data['spec_group_selector']);

        $record->update($data);

        // If raw HTML is enabled, do not touch specifications (preserve them)
        if (!empty($data['is_raw_html'])) {
            return $record;
        }

        // Always delete old specs to ensure we sync correctly (handling deletions)
        \App\Models\ProductSpecValue::where('product_id', $record->id)->delete();

        if (!empty($specifications)) {
            $flatSpecs = [];
            foreach ($specifications as $group) {
                $groupModel = \App\Models\ProductSpecGroup::where('name', $group['group_name'])->first();
                if (!$groupModel) continue;

                foreach ($group['items'] as $item) {
                    // Check if value is not null and not empty string. Allow '0'.
                    if (isset($item['value']) && $item['value'] !== '') {
                        $flatSpecs[] = [
                            'product_id' => $record->id,
                            'product_spec_group_id' => $groupModel->id,
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
