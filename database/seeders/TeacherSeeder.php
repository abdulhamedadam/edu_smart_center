<?php

namespace Database\Seeders;

use App\Models\Teacher;
use App\Models\Group;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = [
            [
                'name' => 'أحمد علي',
                'phone' => '01000000001',
                'specialization' => 'رياضيات',
                'salary_type' => 'شهري',
            ],
            [
                'name' => 'محمد حسن',
                'phone' => '01000000002',
                'specialization' => 'لغة عربية',
                'salary_type' => 'حصة',
            ],
            [
                'name' => 'سارة محمود',
                'phone' => '01000000003',
                'specialization' => 'لغة إنجليزية',
                'salary_type' => 'شهري',
            ],
            [
                'name' => 'محمود إبراهيم',
                'phone' => '01000000004',
                'specialization' => 'علوم',
                'salary_type' => 'حصة',
            ],
        ];

        foreach ($teachers as $data) {
            $teacher = Teacher::updateOrCreate(
                ['name' => $data['name'], 'phone' => $data['phone']],
                $data
            );

            $subjects = Subject::query()
                ->when($data['specialization'], function ($query, $spec) {
                    return $query->where('name', 'like', '%'.$spec.'%');
                })
                ->limit(3)
                ->get();

            if ($subjects->isEmpty()) {
                $subjects = Subject::inRandomOrder()->limit(3)->get();
            }

            foreach ($subjects as $subject) {
                $groups = Group::where('subject_id', $subject->id)->limit(2)->get();

                foreach ($groups as $group) {
                    $teacher->groups()->syncWithoutDetaching([$group->id => ['subject_id' => $subject->id]]);
                }
            }
        }
    }
}
