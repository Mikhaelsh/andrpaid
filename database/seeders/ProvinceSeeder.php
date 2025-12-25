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
            'bali' => 'Bali',
            'banten' => 'Banten',
            'bengkulu' => 'Bengkulu',
            'di_yogyakarta' => 'DI Yogyakarta',
            'dki_jakarta' => 'DKI Jakarta',
            'gorontalo' => 'Gorontalo',
            'jambi' => 'Jambi',
            'jawa_barat' => 'Jawa Barat',
            'jawa_tengah' => 'Jawa Tengah',
            'jawa_timur' => 'Jawa Timur',
            'kalimantan_barat' => 'Kalimantan Barat',
            'kalimantan_selatan' => 'Kalimantan Selatan',
            'kalimantan_tengah' => 'Kalimantan Tengah',
            'kalimantan_timur' => 'Kalimantan Timur',
            'kalimantan_utara' => 'Kalimantan Utara',
            'kepulauan_bangka_belitung' => 'Kepulauan Bangka Belitung',
            'kepulauan_riau' => 'Kepulauan Riau',
            'lampung' => 'Lampung',
            'maluku' => 'Maluku',
            'maluku_utara' => 'Maluku Utara',
            'nusa_tenggara_barat' => 'Nusa Tenggara Barat',
            'nusa_tenggara_timur' => 'Nusa Tenggara Timur',
            'papua' => 'Papua',
            'papua_barat' => 'Papua Barat',
            'papua_barat_daya' => 'Papua Barat Daya',
            'papua_pegunungan' => 'Papua Pegunungan',
            'papua_selatan' => 'Papua Selatan',
            'papua_tengah' => 'Papua Tengah',
            'riau' => 'Riau',
            'sulawesi_barat' => 'Sulawesi Barat',
            'sulawesi_selatan' => 'Sulawesi Selatan',
            'sulawesi_tengah' => 'Sulawesi Tengah',
            'sulawesi_tenggara' => 'Sulawesi Tenggara',
            'sulawesi_utara' => 'Sulawesi Utara',
            'sumatera_barat' => 'Sumatera Barat',
            'sumatera_selatan' => 'Sumatera Selatan',
            'sumatera_utara' => 'Sumatera Utara',
        ];

        foreach($provinces as $id => $name) {
            Province::create([
                'name' => $name,
                'provinceId' => $id
            ]);
        }
    }
}
