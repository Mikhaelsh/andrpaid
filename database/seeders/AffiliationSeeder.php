<?php

namespace Database\Seeders;

use App\Models\Affiliation;
use App\Models\Lecturer;
use App\Models\University;
use App\Models\User;
use Illuminate\Database\Seeder;

class AffiliationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('name', 'BINUS University')->first();

        $univ = $user->university;

        for ($i = 0; $i < 30; $i++) {
            $user = User::factory()->create();

            $lecturer = Lecturer::factory()->create([
                'user_id' => $user->id,
            ]);

            Affiliation::create([
                'university_id' => $univ->id,
                'lecturer_id'   => $lecturer->id,
                'status'        => 'verified',
                'nidn'          => fake()->unique()->numerify('##########'),
            ]);
        }
    }
}
