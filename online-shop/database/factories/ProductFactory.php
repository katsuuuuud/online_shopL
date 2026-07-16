<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Discount;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'         => Str::limit(fake()->words(3, true), 50, ''),
            'description'  => Str::limit(fake()->sentence(), 100, ''),
            'category_id'  => Category::factory(),
            'discount_id'  => null
        ];
    }

    public function withDiscount(): static
    {
        return $this->state(fn (array $attributes) => [
            'discount_id' => Discount::factory(),
        ]);
    }
}
