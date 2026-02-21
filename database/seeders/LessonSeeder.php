<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Lesson;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $groups = Group::with('subject')->get();
        $teachers = Teacher::all();

        if ($groups->isEmpty() || $teachers->isEmpty()) {
            return;
        }

        $days = ['السبت', 'الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء'];

        foreach ($groups as $group) {
            $teacher = $teachers->random();

            foreach ($days as $index => $day) {
                Lesson::updateOrCreate(
                    [
                        'group_id' => $group->id,
                        'teacher_id' => $teacher->id,
                        'day' => $day,
                        'start_time' => sprintf('%02d:00', 15 + $index),
                    ],
                    [
                        'end_time' => sprintf('%02d:30', 15 + $index),
                        'room' => 'قاعة '.($group->id % 5 + 1),
                    ]
                );
            }
        }
    }
}

