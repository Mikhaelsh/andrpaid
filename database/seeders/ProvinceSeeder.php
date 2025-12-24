<?php

namespace Database\Seeders;

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
            'aceh', 'sumatera_utara', 'sumatera_barat', 'riau', 'kepulauan_riau',
            'jambi', 'sumatera_selatan', 'kepulauan_bangka_belitung', 'bengkulu', 'lampung',
            'dki_jakarta', 'jawa_barat', 'banten', 'jawa_tengah', 'di_yogyakarta', 'jawa_timur',
            'bali', 'nusa_tenggara_barat', 'nusa_tenggara_timur',
            'kalimantan_barat', 'kalimantan_tengah', 'kalimantan_selatan', 'kalimantan_timur', 'kalimantan_utara',
            'sulawesi_utara', 'sulawesi_tengah', 'sulawesi_selatan', 'sulawesi_tenggara', 'gorontalo', 'sulawesi_barat',
            'maluku', 'maluku_utara', 'papua', 'papua_barat', 'papua_tengah', 'papua_pegunungan', 'papua_selatan', 'papua_barat_daya'
        ];

        foreach($provinces as $province) {
            DB::table('provinces')->insert([
                'name' => $province
            ]);
        }
    }
}
