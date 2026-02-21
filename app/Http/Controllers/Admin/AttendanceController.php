<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Group;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $groupId = $request->query('group_id');
        $date = $request->query('date', Carbon::today()->toDateString());

        $groups = Group::orderBy('name')->get();

        $attendances = collect();
        $selectedGroup = null;

        if ($groupId) {
            $selectedGroup = $groups->firstWhere('id', (int) $groupId);

            $attendances = Attendance::with('student')
                ->where('group_id', $groupId)
                ->whereDate('date', $date)
                ->orderBy('student_id')
                ->get();
        }

        return view('admin.attendance.index', compact(
            'groups',
            'attendances',
            'selectedGroup',
            'groupId',
            'date'
        ));
    }

    public function student(Student $student, Request $request): View
    {
        $month = $request->query('month');

        $query = Attendance::where('student_id', $student->id)->with('group');

        if ($month) {
            $start = Carbon::parse($month . '-01')->startOfMonth();
            $end = (clone $start)->endOfMonth();
            $query->whereBetween('date', [$start->toDateString(), $end->toDateString()]);
        }

        $attendances = $query
            ->orderBy('date', 'desc')
            ->get();

        $monthlyAbsences = Attendance::where('student_id', $student->id)
            ->where('status', 'absent')
            ->selectRaw('DATE_FORMAT(date, "%Y-%m") as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->get();

        return view('admin.attendance.student', compact(
            'student',
            'attendances',
            'monthlyAbsences',
            'month'
        ));
    }

    public function scan(Request $request): View
    {
        $groups = Group::orderBy('name')->get();
        $groupId = $request->query('group_id');
        $date = $request->query('date', Carbon::today()->toDateString());

        return view('admin.attendance.scan', compact('groups', 'groupId', 'date'));
    }

    public function scanStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'group_id' => ['required', 'exists:groups,id'],
            'payload' => ['required', 'string'],
            'date' => ['nullable', 'date'],
        ]);

        $payload = $data['payload'];

        $decoded = json_decode($payload, true);
        $code = is_array($decoded) && isset($decoded['code'])
            ? $decoded['code']
            : trim($payload);

        $student = Student::where('student_code', $code)->first();

        if (!$student) {
            return back()
                ->withInput()
                ->with('error', 'لم يتم العثور على طالب بهذا الكود');
        }

        $date = $data['date'] ?? Carbon::today()->toDateString();

        Attendance::updateOrCreate(
            [
                'student_id' => $student->id,
                'group_id' => $data['group_id'],
                'date' => $date,
            ],
            [
                'status' => 'present',
            ]
        );

        return back()
            ->with('status', 'تم تسجيل حضور ' . $student->name)
            ->withInput(['group_id' => $data['group_id'], 'date' => $date]);
    }

    public function toggle(Attendance $attendance, Request $request): RedirectResponse
    {
        $attendance->update([
            'status' => $attendance->status === 'present' ? 'absent' : 'present',
        ]);

        $params = [
            'group_id' => $request->input('group_id'),
            'date' => $request->input('date'),
        ];

        return redirect()
            ->route('admin.attendance.index', $params)
            ->with('status', 'تم تحديث حالة الحضور');
    }

    public function storeAbsence(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'group_id' => ['required', 'exists:groups,id'],
            'date' => ['required', 'date'],
            'student_code' => ['required', 'string'],
        ]);

        $student = Student::where('student_code', trim($data['student_code']))->first();

        if (!$student) {
            return back()
                ->withInput()
                ->with('error', 'لم يتم العثور على طالب بهذا الكود');
        }

        Attendance::updateOrCreate(
            [
                'student_id' => $student->id,
                'group_id' => $data['group_id'],
                'date' => $data['date'],
            ],
            [
                'status' => 'absent',
            ]
        );

        return back()
            ->with('status', 'تم تسجيل غياب ' . $student->name)
            ->withInput(['group_id' => $data['group_id'], 'date' => $data['date']]);
    }
}
