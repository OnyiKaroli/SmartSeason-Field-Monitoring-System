<?php

namespace Database\Factories;

use App\Models\Field;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Field>
 */
class FieldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create();

        return [
            'name' => $faker->word() . ' Field',
            'crop_type' => $faker->randomElement(['Corn', 'Wheat', 'Soybeans', 'Cotton']),
            'planting_date' => $faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            'current_stage' => $faker->randomElement(['Planted', 'Growing', 'Ready', 'Harvested']),
            'last_observation_at' => null,
            'assigned_agent_id' => null,
            'created_by' => null,
        ];
    }
}
