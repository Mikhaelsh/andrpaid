<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $logDate = $this->faker->dateTimeBetween('-14 days', 'now');

        return [
            'type' => $this->faker->randomElement(['login', 'logout']),
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'created_at' => $logDate,
            'updated_at' => $logDate,
        ];
    }
}
