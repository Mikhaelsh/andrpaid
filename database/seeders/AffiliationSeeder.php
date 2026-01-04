<?php

namespace Database\Seeders;

use App\Models\Affiliation;
use App\Models\Lecturer;
use App\Models\University;
use App\Models\User;
use Illuminate\Database\Seeder;

class AffiliationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('name', 'BINUS University')->first();

        $univ = $user->university;

        for ($i = 0; $i < 40; $i++) {
            $user = User::factory()->create();

            $lecturer = Lecturer::factory()->create([
                'user_id' => $user->id,
                'province_id' => ($i % 34) + 1,
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
