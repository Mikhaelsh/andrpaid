<?php

namespace Database\Seeders;

use App\Models\ReportType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reportTypes = [
            'bug_report' => 'Report a Bug',
            'feature_request' => 'Request a Feature',
            'design_improvement' => 'Design / Look & Feel',
            'performance_issue' => 'Performance / Slow Loading',
            'account_issue' => 'Account Issue',
            'security' => 'Security Vulnerability',
            'content_correction' => 'Spelling / Content Correction',
            'other' => 'Other',
        ];

        foreach($reportTypes as $id => $name) {
            ReportType::create([
                'name' => $name,
                'reportTypeId' => $id
            ]);
        }
    }
}
