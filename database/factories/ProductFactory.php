<?php

namespace Database\Factories;

use App\Models\User;
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
    public function definition()
    {
        return [
            'productName' => fake()->unique()->text(),
            'amountAvailable' => fake()->numberBetween(2, 10),
            'cost' => fake()->randomElement([5, 10, 20, 50, 100]),
        ];
    }

    public function seller(User $seller)
    {
        return $this->state(fn (array $attributes) => [
            'sellerId' => $seller->id,
        ]);
    }
}
