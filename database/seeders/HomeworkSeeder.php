<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\Homework;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HomeworkSeeder extends Seeder
{
    public function run(): void
    {
        $groups = Group::all();
        $teachers = Teacher::all();

        if ($groups->isEmpty() || $teachers->isEmpty()) {
            return;
        }

        foreach ($groups as $group) {
            $teacher = $teachers->random();

            foreach (range(1, 3) as $i) {
                Homework::updateOrCreate(
                    [
                        'group_id' => $group->id,
                        'teacher_id' => $teacher->id,
                        'title' => 'واجب '.$group->name.' رقم '.$i,
                    ],
                    [
                        'description' => 'أسئلة تدريبية للمجموعة '.$group->name.' رقم '.$i,
                        'due_date' => Carbon::now()->addDays($i)->toDateString(),
                    ]
                );
            }
        }
    }
}

