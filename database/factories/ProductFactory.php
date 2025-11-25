<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(3);
        return [
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title),
            'brand_id' => \App\Models\Brand::factory(),
            'category_id' => \App\Models\Category::factory(),
            'image' => null,
            'status' => $this->faker->randomElement(['official', 'unofficial', 'upcoming']),
            'base_price' => $this->faker->numberBetween(10000, 150000),
            'short_description' => $this->faker->paragraph(),
            'is_published' => true,
            'is_featured' => $this->faker->boolean(20),
        ];
    }
}
