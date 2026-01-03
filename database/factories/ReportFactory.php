<?php

namespace Database\Factories;

use App\Models\ReportType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomDate = $this->faker->dateTimeBetween('-7 days', 'now');

        return [
            'description' => $this->faker->sentence(8),
            'status' => $this->faker->randomElement(["pending", "reviewing", "resolved", "dismissed"]),
            'user_id' => User::inRandomOrder()->first()->id ?? 1,
            'report_type_id' => ReportType::inRandomOrder()->first()->id ?? 1,
            "created_at" => $randomDate,
            "updated_at" => $randomDate,
        ];
    }
}
