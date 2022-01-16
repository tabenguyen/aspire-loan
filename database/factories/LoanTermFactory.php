<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LoanTermFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'apr' => rand(1, 20),
            'length' => rand(12, 240),
            'fee' => rand(0, 300),
            'interest_type' => rand(0,1)
        ];
    }
}
