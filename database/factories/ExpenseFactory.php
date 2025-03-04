<?php
namespace Database\Factories;

use App\Models\ExpenseCategory;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => ExpenseCategory::factory(),
            'id_company' => Company::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'date' => $this->faker->date(),
            'description' => $this->faker->sentence(),
            'is_recurring' => $this->faker->boolean(20),
            'recurrence_frequency' => $this->faker->optional()->randomElement(['weekly', 'monthly', 'yearly']),
            'tax_deductible' => $this->faker->boolean(50),
        ];
    }
}
