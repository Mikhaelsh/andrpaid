<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            'aceh' => 'Aceh',
            'sumatera_utara' => 'Sumatera Utara',
            'sumatera_barat' => 'Sumatera Barat',
            'riau' => 'Riau',
            'jambi' => 'Jambi',
            'sumatera_selatan' => 'Sumatera Selatan',
            'bengkulu' => 'Bengkulu',
            'lampung' => 'Lampung',
            'kepulauan_bangka_belitung' => 'Kepulauan Bangka Belitung',
            'kepulauan_riau' => 'Kepulauan Riau',
            'dki_jakarta' => 'DKI Jakarta',
            'jawa_barat' => 'Jawa Barat',
            'jawa_tengah' => 'Jawa Tengah',
            'di_yogyakarta' => 'DI Yogyakarta',
            'jawa_timur' => 'Jawa Timur',
            'banten' => 'Banten',
            'bali' => 'Bali',
            'nusa_tenggara_barat' => 'Nusa Tenggara Barat',
            'nusa_tenggara_timur' => 'Nusa Tenggara Timur',
            'kalimantan_barat' => 'Kalimantan Barat',
            'kalimantan_tengah' => 'Kalimantan Tengah',
            'kalimantan_selatan' => 'Kalimantan Selatan',
            'kalimantan_timur' => 'Kalimantan Timur',
            'kalimantan_utara' => 'Kalimantan Utara',
            'sulawesi_utara' => 'Sulawesi Utara',
            'sulawesi_tengah' => 'Sulawesi Tengah',
            'sulawesi_selatan' => 'Sulawesi Selatan',
            'sulawesi_tenggara' => 'Sulawesi Tenggara',
            'gorontalo' => 'Gorontalo',
            'sulawesi_barat' => 'Sulawesi Barat',
            'maluku' => 'Maluku',
            'maluku_utara' => 'Maluku Utara',
            'papua_barat' => 'Papua Barat',
            'papua' => 'Papua',
        ];

        foreach($provinces as $id => $name) {
            Province::create([
                'name' => $name,
                'provinceId' => $id
            ]);
        }
    }
}
