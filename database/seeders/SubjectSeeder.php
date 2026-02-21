<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $grades = Grade::all();

        if ($grades->isEmpty()) {
            $grades = collect([
                Grade::firstOrCreate(['name' => 'الصف الأول']),
                Grade::firstOrCreate(['name' => 'الصف الثاني']),
                Grade::firstOrCreate(['name' => 'الصف الثالث']),
            ]);
        }

        $subjectsData = [
            'اللغة العربية' => 250,
            'اللغة الإنجليزية' => 250,
            'الرياضيات' => 300,
            'العلوم' => 280,
            'الدراسات الاجتماعية' => 220,
        ];

        foreach ($grades as $grade) {
            foreach ($subjectsData as $name => $fee) {
                Subject::updateOrCreate(
                    [
                        'grade_id' => $grade->id,
                        'name' => $name,
                    ],
                    [
                        'monthly_fee' => $fee,
                    ]
                );
            }
        }
    }
}

