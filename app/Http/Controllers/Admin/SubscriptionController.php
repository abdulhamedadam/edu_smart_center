<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Student;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->get('status');
        $groupId = $request->get('group_id');

        $query = Subscription::with(['student.grade', 'group.subject.grade'])
            ->orderBy('due_date', 'asc');

        if ($groupId) {
            $query->where('group_id', $groupId);
        }

        if ($status === 'overdue') {
            $query->where('due_date', '<', today())
                ->whereColumn('paid', '<', 'amount');
        }

        $subscriptions = $query->get();

        $groups = Group::with(['subject', 'grade'])
            ->orderBy('name')
            ->get();

        return view('admin.subscriptions.index', compact('subscriptions', 'groups', 'status', 'groupId'));
    }

    public function create(): View
    {
        $students = Student::with('grade')->orderBy('name')->get();
        $groups = Group::with(['subject', 'grade'])->orderBy('name')->get();

        return view('admin.subscriptions.create', compact('students', 'groups'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'group_id' => ['required', 'exists:groups,id'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'paid' => ['nullable', 'numeric', 'min:0'],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
        ]);

        $studentId = (int) $data['student_id'];
        $groupId = (int) $data['group_id'];
        $month = (int) $data['month'];
        $year = (int) $data['year'];

        $group = Group::with('subject')->findOrFail($groupId);

        $amount = $data['amount']
            ?? $group->monthly_fee
            ?? $group->subject?->monthly_fee
            ?? 0;
        $paid = $data['paid'] ?? 0;

        $dueDate = Carbon::create($year, $month, 5)->toDateString();

        $subscription = Subscription::create([
            'student_id' => $studentId,
            'group_id' => $groupId,
            'amount' => $amount,
            'paid' => $paid,
            'due_date' => $dueDate,
        ]);

        if ($paid > 0) {
            SubscriptionPayment::create([
                'subscription_id' => $subscription->id,
                'amount' => $paid,
                'paid_at' => now()->toDateString(),
            ]);
        }

        return redirect()
            ->route('admin.subscriptions.index')
            ->with('status', 'تم إنشاء الاشتراك بنجاح');
    }

    public function edit(Subscription $subscription): View
    {
        $students = Student::with('grade')->orderBy('name')->get();
        $groups = Group::with(['subject', 'grade'])->orderBy('name')->get();

        return view('admin.subscriptions.edit', compact('subscription', 'students', 'groups'));
    }

    public function update(Request $request, Subscription $subscription): RedirectResponse
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'group_id' => ['required', 'exists:groups,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'due_date' => ['nullable', 'date'],
        ]);

        $subscription->update($data);

        return redirect()
            ->route('admin.subscriptions.index')
            ->with('status', 'تم تحديث بيانات الاشتراك');
    }

    public function destroy(Subscription $subscription): RedirectResponse
    {
        $subscription->delete();

        return redirect()
            ->route('admin.subscriptions.index')
            ->with('status', 'تم حذف الاشتراك');
    }

    public function pay(Request $request, Subscription $subscription): RedirectResponse
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'paid_at' => ['nullable', 'date'],
        ]);

        $amount = (float) $data['amount'];

        $maxPayable = max($subscription->amount - $subscription->paid, 0);

        if ($amount > $maxPayable) {
            return redirect()
                ->back()
                ->withErrors(['amount' => 'قيمة الدفعة أكبر من المبلغ المتبقي'])
                ->withInput();
        }

        $subscription->update([
            'paid' => $subscription->paid + $amount,
        ]);

        SubscriptionPayment::create([
            'subscription_id' => $subscription->id,
            'amount' => $amount,
            'paid_at' => $data['paid_at'] ?? now()->toDateString(),
        ]);

        return redirect()
            ->route('admin.subscriptions.index', $request->only(['status', 'group_id']))
            ->with('status', 'تم تسجيل الدفعة بنجاح');
    }

    public function monthlyIncome(Request $request): View
    {
        $month = (int) ($request->get('month') ?: now()->month);
        $year = (int) ($request->get('year') ?: now()->year);

        $start = Carbon::create($year, $month, 1)->startOfDay();
        $end = (clone $start)->endOfMonth();

        $paymentsQuery = SubscriptionPayment::with('subscription.group.subject.grade')
            ->whereBetween('paid_at', [$start->toDateString(), $end->toDateString()]);

        $totalIncome = $paymentsQuery->sum('amount');

        $groupIncome = $paymentsQuery->get()
            ->groupBy(fn(SubscriptionPayment $payment) => $payment->subscription?->group?->id)
            ->map(function ($payments) {
                $first = $payments->first();

                return [
                    'group' => $first->subscription?->group,
                    'amount' => $payments->sum('amount'),
                ];
            })
            ->values();

        $expensesQuery = \App\Models\Expense::whereBetween('date', [$start->toDateString(), $end->toDateString()]);

        $totalExpenses = $expensesQuery->sum('amount');

        $categoryExpenses = $expensesQuery->with('category')
            ->get()
            ->groupBy('expense_category_id')
            ->map(function ($items) {
                $first = $items->first();

                return [
                    'category' => $first->category,
                    'amount' => $items->sum('amount'),
                ];
            })
            ->values();

        $net = $totalIncome - $totalExpenses;

        return view('admin.subscriptions.monthly_income', compact(
            'month',
            'year',
            'totalIncome',
            'groupIncome',
            'totalExpenses',
            'categoryExpenses',
            'net'
        ));
    }

    public function teacherCommission(Request $request): View
    {
        $month = (int) ($request->get('month') ?: now()->month);
        $year = (int) ($request->get('year') ?: now()->year);

        $start = Carbon::create($year, $month, 1)->startOfDay();
        $end = (clone $start)->endOfMonth();

        $payments = SubscriptionPayment::with(['subscription.group', 'subscription.group.subject', 'subscription.group.grade'])
            ->whereBetween('paid_at', [$start->toDateString(), $end->toDateString()])
            ->get();

        $teachers = Teacher::with(['subjects', 'groups'])->get();

        $teacherData = $teachers->map(function (Teacher $teacher) use ($payments) {
            if (!$teacher->commission_rate) {
                return [
                    'teacher' => $teacher,
                    'total_amount' => 0,
                    'commission' => 0,
                ];
            }

            $groupIds = $teacher->groups->pluck('id')->all();

            $teacherAmount = $payments
                ->filter(function (SubscriptionPayment $payment) use ($groupIds) {
                    return in_array($payment->subscription?->group_id, $groupIds, true);
                })
                ->sum('amount');

            $commission = $teacherAmount * ($teacher->commission_rate / 100);

            return [
                'teacher' => $teacher,
                'total_amount' => $teacherAmount,
                'commission' => $commission,
            ];
        })->filter(function (array $row) {
            return $row['total_amount'] > 0;
        })->values();

        return view('admin.subscriptions.teacher_commission', compact('month', 'year', 'teacherData'));
    }
}
