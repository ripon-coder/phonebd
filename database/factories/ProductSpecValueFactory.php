<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductSpecValue>
 */
class ProductSpecValueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'product_spec_group_id' => \App\Models\ProductSpecGroup::factory(),
            'product_spec_item_id' => \App\Models\ProductSpecItem::factory(),
            'value' => $this->faker->word(),
        ];
    }
}
