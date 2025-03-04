<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncomeCategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'name' => $this->faker->word(),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}
