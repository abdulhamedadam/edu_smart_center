<?php

namespace Database\Seeders;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Student;
use Illuminate\Database\Seeder;

class ExamResultSeeder extends Seeder
{
    public function run(): void
    {
        $exams = Exam::with('group')->get();

        if ($exams->isEmpty()) {
            return;
        }

        $students = Student::all();

        foreach ($exams as $exam) {
            $examStudents = $students
                ->where('grade_id', $exam->group?->grade_id)
                ->take(15);

            foreach ($examStudents as $student) {
                ExamResult::updateOrCreate(
                    [
                        'exam_id' => $exam->id,
                        'student_id' => $student->id,
                    ],
                    [
                        'mark' => rand(20, $exam->total_marks),
                    ]
                );
            }
        }
    }
}

