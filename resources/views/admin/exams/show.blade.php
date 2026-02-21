@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">تفاصيل الامتحان</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.exams.index') }}">الامتحانات</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $exam->title }}</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.exams.edit', $exam) }}" class="btn btn-outline-primary btn-sm">
                    تعديل الامتحان
                </a>
                <form method="POST" action="{{ route('admin.exams.destroy', $exam) }}"
                      onsubmit="return confirm('هل أنت متأكد من حذف هذا الامتحان؟');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        حذف
                    </button>
                </form>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-4">
                @if(session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">بيانات الامتحان</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>العنوان:</strong> {{ $exam->title }}</p>
                        <p class="mb-2">
                            <strong>المجموعة:</strong> {{ $exam->group?->name }}
                            @if($exam->group?->subject)
                                <span class="text-muted d-block small">
                                    {{ $exam->group->subject->name }}
                                    @if($exam->group->grade)
                                        – {{ $exam->group->grade->name }}
                                    @endif
                                </span>
                            @endif
                        </p>
                        <p class="mb-2"><strong>تاريخ الامتحان:</strong> {{ $exam->date }}</p>
                        <p class="mb-2"><strong>الدرجة الكلية:</strong> {{ $exam->total_marks }}</p>
                        <p class="mb-0">
                            <strong>عدد الدرجات المسجلة:</strong> {{ $exam->results->whereNotNull('mark')->count() }}
                        </p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">ترتيب الأوائل</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الطالب</th>
                                        <th>الدرجة</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rankedResults->take(10) as $index => $result)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <a href="{{ route('admin.exams.student', $result->student_id) }}">
                                                    {{ $result->student?->name }}
                                                </a>
                                            </td>
                                            <td>{{ $result->mark }}</td>
                                            <td>
                                                @php
                                                    $percent = $exam->total_marks > 0
                                                        ? round(($result->mark / $exam->total_marks) * 100, 1)
                                                        : 0;
                                                @endphp
                                                {{ $percent }}%
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-3">
                                                لا توجد درجات مسجلة بعد
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">درجات الطلاب في الامتحان</h5>
                    </div>
                    <div class="card-body p-0">
                        <form method="POST" action="{{ route('admin.exams.marks', $exam) }}">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead>
                                        <tr>
                                            <th>الطالب</th>
                                            <th>الكود</th>
                                            <th style="width: 120px;">الدرجة</th>
                                            <th style="width: 120px;">النسبة %</th>
                                            <th style="width: 120px;">الحالة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $resultsByStudent = $exam->results->keyBy('student_id');
                                        @endphp
                                        @forelse($students as $student)
                                            @php
                                                $result = $resultsByStudent->get($student->id);
                                                $mark = $result?->mark;
                                                $percent = $exam->total_marks > 0 && $mark !== null
                                                    ? round(($mark / $exam->total_marks) * 100, 1)
                                                    : null;
                                            @endphp
                                            <tr>
                                                <td>{{ $student->name }}</td>
                                                <td>{{ $student->student_code }}</td>
                                                <td>
                                                    <input
                                                        type="number"
                                                        name="marks[{{ $student->id }}]"
                                                        min="0"
                                                        max="{{ $exam->total_marks }}"
                                                        value="{{ old('marks.'.$student->id, $mark) }}"
                                                        class="form-control form-control-sm text-center"
                                                    >
                                                </td>
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
                                                <td colspan="5" class="text-center py-4">
                                                    لا يوجد طلاب مرتبطون بهذه المرحلة بعد
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-3 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    حفظ جميع الدرجات
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

