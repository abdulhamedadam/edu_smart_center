@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">تقرير نتيجة الطالب</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.exams.index') }}">الامتحانات</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $student->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">بيانات الطالب</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>الاسم:</strong> {{ $student->name }}</p>
                        <p class="mb-2"><strong>الكود:</strong> {{ $student->student_code }}</p>
                        <p class="mb-2">
                            <strong>المرحلة:</strong> {{ $student->grade?->name ?? '-' }}
                        </p>
                        <p class="mb-0">
                            <strong>عدد الامتحانات:</strong> {{ $results->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">نتائج الامتحانات</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>الامتحان</th>
                                        <th>المجموعة</th>
                                        <th>التاريخ</th>
                                        <th>الدرجة</th>
                                        <th>الدرجة الكلية</th>
                                        <th>النسبة %</th>
                                        <th>الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($results as $result)
                                        @php
                                            $exam = $result->exam;
                                            $mark = $result->mark;
                                            $percent = $exam->total_marks > 0 && $mark !== null
                                                ? round(($mark / $exam->total_marks) * 100, 1)
                                                : null;
                                        @endphp
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.exams.show', $exam) }}">
                                                    {{ $exam->title }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $exam->group?->name }}
                                                @if($exam->group?->subject)
                                                    <span class="text-muted small d-block">
                                                        {{ $exam->group->subject->name }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $exam->date }}</td>
                                            <td>{{ $mark !== null ? $mark : '-' }}</td>
                                            <td>{{ $exam->total_marks }}</td>
                                            <td>
                                                @if($percent !== null)
                                                    {{ $percent }}%
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
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
                                            <td colspan="7" class="text-center py-4">
                                                لا توجد نتائج امتحانات مسجلة لهذا الطالب بعد
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
@endsection

