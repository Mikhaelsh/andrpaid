<?php

namespace Database\Seeders;

use App\Models\PaperType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaperTypeSeeder extends Seeder
{
    public function run(): void
    {
        $paperTypes = [
            'journal_article'           => 'Journal Article',
            'conference_paper'          => 'Conference Paper',
            'undergraduate_thesis'      => 'Undergraduate Thesis',
            'master_thesis'             => 'Master Thesis',
            'doctoral_dissertation'     => 'Doctoral Dissertation',
        ];

        foreach($paperTypes as $id => $name) {
            PaperType::create([
                'name'=> $name,
                'paperTypeId' => $id
            ]);
        }
    }
}
