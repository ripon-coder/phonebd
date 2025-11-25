<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductSpecItem>
 */
class ProductSpecItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $label = $this->faker->unique()->word();
        return [
            'product_spec_group_id' => \App\Models\ProductSpecGroup::factory(),
            'label' => ucfirst($label),
            'slug' => \Illuminate\Support\Str::slug($label),
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }
}
