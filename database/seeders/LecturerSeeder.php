<?php

namespace Database\Seeders;

use App\Models\Lecturer;
use App\Models\Province;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LecturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name'=> "Niko Sutiono",
            'email'=> "nikosutiono11@gmail.com",
            'password' => bcrypt("aa"),
            'description'=> "I am atomic"
        ]);

        $province = Province::where('provinceId', "banten" )->first();

        Lecturer::create([
            "user_id"=> $user->id,
            "province_id" => $province->id
        ]);
    }
}
