<?php

namespace Database\Seeders;

use App\Models\Admin;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // 1. Create Brands
        $brands = \App\Models\Brand::factory(10)->create();

        // 2. Create Categories
        $categories = \App\Models\Category::factory()->createMany([
            ['name' => 'Mobile', 'slug' => 'mobile'],
            ['name' => 'Tablet', 'slug' => 'tablet'],
            ['name' => 'Smartwatch', 'slug' => 'smartwatch'],
            ['name' => 'Headphone', 'slug' => 'headphone'],
            ['name' => 'Accessories', 'slug' => 'accessories'],
        ]);

        // 3. Create Spec Groups and Items
        $specGroupsData = [
            'Display' => ['Size', 'Resolution', 'Type', 'Refresh Rate'],
            'Processor' => ['Chipset', 'CPU', 'GPU'],
            'Memory' => ['RAM', 'Internal Storage', 'Card Slot'],
            'Camera' => ['Main Camera', 'Selfie Camera', 'Features'],
            'Battery' => ['Capacity', 'Charging'],
        ];

        $specItems = [];

        foreach ($specGroupsData as $groupName => $items) {
            $group = \App\Models\ProductSpecGroup::factory()->create(['name' => $groupName, 'slug' => \Illuminate\Support\Str::slug($groupName)]);
            
            foreach ($items as $itemName) {
                $specItems[] = \App\Models\ProductSpecItem::factory()->create([
                    'product_spec_group_id' => $group->id,
                    'label' => $itemName,
                    'slug' => \Illuminate\Support\Str::slug($itemName),
                ]);
            }
        }

        // 4. Create Products
        \App\Models\Product::factory(50)->make()->each(function ($product) use ($brands, $categories, $specItems) {
            $product->brand_id = $brands->random()->id;
            $product->category_id = $categories->random()->id;
            $product->save();

            // Create Variants
            \App\Models\ProductVariantPrice::factory(rand(1, 3))->create([
                'product_id' => $product->id,
            ]);

            // Create Spec Values
            // For simplicity, just pick a few random spec items to populate
            $randomSpecItems = collect($specItems)->random(rand(5, 10));
            
            foreach ($randomSpecItems as $item) {
                \App\Models\ProductSpecValue::factory()->create([
                    'product_id' => $product->id,
                    'product_spec_group_id' => $item->product_spec_group_id,
                    'product_spec_item_id' => $item->id,
                    'value' => fake()->word(), // Or more realistic data based on item label
                ]);
            }
        });
    }
}
