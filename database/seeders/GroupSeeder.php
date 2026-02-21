<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Group;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        $grades = Grade::all();

        if ($grades->isEmpty()) {
            return;
        }

        foreach ($grades as $grade) {
            $subjects = Subject::where('grade_id', $grade->id)->get();

            foreach ($subjects as $subject) {
                foreach (['A', 'B'] as $suffix) {
                    Group::updateOrCreate(
                        [
                            'grade_id' => $grade->id,
                            'subject_id' => $subject->id,
                            'name' => $subject->name.' - مجموعة '.$suffix,
                        ],
                        [
                            'capacity' => 30,
                            'monthly_fee' => $subject->monthly_fee ?? 300,
                        ]
                    );
                }
            }
        }
    }
}

