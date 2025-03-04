<?php
namespace Database\Factories;

use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;

class PositionFactory extends Factory
{
    protected $model = Position::class;

    public function definition()
    {
        return [
            'name' => $this->faker->jobTitle(),
            'description' => $this->faker->sentence(),
            'base_salary' => $this->faker->randomFloat(2, 1500, 5000),
            'level' => $this->faker->randomElement(['Junior', 'Mid-Level', 'Senior']),
            'department' => $this->faker->randomElement(['Sales', 'IT', 'HR', 'Marketing']),
        ];
    }
}
