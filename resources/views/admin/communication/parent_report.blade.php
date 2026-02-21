@extends('admin.layout.master')

@section('title', 'تقرير ولي الأمر')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">تقرير ولي الأمر: {{ $parent->name }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.parents.index') }}">أولياء الأمور</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">تقرير ولي الأمر</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="mb-2">{{ $parent->name }}</h5>
                        <p class="mb-1"><strong>الهاتف:</strong> {{ $parent->phone ?: '-' }}</p>
                        <p class="mb-1"><strong>البريد:</strong> {{ $parent->email ?: '-' }}</p>
                        <p class="mb-0"><strong>عدد الأبناء المسجلين:</strong> {{ count($reports) }}</p>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">الشهر المختار</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.communication.parent_report', $parent) }}">
                            <div class="mb-2">
                                <label class="form-label">اختر شهر</label>
                                <input type="month" name="month" class="form-control" value="{{ $month }}">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                تحديث التقرير
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">آخر الإشعارات المرسلة</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($notifications as $notification)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <strong>{{ $notification->title }}</strong>
                                        <span class="text-muted small">
                                            {{ $notification->created_at?->format('Y-m-d H:i') }}
                                        </span>
                                    </div>
                                    <div class="small text-muted">
                                        النوع: {{ $notification->type }}
                                        @if($notification->student)
                                            | الطالب: {{ $notification->student->name }}
                                        @endif
                                    </div>
                                    <div class="mt-1">
                                        {{ \Illuminate\Support\Str::limit($notification->body, 120) }}
                                    </div>
                                </div>
                            @empty
                                <div class="p-3 text-center text-muted">
                                    لا توجد إشعارات مسجلة بعد
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                @forelse($reports as $report)
                    @php
                        $student = $report['student'];
                        $attendance = $report['attendance'];
                        $exams = $report['exams'];
                        $subscriptions = $report['subscriptions'];
                    @endphp
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">{{ $student->name }}</h5>
                                <div class="small text-muted">
                                    الكود: {{ $student->student_code }} |
                                    المرحلة: {{ $student->grade?->name }}
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <h6>الحضور في الشهر</h6>
                                    <p class="mb-1">
                                        إجمالي الحصص: {{ $attendance['total'] }}
                                    </p>
                                    <p class="mb-1 text-danger">
                                        أيام الغياب: {{ $attendance['absent'] }}
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <h6>الاشتراكات المفتوحة</h6>
                                    @if($subscriptions->isEmpty())
                                        <p class="text-muted mb-0">لا توجد مبالغ متبقية</p>
                                    @else
                                        <ul class="mb-0">
                                            @foreach($subscriptions as $sub)
                                                <li>
                                                    {{ $sub->group?->name }} -
                                                    متبقي:
                                                    {{ number_format($sub->remaining, 2) }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                                <div class="col-md-4">
                                    <h6>أحدث نتائج الامتحانات</h6>
                                    @if($exams->isEmpty())
                                        <p class="text-muted mb-0">لا توجد نتائج بعد</p>
                                    @else
                                        <ul class="mb-0">
                                            @foreach($exams as $res)
                                                <li>
                                                    {{ $res->exam?->title }}
                                                    ({{ $res->exam?->group?->subject?->name }}) -
                                                    {{ $res->mark }}/{{ $res->exam?->total_marks }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card">
                        <div class="card-body text-center text-muted">
                            لا توجد طلاب مرتبطة بهذا ولي الأمر
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

