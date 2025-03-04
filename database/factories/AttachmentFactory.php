<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AttachmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'attachable_type' => $this->faker->randomElement(['App\Models\Invoice', 'App\Models\Expense']),
            'attachable_id' => $this->faker->numberBetween(1, 100),
            'file_name' => $this->faker->word() . '.' . $this->faker->fileExtension(),
            'file_path' => $this->faker->filePath(),
            'file_size' => $this->faker->numberBetween(1000, 10000000),
            'file_type' => $this->faker->mimeType(),
        ];
    }
}
