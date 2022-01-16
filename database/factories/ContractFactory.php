<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::all()->random()->id(),
            'loan_term_id' => User::all()->random()->id(),
            'amount' => rand(500, 1000) * 1000,
            'start_date' => $this->faker->dateTimeBetween('-3 months', '+3 months'),
            'status' => rand(0, 2),
        ];
    }
}
