<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLessonRequest;
use App\Models\Group;
use App\Models\Lesson;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function index(Request $request): View
    {
        $groupId = $request->query('group_id');
        $teacherId = $request->query('teacher_id');

        $query = Lesson::with(['group.subject.grade', 'teacher']);

        if ($groupId) {
            $query->where('group_id', $groupId);
        }

        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }

        $lessons = $query
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $groups = Group::with('subject')->orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();

        $selectedGroup = $groupId ? $groups->firstWhere('id', (int) $groupId) : null;
        $selectedTeacher = $teacherId ? $teachers->firstWhere('id', (int) $teacherId) : null;

        $days = [
            'saturday' => 'السبت',
            'sunday' => 'الأحد',
            'monday' => 'الاثنين',
            'tuesday' => 'الثلاثاء',
            'wednesday' => 'الأربعاء',
            'thursday' => 'الخميس',
            'friday' => 'الجمعة',
        ];

        return view('admin.lessons.index', compact(
            'lessons',
            'groups',
            'teachers',
            'selectedGroup',
            'selectedTeacher',
            'groupId',
            'teacherId',
            'days'
        ));
    }

    public function store(StoreLessonRequest $request)
    {
        Lesson::create($request->validated());

        return redirect()
            ->route('admin.schedule.index', $request->only('group_id', 'teacher_id'))
            ->with('status', 'تم إضافة الحصة بنجاح');
    }
}
