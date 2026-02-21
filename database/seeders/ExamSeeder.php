<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $groups = Group::all();

        if ($groups->isEmpty()) {
            return;
        }

        foreach ($groups as $group) {
            foreach (['شهري', 'ترم'] as $type) {
                $title = 'امتحان '.$type.' - '.$group->name;

                Exam::updateOrCreate(
                    [
                        'group_id' => $group->id,
                        'title' => $title,
                    ],
                    [
                        'total_marks' => 50,
                        'date' => Carbon::now()->subDays(rand(1, 20))->toDateString(),
                    ]
                );
            }
        }
    }
}

