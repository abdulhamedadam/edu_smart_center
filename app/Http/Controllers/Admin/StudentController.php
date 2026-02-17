<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudentRequest;
use App\Http\Requests\Admin\UpdateStudentRequest;
use App\Models\Student;
use App\Services\GradeService;
use App\Services\StudentParentService;
use App\Services\StudentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function __construct(
        protected StudentService $students,
        protected GradeService $grades,
        protected StudentParentService $parents
    ) {
    }

    public function index(): View
    {
        $students = $this->students->list();

        return view('admin.students.index', compact('students'));
    }

    public function create(): View
    {
        $grades = $this->grades->options();
        $parents = $this->parents->options();

        return view('admin.students.create', compact('grades', 'parents'));
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $this->students->create($request->validated());

        return redirect()
            ->route('admin.students.index')
            ->with('status', 'تم إضافة الطالب بنجاح');
    }

    public function edit(Student $student): View
    {
        $grades = $this->grades->options();
        $parents = $this->parents->options();

        return view('admin.students.edit', compact('student', 'grades', 'parents'));
    }

    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        $this->students->update($student, $request->validated());

        return redirect()
            ->route('admin.students.index')
            ->with('status', 'تم تحديث بيانات الطالب بنجاح');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $this->students->delete($student);

        return redirect()
            ->route('admin.students.index')
            ->with('status', 'تم حذف الطالب بنجاح');
    }
}
