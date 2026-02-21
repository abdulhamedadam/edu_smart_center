<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGradeRequest;
use App\Http\Requests\Admin\UpdateGradeRequest;
use App\Models\Grade;
use App\Services\GradeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function __construct(
        protected GradeService $service
    ) {
    }

    public function index(): View
    {
        $grades = $this->service->options();

        return view('admin.grades.index', compact('grades'));
    }

    public function store(StoreGradeRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()
            ->route('admin.grades.index')
            ->with('status', 'تم إضافة المرحلة بنجاح');
    }

    public function edit(Grade $grade): View
    {
        $grades = $this->service->options();

        return view('admin.grades.index', compact('grades', 'grade'));
    }

    public function update(UpdateGradeRequest $request, Grade $grade): RedirectResponse
    {
        $this->service->update($grade, $request->validated());

        return redirect()
            ->route('admin.grades.index')
            ->with('status', 'تم تحديث المرحلة بنجاح');
    }

    public function destroy(Grade $grade): RedirectResponse
    {
        $this->service->delete($grade);

        return redirect()
            ->route('admin.grades.index')
            ->with('status', 'تم حذف المرحلة بنجاح');
    }
}
