<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Student;
use App\Models\StudentParent;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $grades = Grade::all();

        if ($grades->isEmpty()) {
            $grades = collect([
                Grade::firstOrCreate(['name' => 'الصف الأول']),
                Grade::firstOrCreate(['name' => 'الصف الثاني']),
            ]);
        }

        $parents = StudentParent::all();

        if ($parents->isEmpty()) {
            $parents = collect([
                StudentParent::create([
                    'name' => 'وليد أحمد',
                    'phone' => '01100000001',
                    'email' => 'parent1@example.com',
                    'relation' => 'والد',
                ]),
                StudentParent::create([
                    'name' => 'منى علي',
                    'phone' => '01100000002',
                    'email' => 'parent2@example.com',
                    'relation' => 'والدة',
                ]),
            ]);
        }

        foreach (range(1, 20) as $i) {
            $parent = $parents->random();
            $grade = $grades->random();

            Student::updateOrCreate(
                ['student_code' => 'STU'.str_pad((string) $i, 3, '0', STR_PAD_LEFT)],
                [
                    'name' => 'طالب '.$i,
                    'phone' => '0120000'.str_pad((string) $i, 4, '0', STR_PAD_LEFT),
                    'parent_id' => $parent->id,
                    'parent_name' => $parent->name,
                    'parent_phone' => $parent->phone,
                    'grade_id' => $grade->id,
                    'avatar_path' => null,
                ]
            );
        }
    }
}

