<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Stage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'user_id' => User::where('is_admin', 0)->inRandomOrder()->first()->id ?? User::factory(),
            'stage_id' => Stage::inRandomOrder()->first()->id ?? Stage::factory(),
            'assigned_to' => User::where('is_admin', 0)->inRandomOrder()->first()->id ?? User::factory(),
        ];
    }
}
