<?php

namespace Database\Factories;

use App\Models\Lecturer;
use App\Models\Paper;
use App\Models\PaperType;
use App\Models\ResearchField;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Paper>
 */
class PaperFactory extends Factory
{
    public function configure(): static
    {
        return $this->afterCreating(function (Paper $paper) {
            // 1. Get 1 to 3 random Research Field IDs from the database
            $fields = ResearchField::inRandomOrder()->limit(rand(1, 3))->get();

            // 2. Attach them to the newly created paper
            if ($fields->isNotEmpty()) {
                $paper->researchFields()->attach($fields);
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomDate = $this->faker->dateTimeBetween('-14 days', 'now');

        return [
            'paperId' => $this->faker->uuid(),
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->paragraph(3),
            'visibility' => $this->faker->randomElement(['public', 'private']),
            'status' => $this->faker->randomElement(['draft', 'finalized']),
            'externalLink' => null,
            'filePath' => null,
            'originalFilename' => null,
            'openCollaboration' => $this->faker->randomElement([true, false]),
            'lecturer_id' => Lecturer::factory(),
            'paper_type_id' => PaperType::inRandomOrder()->first()->id ?? 1,
            'created_at' => $randomDate,
            'updated_at' => $randomDate,
        ];
    }
}
