<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\Project;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'client_id' => Client::factory(),
            'project_id' => Project::factory(),
            'issue_date' => $this->faker->date(),
            'subtotal' => $this->faker->randomFloat(2, 100, 10000),
            'tax_amount' => $this->faker->randomFloat(2, 0, 1000),
            'total' => function (array $attributes) {
                return $attributes['subtotal'] + $attributes['tax_amount'];
            },
            'type' => $this->faker->randomElement(['expense', 'income']),
            'status' => $this->faker->randomElement(['draft', 'sent', 'paid', 'overdue']),
            'notes' => $this->faker->optional()->paragraph,
        ];
    }
}
