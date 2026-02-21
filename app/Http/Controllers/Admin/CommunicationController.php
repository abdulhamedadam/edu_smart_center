<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\Group;
use App\Models\Notification;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommunicationController extends Controller
{
    public function absenceNotifications(Request $request): View
    {
        $date = $request->query('date', Carbon::today()->toDateString());
        $groupId = $request->query('group_id');

        $groups = Group::with(['subject', 'grade'])->orderBy('name')->get();

        $query = Attendance::with(['student.parent', 'group.subject.grade'])
            ->whereDate('date', $date)
            ->where('status', 'absent');

        if ($groupId) {
            $query->where('group_id', $groupId);
        }

        $absences = $query->get();

        return view('admin.communication.absence', compact(
            'date',
            'groupId',
            'groups',
            'absences'
        ));
    }

    public function sendAbsenceNotifications(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'date' => ['required', 'date'],
            'group_id' => ['nullable', 'exists:groups,id'],
            'attendance_ids' => ['array'],
            'attendance_ids.*' => ['integer', 'exists:attendances,id'],
        ]);

        $attendanceIds = $data['attendance_ids'] ?? [];

        if (empty($attendanceIds)) {
            return back()->with('status', 'لم يتم اختيار أي غياب لإرسال إشعارات');
        }

        $attendances = Attendance::with(['student.parent', 'group.subject'])
            ->whereIn('id', $attendanceIds)
            ->get();

        foreach ($attendances as $attendance) {
            $student = $attendance->student;
            $parent = $student?->parent;

            if (! $student || ! $parent) {
                continue;
            }

            $title = 'إشعار غياب';
            $body = sprintf(
                'نبلغكم بغياب الطالب %s عن حصة %s بتاريخ %s.',
                $student->name,
                $attendance->group?->subject?->name ?? 'غير محددة',
                $attendance->date
            );

            Notification::create([
                'student_id' => $student->id,
                'parent_id' => $parent->id,
                'type' => 'absence',
                'title' => $title,
                'body' => $body,
                'meta' => [
                    'date' => $attendance->date,
                    'group_id' => $attendance->group_id,
                ],
                'sent_at' => now(),
            ]);
        }

        return back()->with('status', 'تم إنشاء إشعارات الغياب بنجاح (جاهزة للإرسال عبر SMS أو WhatsApp)');
    }

    public function examNotifications(Request $request): View
    {
        $groupId = $request->query('group_id');

        $groups = Group::with(['subject', 'grade'])->orderBy('name')->get();

        $examsQuery = Exam::with('group.subject.grade')
            ->orderBy('date', 'asc');

        if ($groupId) {
            $examsQuery->where('group_id', $groupId);
        }

        $exams = $examsQuery->get();

        return view('admin.communication.exams', compact(
            'groups',
            'groupId',
            'exams'
        ));
    }

    public function sendExamNotifications(Request $request, Exam $exam): RedirectResponse
    {
        $students = Student::whereHas('attendances', function ($query) use ($exam) {
            $query->where('group_id', $exam->group_id);
        })->with('parent')->get();

        foreach ($students as $student) {
            $parent = $student->parent;

            if (! $parent) {
                continue;
            }

            $title = 'إشعار امتحان';
            $body = sprintf(
                'يوجد امتحان %s لمادة %s يوم %s للطالب %s.',
                $exam->title,
                $exam->group?->subject?->name ?? '',
                $exam->date?->format('Y-m-d'),
                $student->name
            );

            Notification::create([
                'student_id' => $student->id,
                'parent_id' => $parent->id,
                'type' => 'exam',
                'title' => $title,
                'body' => $body,
                'meta' => [
                    'exam_id' => $exam->id,
                    'group_id' => $exam->group_id,
                ],
                'sent_at' => now(),
            ]);
        }

        return back()->with('status', 'تم إنشاء إشعارات الامتحان بنجاح');
    }

    public function broadcast(Request $request): View
    {
        $groups = Group::with(['subject', 'grade'])->orderBy('name')->get();

        return view('admin.communication.broadcast', compact('groups'));
    }

    public function sendBroadcast(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'scope' => ['required', 'in:all,grade,group'],
            'grade_id' => ['nullable', 'exists:grades,id'],
            'group_id' => ['nullable', 'exists:groups,id'],
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $studentsQuery = Student::with('parent');

        if ($data['scope'] === 'grade' && $data['grade_id']) {
            $studentsQuery->where('grade_id', $data['grade_id']);
        } elseif ($data['scope'] === 'group' && $data['group_id']) {
            $groupId = (int) $data['group_id'];

            $studentsQuery->whereHas('attendances', function ($query) use ($groupId) {
                $query->where('group_id', $groupId);
            });
        }

        $students = $studentsQuery->get();

        foreach ($students as $student) {
            $parent = $student->parent;

            if (! $parent) {
                continue;
            }

            Notification::create([
                'student_id' => $student->id,
                'parent_id' => $parent->id,
                'type' => 'broadcast',
                'title' => $data['title'],
                'body' => $data['body'],
                'meta' => [
                    'scope' => $data['scope'],
                ],
                'sent_at' => now(),
            ]);
        }

        return back()->with('status', 'تم إرسال الرسالة الجماعية (مسجلة في سجل الإشعارات)');
    }

    public function parentReport(StudentParent $parent, Request $request): View
    {
        $month = $request->query('month', Carbon::today()->format('Y-m'));

        $children = $parent->students()
            ->with(['grade', 'attendances', 'examResults.exam.group.subject', 'subscriptions'])
            ->get();

        $monthStart = Carbon::parse($month . '-01')->startOfMonth();
        $monthEnd = (clone $monthStart)->endOfMonth();

        $reports = [];

        foreach ($children as $child) {
            $attendances = $child->attendances()
                ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])
                ->get();

            $totalSessions = $attendances->count();
            $absentSessions = $attendances->where('status', 'absent')->count();

            $exams = $child->examResults()
                ->with('exam.group.subject')
                ->orderByDesc('created_at')
                ->limit(10)
                ->get();

            $openSubscriptions = $child->subscriptions()
                ->with('group.subject')
                ->whereColumn('paid', '<', 'amount')
                ->get();

            $reports[] = [
                'student' => $child,
                'attendance' => [
                    'total' => $totalSessions,
                    'absent' => $absentSessions,
                ],
                'exams' => $exams,
                'subscriptions' => $openSubscriptions,
            ];
        }

        $notifications = Notification::where('parent_id', $parent->id)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        return view('admin.communication.parent_report', compact(
            'parent',
            'reports',
            'month',
            'notifications'
        ));
    }
}

