<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\IncomeCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncomeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => IncomeCategory::factory(),
            'id_company' => Company::factory(),
            'amount' => $this->faker->randomFloat(2, 100, 10000),
            'date' => $this->faker->date(),
            'description' => $this->faker->sentence(),
            'is_recurring' => $this->faker->boolean(20),
            'recurrence_frequency' => $this->faker->optional()->randomElement(['weekly', 'monthly', 'yearly']),
            'tax_deductible' => $this->faker->boolean(50),
        ];
    }
}
