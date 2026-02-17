<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGroupRequest;
use App\Http\Requests\Admin\UpdateGroupRequest;
use App\Models\Group;
use App\Services\GradeService;
use App\Services\GroupService;
use App\Services\SubjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class GroupController extends Controller
{
    public function __construct(
        protected GroupService $service,
        protected GradeService $grades,
        protected SubjectService $subjects
    ) {
    }

    public function index(): View
    {
        $groups = $this->service->list();
        $grades = $this->grades->options();
        $subjects = $this->subjects->options();

        return view('admin.groups.index', compact('groups', 'grades', 'subjects'));
    }

    public function store(StoreGroupRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()
            ->route('admin.groups.index')
            ->with('status', 'تم إضافة المجموعة بنجاح');
    }

    public function edit(Group $group): View
    {
        $groups = $this->service->list();
        $grades = $this->grades->options();
        $subjects = $this->subjects->optionsForGrade($group->grade_id);

        return view('admin.groups.index', compact('groups', 'grades', 'subjects', 'group'));
    }

    public function update(UpdateGroupRequest $request, Group $group): RedirectResponse
    {
        $this->service->update($group, $request->validated());

        return redirect()
            ->route('admin.groups.index')
            ->with('status', 'تم تحديث المجموعة بنجاح');
    }

    public function destroy(Group $group): RedirectResponse
    {
        $this->service->delete($group);

        return redirect()
            ->route('admin.groups.index')
            ->with('status', 'تم حذف المجموعة بنجاح');
    }
}
