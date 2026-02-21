<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ExamResult;
use App\Models\HomeworkSubmission;
use App\Models\Student;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\View\View;

class PublicStudentProfileController extends Controller
{
    public function show(string $token): View
    {
        $student = Student::with([
            'grade',
            'parent',
        ])->where('public_token', $token)->firstOrFail();

        $today = Carbon::today();
        $monthStart = $today->copy()->startOfMonth();

        $attendances = Attendance::with('group')
            ->where('student_id', $student->id)
            ->orderByDesc('date')
            ->limit(30)
            ->get();

        $monthlyAbsence = Attendance::where('student_id', $student->id)
            ->whereBetween('date', [$monthStart, $today])
            ->where('status', 'absent')
            ->count();

        $examResults = ExamResult::with(['exam.group.subject'])
            ->where('student_id', $student->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $subscriptions = Subscription::with(['group.subject'])
            ->where('student_id', $student->id)
            ->orderByDesc('due_date')
            ->limit(6)
            ->get();

        $homeworkSubmissions = HomeworkSubmission::with('homework.group.subject')
            ->where('student_id', $student->id)
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        return view('public.student-profile', compact(
            'student',
            'attendances',
            'monthlyAbsence',
            'examResults',
            'subscriptions',
            'homeworkSubmissions'
        ));
    }
}

