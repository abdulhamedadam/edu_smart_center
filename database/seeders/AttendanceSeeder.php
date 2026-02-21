<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Group;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $groups = Group::all();
        $students = Student::with('grade')->get();

        if ($groups->isEmpty() || $students->isEmpty()) {
            return;
        }

        $startDate = Carbon::now()->subDays(10);

        foreach ($groups as $group) {
            $groupStudents = $students->where('grade_id', $group->grade_id)->take(10);

            foreach (range(0, 9) as $offset) {
                $date = (clone $startDate)->addDays($offset)->toDateString();

                foreach ($groupStudents as $student) {
                    $status = rand(1, 100) <= 85 ? 'present' : 'absent';

                    Attendance::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'group_id' => $group->id,
                            'date' => $date,
                        ],
                        [
                            'status' => $status,
                        ]
                    );
                }
            }
        }
    }
}

