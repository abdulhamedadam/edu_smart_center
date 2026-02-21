<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ملف الطالب - {{ $student->name }}</title>
    @include('admin.layout.styles')
    <style>
        body {
            background: radial-gradient(circle at top, #eef2ff, #f9fafb);
        }

        .public-hero {
            background: linear-gradient(135deg, #4f46e5, #0ea5e9);
            border-radius: 24px;
            padding: 24px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .public-hero::before {
            content: "";
            position: absolute;
            inset-inline-start: -40px;
            top: -40px;
            width: 160px;
            height: 160px;
            background: radial-gradient(circle, rgba(255,255,255,0.16), transparent 70%);
        }

        .public-avatar {
            width: 96px;
            height: 96px;
            border-radius: 999px;
            border: 3px solid rgba(255,255,255,0.6);
            object-fit: cover;
            box-shadow: 0 10px 25px rgba(15,23,42,0.35);
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            background: rgba(15,23,42,0.16);
            color: #e5e7eb;
        }

        .chip i {
            font-size: 14px;
        }

        .section-card {
            border-radius: 20px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 12px 30px rgba(15,23,42,0.04);
        }

        .section-card .card-header {
            border-bottom: 0;
            background: transparent;
        }

        .timeline-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: #4f46e5;
        }

        .timeline-line {
            position: absolute;
            inset-inline-start: 5px;
            top: 18px;
            bottom: -12px;
            width: 2px;
            background: #e5e7eb;
        }

        @media (max-width: 767.98px) {
            .public-hero {
                padding: 18px;
                border-radius: 18px;
            }

            .public-avatar {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>
<body>
    <div class="container py-4 py-md-5">
        <div class="mb-3 mb-md-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h4 mb-1">ملف الطالب</h1>
                <p class="text-muted mb-0">صفحة عامة يمكن لولي الأمر الاطلاع عليها</p>
            </div>
            <div class="d-none d-md-flex align-items-center gap-2">
                <span class="chip">
                    <i class="bi bi-shield-check"></i>
                    بيانات خاصة بالطالب
                </span>
            </div>
        </div>

        <div class="public-hero mb-4 mb-md-5">
            <div class="row align-items-center g-3 g-md-4 position-relative">
                <div class="col-12 col-md-auto text-center text-md-start">
                    @if($student->avatar_path)
                        <img src="{{ asset('storage/'.$student->avatar_path) }}" alt="{{ $student->name }}" class="public-avatar mb-2 mb-md-0">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=6366f1&color=fff" alt="{{ $student->name }}" class="public-avatar mb-2 mb-md-0">
                    @endif
                </div>
                <div class="col">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                        <div>
                            <h2 class="h4 mb-1">{{ $student->name }}</h2>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="chip">
                                    <i class="bi bi-hash"></i>
                                    كود الطالب: {{ $student->student_code }}
                                </span>
                                @if($student->grade)
                                    <span class="chip">
                                        <i class="bi bi-mortarboard"></i>
                                        المرحلة: {{ $student->grade->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="text-md-end">
                            <p class="mb-1 small text-indigo-100">بيانات ولي الأمر</p>
                            <div class="chip">
                                <i class="bi bi-person"></i>
                                {{ $student->parent_name }}
                                @if($student->parent_phone)
                                    <span class="mx-1">•</span>
                                    <i class="bi bi-telephone"></i>
                                    {{ $student->parent_phone }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card section-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-1">ملخص سريع</h5>
                        <p class="text-muted small mb-0">أهم الأرقام الخاصة بالطالب</p>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="stat-card primary h-100">
                                    <div class="stat-icon">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h3 class="mb-0">
                                            {{ $attendances->where('status', 'present')->count() }}
                                        </h3>
                                        <p class="mb-0">أيام حضور</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-card danger h-100">
                                    <div class="stat-icon">
                                        <i class="bi bi-calendar-x"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h3 class="mb-0">
                                            {{ $attendances->where('status', 'absent')->count() }}
                                        </h3>
                                        <p class="mb-0">أيام غياب (آخر 30 يوم)</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-card success h-100">
                                    <div class="stat-icon">
                                        <i class="bi bi-emoji-smile"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h3 class="mb-0">
                                            {{ $examResults->whereNotNull('mark')->count() }}
                                        </h3>
                                        <p class="mb-0">امتحانات مرصودة</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-card info h-100">
                                    <div class="stat-icon">
                                        <i class="bi bi-journal-check"></i>
                                    </div>
                                    <div class="stat-info">
                                        <h3 class="mb-0">
                                            {{ $homeworkSubmissions->count() }}
                                        </h3>
                                        <p class="mb-0">واجبات مسلَّمة</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card section-card">
                    <div class="card-header">
                        <h5 class="mb-1">الحضور في هذا الشهر</h5>
                        <p class="text-muted small mb-0">عدد أيام الغياب خلال الشهر الحالي</p>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="text-muted">أيام الغياب</span>
                            <span class="fw-bold">{{ $monthlyAbsence }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            @php
                                $absencePercent = min(100, $monthlyAbsence * 10);
                            @endphp
                            <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $absencePercent }}%;"></div>
                        </div>
                        <p class="small text-muted mt-2 mb-0">
                            كلما قل عدد أيام الغياب كان أداء الطالب أفضل.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card section-card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">آخر نتائج الامتحانات</h5>
                            <p class="text-muted small mb-0">أحدث الامتحانات المسجلة للطالب</p>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>الامتحان</th>
                                        <th>المادة</th>
                                        <th>التاريخ</th>
                                        <th class="text-center">الدرجة</th>
                                        <th class="text-center">النسبة</th>
                                        <th class="text-center">الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($examResults as $result)
                                        @php
                                            $exam = $result->exam;
                                            $mark = $result->mark;
                                            $percent = $exam->total_marks > 0 && $mark !== null
                                                ? round(($mark / $exam->total_marks) * 100, 1)
                                                : null;
                                        @endphp
                                        <tr>
                                            <td>{{ $exam->title }}</td>
                                            <td>
                                                {{ $exam->group?->subject?->name }}
                                                @if($exam->group)
                                                    <span class="text-muted small d-block">
                                                        {{ $exam->group->name }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $exam->date }}</td>
                                            <td class="text-center">
                                                {{ $mark !== null ? $mark.' / '.$exam->total_marks : '-' }}
                                            </td>
                                            <td class="text-center">
                                                @if($percent !== null)
                                                    {{ $percent }}%
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($mark === null)
                                                    <span class="badge bg-secondary">لم يرصد</span>
                                                @elseif($percent !== null && $percent >= 50)
                                                    <span class="badge bg-success">ناجح</span>
                                                @else
                                                    <span class="badge bg-danger">راسب</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                لا توجد نتائج امتحانات مسجلة حتى الآن
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card section-card h-100">
                            <div class="card-header">
                                <h5 class="mb-1">سجل الحضور (آخر 30 يوم)</h5>
                                <p class="text-muted small mb-0">أحدث سجلات الحضور والغياب</p>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive" style="max-height: 260px;">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>التاريخ</th>
                                                <th>المجموعة</th>
                                                <th class="text-center">الحالة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($attendances as $item)
                                                <tr>
                                                    <td>{{ $item->date }}</td>
                                                    <td>{{ $item->group?->name }}</td>
                                                    <td class="text-center">
                                                        @if($item->status === 'present')
                                                            <span class="badge bg-success">حاضر</span>
                                                        @else
                                                            <span class="badge bg-danger">غائب</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center py-3 text-muted">
                                                        لا توجد سجلات حضور حتى الآن
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card section-card h-100 mb-4 mb-md-3">
                            <div class="card-header">
                                <h5 class="mb-1">الاشتراكات والمدفوعات</h5>
                                <p class="text-muted small mb-0">أحدث الاشتراكات الخاصة بالطالب</p>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive" style="max-height: 260px;">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>المجموعة</th>
                                                <th class="text-center">المستحق</th>
                                                <th class="text-center">المدفوع</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($subscriptions as $subscription)
                                                <tr>
                                                    <td>
                                                        {{ $subscription->group?->name }}
                                                        @if($subscription->group?->subject)
                                                            <span class="text-muted small d-block">
                                                                {{ $subscription->group->subject->name }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        {{ number_format($subscription->amount, 2) }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ number_format($subscription->paid, 2) }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center py-3 text-muted">
                                                        لا توجد بيانات اشتراكات لعرضها
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="card section-card h-100">
                            <div class="card-header">
                                <h5 class="mb-1">الواجبات الأخيرة</h5>
                                <p class="text-muted small mb-0">أحدث الواجبات التي تم تسليمها</p>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive" style="max-height: 240px;">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>العنوان</th>
                                                <th class="text-center">الدرجة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($homeworkSubmissions as $submission)
                                                <tr>
                                                    <td>
                                                        {{ $submission->homework?->title }}
                                                        @if($submission->homework?->group?->subject)
                                                            <span class="text-muted small d-block">
                                                                {{ $submission->homework->group->subject->name }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $submission->grade !== null ? $submission->grade : '-' }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="text-center py-3 text-muted">
                                                        لا توجد واجبات مسلَّمة حتى الآن
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.layout.scripts')
</body>
</html>

