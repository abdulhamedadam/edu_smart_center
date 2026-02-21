<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(Request $request): View
    {
        $categoryId = $request->get('category_id');
        $month = $request->get('month');
        $year = $request->get('year');

        $query = Expense::with('category')->orderByDesc('date');

        if ($categoryId) {
            $query->where('expense_category_id', $categoryId);
        }

        if ($month && $year) {
            $start = Carbon::create((int) $year, (int) $month, 1)->startOfDay();
            $end = (clone $start)->endOfMonth();
            $query->whereBetween('date', [$start->toDateString(), $end->toDateString()]);
        }

        $expenses = $query->get();

        $categories = ExpenseCategory::orderBy('name')->get();

        $total = (clone $query)->sum('amount');

        return view('admin.expenses.index', compact('expenses', 'categories', 'categoryId', 'month', 'year', 'total'));
    }

    public function create(): View
    {
        $categories = ExpenseCategory::orderBy('name')->get();

        return view('admin.expenses.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'expense_category_id' => ['required', 'exists:expense_categories,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        Expense::create($data);

        return redirect()
            ->route('admin.expenses.index')
            ->with('status', 'تم تسجيل المصروف بنجاح');
    }

    public function edit(Expense $expense): View
    {
        $categories = ExpenseCategory::orderBy('name')->get();

        return view('admin.expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense): RedirectResponse
    {
        $data = $request->validate([
            'expense_category_id' => ['required', 'exists:expense_categories,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ]);

        $expense->update($data);

        return redirect()
            ->route('admin.expenses.index')
            ->with('status', 'تم تحديث بيانات المصروف');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return redirect()
            ->route('admin.expenses.index')
            ->with('status', 'تم حذف المصروف');
    }
}
