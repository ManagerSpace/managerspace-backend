<?php
namespace Database\Factories;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'company_id' => Company::factory(),
            'birth_date' => $this->faker->date('Y-m-d', '-20 years'),
            'gender' => $this->faker->randomElement(['Male', 'Female', 'Other']),
            'national_id' => $this->faker->unique()->numerify('#########'),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'hire_date' => $this->faker->date('Y-m-d', '-5 years'),
            'position_id' => Position::factory(),
            'transport_expense' => $this->faker->randomFloat(2, 50, 500),
            'food_expense' => $this->faker->randomFloat(2, 100, 1000),
            'accommodation_expense' => $this->faker->randomFloat(2, 200, 2000),
            'other_expenses' => $this->faker->randomFloat(2, 50, 500),
            'status' => $this->faker->randomElement(['Active', 'Inactive', 'Suspended']),
        ];
    }
}
