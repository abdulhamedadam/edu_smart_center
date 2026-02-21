<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Student;
use App\Models\StudentParent;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\Teacher;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = Carbon::today();
        $monthStart = $today->copy()->startOfMonth();
        $monthEnd = $today->copy()->endOfMonth();

        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalGroups = Group::count();
        $totalParents = StudentParent::count();

        $monthlyIncome = SubscriptionPayment::whereBetween('paid_at', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->sum('amount');

        $monthlyExpenses = Expense::whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->sum('amount');

        $netMonthly = $monthlyIncome - $monthlyExpenses;

        $todayAttendanceTotal = Attendance::whereDate('date', $today->toDateString())->count();
        $todayPresent = Attendance::whereDate('date', $today->toDateString())
            ->where('status', 'present')
            ->count();
        $todayAbsent = Attendance::whereDate('date', $today->toDateString())
            ->where('status', 'absent')
            ->count();

        $studentsByGrade = Grade::withCount('students')
            ->orderBy('name')
            ->get();

        $months = collect(range(5, 0))->map(function (int $i) {
            return Carbon::now()->subMonths($i);
        });

        $chartMonths = [];
        $incomeSeries = [];
        $expenseSeries = [];

        foreach ($months as $month) {
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $chartMonths[] = $month->format('Y-m');
            $incomeSeries[] = (float) SubscriptionPayment::whereBetween('paid_at', [$start->toDateString(), $end->toDateString()])
                ->sum('amount');
            $expenseSeries[] = (float) Expense::whereBetween('date', [$start->toDateString(), $end->toDateString()])
                ->sum('amount');
        }

        $upcomingExams = Exam::with(['group.subject.grade'])
            ->whereDate('date', '>=', $today->toDateString())
            ->orderBy('date')
            ->limit(5)
            ->get();

        $overdueSubscriptionsCount = Subscription::whereColumn('paid', '<', 'amount')
            ->whereNotNull('due_date')
            ->where('due_date', '<', $today->toDateString())
            ->count();

        return view('admin.dashboard', [
            'totalStudents' => $totalStudents,
            'totalTeachers' => $totalTeachers,
            'totalGroups' => $totalGroups,
            'totalParents' => $totalParents,
            'monthlyIncome' => $monthlyIncome,
            'monthlyExpenses' => $monthlyExpenses,
            'netMonthly' => $netMonthly,
            'todayAttendanceTotal' => $todayAttendanceTotal,
            'todayPresent' => $todayPresent,
            'todayAbsent' => $todayAbsent,
            'studentsByGrade' => $studentsByGrade,
            'chartMonths' => $chartMonths,
            'incomeSeries' => $incomeSeries,
            'expenseSeries' => $expenseSeries,
            'upcomingExams' => $upcomingExams,
            'overdueSubscriptionsCount' => $overdueSubscriptionsCount,
        ]);
    }
}

