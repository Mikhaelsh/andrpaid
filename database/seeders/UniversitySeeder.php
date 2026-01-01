<?php

namespace Database\Seeders;

use App\Models\Province;
use App\Models\University;
use App\Models\User; // Import User Model
use Illuminate\Database\Seeder;
use Illuminate\Support\Str; // Import Str for UUID and Slug

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $universities = [
            ['name' => 'Universitas Indonesia', 'province' => 'jawa_barat'],
            ['name' => 'Institut Teknologi Bandung', 'province' => 'jawa_barat'],
            ['name' => 'Institut Pertanian Bogor', 'province' => 'jawa_barat'],
            ['name' => 'Universitas Padjadjaran', 'province' => 'jawa_barat'],
            ['name' => 'Universitas Pendidikan Indonesia', 'province' => 'jawa_barat'],
            ['name' => 'Telkom University', 'province' => 'jawa_barat'],
            ['name' => 'BINUS University', 'province' => 'dki_jakarta'],
            ['name' => 'Universitas Gadjah Mada', 'province' => 'di_yogyakarta'],
            ['name' => 'Universitas Negeri Yogyakarta', 'province' => 'di_yogyakarta'],
            ['name' => 'Universitas Islam Indonesia', 'province' => 'di_yogyakarta'],
            ['name' => 'Universitas Muhammadiyah Yogyakarta', 'province' => 'di_yogyakarta'],
            ['name' => 'Universitas Diponegoro', 'province' => 'jawa_tengah'],
            ['name' => 'Universitas Sebelas Maret', 'province' => 'jawa_tengah'],
            ['name' => 'Universitas Airlangga', 'province' => 'jawa_timur'],
            ['name' => 'Institut Teknologi Sepuluh Nopember', 'province' => 'jawa_timur'],
            ['name' => 'Universitas Brawijaya', 'province' => 'jawa_timur'],
            ['name' => 'Universitas Negeri Malang', 'province' => 'jawa_timur'],
            ['name' => 'Universitas Sumatera Utara', 'province' => 'sumatera_utara'],
            ['name' => 'Universitas Andalas', 'province' => 'sumatera_barat'],
            ['name' => 'Universitas Sriwijaya', 'province' => 'sumatera_selatan'],
        ];

        foreach ($universities as $data) {
            $province = Province::where('provinceId', $data['province'])->first();

            if ($province) {
                $user = User::factory()->create([
                    'name' => $data['name'],
                    'email' => Str::slug($data['name']) . '@uni.ac.id',
                ]);

                University::create([
                    'user_id' => $user->id,
                    'province_id' => $province->id,
                ]);
            }
        }

        $user = User::where("name", "BINUS University")->first();

        $user->update([
            'password' => bcrypt("aa")
        ]);
    }
}
