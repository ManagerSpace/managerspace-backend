<?php
namespace Database\Factories;

use App\Models\TimesheetEntry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimesheetEntryFactory extends Factory
{
    protected $model = TimesheetEntry::class;

    public function definition()
    {
        $date = $this->faker->dateTimeBetween('-1 week', 'now');
        $type = $this->faker->randomElement(['check_in', 'check_out']);

        return [
            'user_id' => User::factory(),
            'date' => $date->format('Y-m-d'),
            'time' => $date->format('H:i:s'),
            'type' => $type,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'notes' => $this->faker->optional()->sentence,
        ];
    }

    public function checkIn()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'check_in',
            ];
        });
    }

    public function checkOut()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'check_out',
            ];
        });
    }
}
