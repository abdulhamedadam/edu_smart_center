<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Homework;
use App\Models\HomeworkSubmission;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeworkController extends Controller
{
    public function index(): View
    {
        $homeworks = Homework::with(['group.subject.grade', 'teacher'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.homework.index', compact('homeworks'));
    }

    public function create(): View
    {
        $groups = Group::with(['subject', 'grade'])->orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();

        return view('admin.homework.create', compact('groups', 'teachers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'group_id' => ['required', 'exists:groups,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file' => ['nullable', 'file'],
            'due_date' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('homeworks', 'public');
        }

        $homework = Homework::create($data);

        return redirect()
            ->route('admin.homework.show', $homework)
            ->with('status', 'تم إنشاء الواجب بنجاح');
    }

    public function show(Homework $homework): View
    {
        $homework->load(['group.subject.grade', 'teacher', 'submissions.student']);

        $students = Student::where('grade_id', $homework->group->grade_id)
            ->orderBy('name')
            ->get();

        return view('admin.homework.show', compact('homework', 'students'));
    }

    public function edit(Homework $homework): View
    {
        $groups = Group::with(['subject', 'grade'])->orderBy('name')->get();
        $teachers = Teacher::orderBy('name')->get();

        return view('admin.homework.edit', compact('homework', 'groups', 'teachers'));
    }

    public function update(Request $request, Homework $homework): RedirectResponse
    {
        $data = $request->validate([
            'group_id' => ['required', 'exists:groups,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file' => ['nullable', 'file'],
            'due_date' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('homeworks', 'public');
        }

        $homework->update($data);

        return redirect()
            ->route('admin.homework.show', $homework)
            ->with('status', 'تم تحديث بيانات الواجب');
    }

    public function destroy(Homework $homework): RedirectResponse
    {
        $homework->delete();

        return redirect()
            ->route('admin.homework.index')
            ->with('status', 'تم حذف الواجب');
    }

    public function storeSubmission(Request $request, Homework $homework): RedirectResponse
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'file' => ['required', 'file'],
        ]);

        $filePath = $request->file('file')->store('homework_submissions', 'public');

        HomeworkSubmission::updateOrCreate(
            [
                'homework_id' => $homework->id,
                'student_id' => $data['student_id'],
            ],
            [
                'file' => $filePath,
            ]
        );

        return redirect()
            ->route('admin.homework.show', $homework)
            ->with('status', 'تم رفع حل الطالب بنجاح');
    }

    public function gradeSubmission(Request $request, HomeworkSubmission $submission): RedirectResponse
    {
        $data = $request->validate([
            'grade' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $submission->update([
            'grade' => $data['grade'],
        ]);

        return redirect()
            ->route('admin.homework.show', $submission->homework_id)
            ->with('status', 'تم تحديث درجة الواجب');
    }

    public function saveGrades(Request $request, Homework $homework): RedirectResponse
    {
        $data = $request->validate([
            'grades' => ['array'],
            'grades.*' => ['nullable', 'integer', 'min:0', 'max:100'],
        ]);

        $grades = $data['grades'] ?? [];

        foreach ($grades as $studentId => $grade) {
            if ($grade === null || $grade === '') {
                continue;
            }

            $submission = HomeworkSubmission::firstOrCreate(
                [
                    'homework_id' => $homework->id,
                    'student_id' => $studentId,
                ]
            );

            $submission->update([
                'grade' => $grade,
            ]);
        }

        return redirect()
            ->route('admin.homework.show', $homework)
            ->with('status', 'تم حفظ درجات الواجب');
    }
}
