<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'client_id' => Client::factory(),
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph,
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->optional()->date(),
            'status' => $this->faker->randomElement(['not_started', 'in_progress', 'completed', 'on_hold']),
            'budget' => $this->faker->optional()->randomFloat(2, 1000, 100000),
        ];
    }
}
