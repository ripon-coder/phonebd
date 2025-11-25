<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->company();
        return [
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
            'sort_order' => $this->faker->numberBetween(0, 100),
            'image' => null, // Or a placeholder URL if you want
            'is_active' => true,
        ];
    }
}
