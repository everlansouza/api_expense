<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expenses>
 */
class ExpensesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::all()->random()->id,
            'value' => $this->faker->numberBetween(10, 100),
            'date_expenses' => $this->faker->randomElement([$this->faker->dateTimeThisMonth()]),
            'description' => 'teste'
        ];
    }
}
