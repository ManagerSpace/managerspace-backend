<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TaxFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->word() . ' Tax',
            'rate' => $this->faker->randomFloat(2, 0, 25),
        ];
    }
}
