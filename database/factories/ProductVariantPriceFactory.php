<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariantPrice>
 */
class ProductVariantPriceFactory extends Factory
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
            'ram' => $this->faker->randomElement(['4GB', '6GB', '8GB', '12GB', '16GB']),
            'storage' => $this->faker->randomElement(['64GB', '128GB', '256GB', '512GB', '1TB']),
            'amount' => $this->faker->numberBetween(10000, 150000),
            'currency' => 'BDT',
        ];
    }
}
