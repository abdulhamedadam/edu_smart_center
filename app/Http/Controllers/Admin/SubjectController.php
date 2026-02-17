<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSubjectRequest;
use App\Http\Requests\Admin\UpdateSubjectRequest;
use App\Models\Grade;
use App\Models\Subject;
use App\Services\GradeService;
use App\Services\SubjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function __construct(
        protected SubjectService $service,
        protected GradeService $grades
    ) {
    }

    public function index(): View
    {
        $subjects = $this->service->list();
        $grades = $this->grades->options();

        return view('admin.subjects.index', compact('subjects', 'grades'));
    }

    public function store(StoreSubjectRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()
            ->route('admin.subjects.index')
            ->with('status', 'تم إضافة المادة بنجاح');
    }

    public function edit(Subject $subject): View
    {
        $subjects = $this->service->list();
        $grades = $this->grades->options();

        return view('admin.subjects.index', compact('subjects', 'grades', 'subject'));
    }

    public function update(UpdateSubjectRequest $request, Subject $subject): RedirectResponse
    {
        $this->service->update($subject, $request->validated());

        return redirect()
            ->route('admin.subjects.index')
            ->with('status', 'تم تحديث المادة بنجاح');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        $this->service->delete($subject);

        return redirect()
            ->route('admin.subjects.index')
            ->with('status', 'تم حذف المادة بنجاح');
    }

    public function forGrade(Grade $grade): JsonResponse
    {
        $subjects = $this->service
            ->optionsForGrade($grade->id)
            ->map(function (Subject $subject) {
                return [
                    'id' => $subject->id,
                    'name' => $subject->name,
                ];
            });

        return response()->json($subjects);
    }
}
