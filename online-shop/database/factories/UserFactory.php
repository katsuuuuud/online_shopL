<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'    => Str::limit(fake()->name(), 45, ''),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => Str::limit(
                str_replace("\n", ', ', fake()->streetAddress()), 45, ''),
            'password' => static::$password ??= Hash::make('password'),
        ];
    }
}
