<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Group;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExamController extends Controller
{
    public function index(): View
    {
        $exams = Exam::with(['group.subject.grade'])
            ->withCount('results')
            ->orderBy('date', 'desc')
            ->get();

        return view('admin.exams.index', compact('exams'));
    }

    public function create(): View
    {
        $groups = Group::with(['subject', 'grade'])->orderBy('name')->get();

        return view('admin.exams.create', compact('groups'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'group_id' => ['required', 'exists:groups,id'],
            'title' => ['required', 'string', 'max:255'],
            'total_marks' => ['required', 'integer', 'min:1', 'max:1000'],
            'date' => ['required', 'date'],
        ]);

        $exam = Exam::create($data);

        return redirect()
            ->route('admin.exams.show', $exam)
            ->with('status', 'تم إنشاء الامتحان بنجاح');
    }

    public function show(Exam $exam): View
    {
        $exam->load(['group.subject.grade', 'results.student']);

        $students = Student::where('grade_id', $exam->group->grade_id)
            ->orderBy('name')
            ->get();

        $rankedResults = $exam->results
            ->filter(function (ExamResult $result) {
                return $result->mark !== null;
            })
            ->sortByDesc('mark')
            ->values();

        return view('admin.exams.show', compact('exam', 'students', 'rankedResults'));
    }

    public function edit(Exam $exam): View
    {
        $groups = Group::with(['subject', 'grade'])->orderBy('name')->get();

        return view('admin.exams.edit', compact('exam', 'groups'));
    }

    public function update(Request $request, Exam $exam): RedirectResponse
    {
        $data = $request->validate([
            'group_id' => ['required', 'exists:groups,id'],
            'title' => ['required', 'string', 'max:255'],
            'total_marks' => ['required', 'integer', 'min:1', 'max:1000'],
            'date' => ['required', 'date'],
        ]);

        $exam->update($data);

        return redirect()
            ->route('admin.exams.show', $exam)
            ->with('status', 'تم تحديث بيانات الامتحان');
    }

    public function destroy(Exam $exam): RedirectResponse
    {
        $exam->delete();

        return redirect()
            ->route('admin.exams.index')
            ->with('status', 'تم حذف الامتحان');
    }

    public function saveMarks(Request $request, Exam $exam): RedirectResponse
    {
        $data = $request->validate([
            'marks' => ['array'],
            'marks.*' => ['nullable', 'integer', 'min:0', 'max:' . $exam->total_marks],
        ]);

        $marks = $data['marks'] ?? [];

        foreach ($marks as $studentId => $mark) {
            if ($mark === null || $mark === '') {
                continue;
            }

            $result = ExamResult::firstOrCreate(
                [
                    'exam_id' => $exam->id,
                    'student_id' => $studentId,
                ]
            );

            $result->update([
                'mark' => $mark,
            ]);
        }

        return redirect()
            ->route('admin.exams.show', $exam)
            ->with('status', 'تم حفظ درجات الامتحان');
    }

    public function student(Student $student): View
    {
        $results = ExamResult::with(['exam.group.subject', 'exam.group.grade'])
            ->where('student_id', $student->id)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.exams.student', compact('student', 'results'));
    }
}
