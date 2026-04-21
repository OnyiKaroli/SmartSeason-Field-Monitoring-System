<?php

namespace Database\Factories;

use App\Models\Field;
use App\Models\FieldUpdate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FieldUpdate>
 */
class FieldUpdateFactory extends Factory
{
    protected $model = FieldUpdate::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'field_id' => Field::factory(),
            'updated_by' => User::factory(),
            'previous_stage' => 'Planted',
            'new_stage' => 'Growing',
            'note' => \fake()->sentence(),
            'observed_at' => now(),
        ];
    }
}
