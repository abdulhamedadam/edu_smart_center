<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentParent;
use App\Services\StudentParentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentParentController extends Controller
{
    public function __construct(
        protected StudentParentService $parents
    ) {
    }

    public function index(): View
    {
        $parents = $this->parents->all();

        return view('admin.parents.index', compact('parents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email'],
            'relation' => ['nullable', 'string', 'max:100'],
        ]);

        $this->parents->create($data);

        return back()->with('status', 'تم إضافة ولي الأمر بنجاح');
    }

    public function destroy(StudentParent $parent): RedirectResponse
    {
        $this->parents->delete($parent);

        return back()->with('status', 'تم حذف ولي الأمر بنجاح');
    }
}
