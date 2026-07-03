<?php

namespace Database\Factories;

use App\Enums\BudgetType;
use App\Models\Budget;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Budget>
 */
class BudgetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->sentence(1),
            'amount' => fake()->numberBetween(9999, 99999),
            'type' => fake()->randomElement(BudgetType::class),
        ];
    }
}
