<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount>
 */
class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => Str::limit(fake()->sentence(), 60, ''),
            'discount_value' => 10,
            'type' => 'percentage',
            'is_active' => true,
        ];
    }

    public function fixedAmount(int $value = 20): static
    {
        return $this->state(fn (array $attributes) => [
            'type'           => 'fixed_amount',
            'discount_value' => $value,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
