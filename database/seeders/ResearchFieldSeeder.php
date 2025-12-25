<?php

namespace Database\Seeders;

use App\Models\ResearchField;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResearchFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $researchFields = [
            'artificial_intelligence' => 'Artificial Intelligence',
            'bioinformatics' => 'Bioinformatics',
            'blockchain' => 'Blockchain & Distributed Ledger',
            'cloud_computing' => 'Cloud Computing',
            'graphics_multimedia' => 'Computer Graphics & Multimedia',
            'networking' => 'Computer Networks & Communications',
            'computer_vision' => 'Computer Vision',
            'cyber_security' => 'Cybersecurity & Cryptography',
            'data_science' => 'Data Science & Big Data',
            'human_computer_interaction' => 'Human-Computer Interaction',
            'information_systems' => 'Information Systems',
            'internet_of_things' => 'Internet of Things',
            'machine_learning' => 'Machine Learning',
            'natural_language_processing' => 'Natural Language Processing',
            'operating_systems' => 'Operating Systems',
            'robotics' => 'Robotics',
            'software_engineering' => 'Software Engineering',
        ];

        foreach($researchFields as $id => $name) {
            ResearchField::create([
                'name'=> $name,
                'researchFieldId' => $id
            ]);
        }
    }
}
