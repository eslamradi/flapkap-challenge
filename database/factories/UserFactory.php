<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'username' => fake()->unique()->userName(),
            'deposit' => fake()->randomElement([5, 10, 20, 50, 100]),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ];
    }

    /**
     * Indicate that the model is a seller
     *
     * @return static
     */
    public function seller()
    {
        return $this->state(fn (array $attributes) => [
            'role' => Role::SELLER,
        ]);
    }

    /**
     * Indicate that the model is a buyer
     *
     * @return static
     */
    public function buyer()
    {
        return $this->state(fn (array $attributes) => [
            'role' => Role::BUYER,
        ]);
    }

    /**
     * Indicate that the model is a admin
     *
     * @return static
     */
    public function admin()
    {
        return $this->state(fn (array $attributes) => [
            'role' => Role::ADMIN,
        ]);
    }
}
