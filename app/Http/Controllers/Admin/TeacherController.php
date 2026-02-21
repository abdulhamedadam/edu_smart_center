<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTeacherRequest;
use App\Http\Requests\Admin\UpdateTeacherRequest;
use App\Models\Teacher;
use App\Services\GroupService;
use App\Services\SubjectService;
use App\Services\TeacherService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function __construct(
        protected TeacherService $teachers,
        protected SubjectService $subjects,
        protected GroupService $groups
    ) {
    }

    public function index(): View
    {
        $teachers = $this->teachers->all();

        return view('admin.teachers.index', compact('teachers'));
    }

    public function create(): View
    {
        $subjects = $this->subjects->options();
        $groups = $this->groups->list();

        return view('admin.teachers.create', compact('subjects', 'groups'));
    }

    public function store(StoreTeacherRequest $request): RedirectResponse
    {
        $teacher = $this->teachers->create($request->validated());

        return redirect()
            ->route('admin.teachers.show', $teacher)
            ->with('status', 'تم إضافة المدرس بنجاح');
    }

    public function show(Teacher $teacher): View
    {
        $teacher->load(['subjects.grade', 'groups']);

        return view('admin.teachers.show', compact('teacher'));
    }

    public function edit(Teacher $teacher): View
    {
        $subjects = $this->subjects->options();
        $groups = $this->groups->list();

        $teacher->load(['subjects', 'groups']);

        return view('admin.teachers.edit', compact('teacher', 'subjects', 'groups'));
    }

    public function update(UpdateTeacherRequest $request, Teacher $teacher): RedirectResponse
    {
        $this->teachers->update($teacher, $request->validated());

        return redirect()
            ->route('admin.teachers.show', $teacher)
            ->with('status', 'تم تحديث بيانات المدرس بنجاح');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        $this->teachers->delete($teacher);

        return redirect()
            ->route('admin.teachers.index')
            ->with('status', 'تم حذف المدرس بنجاح');
    }
}
