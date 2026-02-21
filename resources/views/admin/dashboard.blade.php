@extends('admin.layout.master')

@section('title', 'لوحة التحكم')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title mb-1">لوحة التحكم</h1>
                <p class="text-muted mb-0">
                    مرحباً {{ auth()->user()->name ?? 'بك' }}، هذا ملخص سريع للنظام.
                </p>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card primary h-100">
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ number_format($totalStudents) }}</h3>
                        <p>إجمالي الطلاب</p>
                    </div>
                    <div class="stat-chart">
                        <span class="trend up">
                            <i class="bi bi-calendar-event"></i>
                            هذا الشهر
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card success h-100">
                    <div class="stat-icon">
                        <i class="bi bi-mortarboard"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ number_format($totalTeachers) }}</h3>
                        <p>المدرسون</p>
                    </div>
                    <div class="stat-chart">
                        <span class="trend up">
                            <i class="bi bi-people-fill"></i>
                            المجموعات النشطة
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card warning h-100">
                    <div class="stat-icon">
                        <i class="bi bi-collection-play"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ number_format($totalGroups) }}</h3>
                        <p>المجموعات</p>
                    </div>
                    <div class="stat-chart">
                        <span class="trend up">
                            <i class="bi bi-grid-1x2"></i>
                            لجميع المراحل
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="stat-card danger h-100">
                    <div class="stat-icon">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ number_format($monthlyIncome, 2) }}</h3>
                        <p>دخل الشهر الحالي</p>
                    </div>
                    <div class="stat-chart">
                        <span class="trend {{ $netMonthly >= 0 ? 'up' : 'down' }}">
                            <i class="bi bi-graph-up-arrow"></i>
                            صافي {{ number_format($netMonthly, 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="card chart-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">الدخل والمصروفات لآخر ٦ شهور</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="financeChart" height="280"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card chart-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">توزيع الطلاب على المراحل</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="studentsByGradeChart" height="280"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">الحضور اليوم</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>إجمالي التسجيلات</span>
                                <span>{{ $todayAttendanceTotal }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>حضور</span>
                                <span class="text-success">{{ $todayPresent }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>غياب</span>
                                <span class="text-danger">{{ $todayAbsent }}</span>
                            </div>
                        </div>
                        <canvas id="attendanceChart" height="220"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">أقرب الامتحانات وحالة الاشتراكات</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7">
                                <h6 class="mb-3">الامتحانات القادمة</h6>
                                @if($upcomingExams->isEmpty())
                                    <p class="text-muted mb-0">لا توجد امتحانات قريبة مسجلة.</p>
                                @else
                                    <ul class="list-unstyled mb-0">
                                        @foreach($upcomingExams as $exam)
                                            <li class="mb-2">
                                                <div class="d-flex justify-content-between">
                                                    <span>
                                                        {{ $exam->title }}
                                                        @if($exam->group?->subject)
                                                            - {{ $exam->group->subject->name }}
                                                        @endif
                                                    </span>
                                                    <span class="text-muted">
                                                        {{ $exam->date?->format('Y-m-d') }}
                                                    </span>
                                                </div>
                                                <div class="text-muted small">
                                                    {{ $exam->group?->name }}
                                                    @if($exam->group?->grade)
                                                        • {{ $exam->group->grade->name }}
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                            <div class="col-md-5">
                                <h6 class="mb-3">الاشتراكات المتأخرة</h6>
                                @if($overdueSubscriptionsCount === 0)
                                    <p class="text-success mb-0">لا توجد اشتراكات متأخرة حالياً.</p>
                                @else
                                    <p class="mb-2">
                                        يوجد
                                        <strong>{{ $overdueSubscriptionsCount }}</strong>
                                        اشتراكاً متأخراً عن السداد.
                                    </p>
                                    <a href="{{ route('admin.subscriptions.index', ['status' => 'overdue']) }}" class="btn btn-sm btn-outline-danger">
                                        عرض الاشتراكات المتأخرة
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const financeCtx = document.getElementById('financeChart');
        const studentsByGradeCtx = document.getElementById('studentsByGradeChart');
        const attendanceCtx = document.getElementById('attendanceChart');

        if (financeCtx) {
            const financeData = {
                labels: @json($chartMonths),
                datasets: [
                    {
                        label: 'الدخل',
                        data: @json($incomeSeries),
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34,197,94,0.12)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'المصروفات',
                        data: @json($expenseSeries),
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239,68,68,0.12)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            };

            new Chart(financeCtx, {
                type: 'line',
                data: financeData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                boxWidth: 12
                            }
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        if (studentsByGradeCtx) {
            const gradeLabels = @json($studentsByGrade->pluck('name'));
            const gradeData = @json($studentsByGrade->pluck('students_count'));

            new Chart(studentsByGradeCtx, {
                type: 'bar',
                data: {
                    labels: gradeLabels,
                    datasets: [
                        {
                            label: 'عدد الطلاب',
                            data: gradeData,
                            backgroundColor: '#6366f1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        }

        if (attendanceCtx) {
            new Chart(attendanceCtx, {
                type: 'doughnut',
                data: {
                    labels: ['حضور', 'غياب'],
                    datasets: [
                        {
                            data: [{{ $todayPresent }}, {{ $todayAbsent }}],
                            backgroundColor: ['#22c55e', '#ef4444']
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    cutout: '60%'
                }
            });
        }
    </script>
@endpush
