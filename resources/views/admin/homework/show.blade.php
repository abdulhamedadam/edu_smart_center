@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">تفاصيل الواجب</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.homework.index') }}">الواجبات</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $homework->title }}</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.homework.edit', $homework) }}" class="btn btn-outline-primary btn-sm">
                    تعديل الواجب
                </a>
                <form method="POST" action="{{ route('admin.homework.destroy', $homework) }}"
                      onsubmit="return confirm('هل أنت متأكد من حذف هذا الواجب؟');">
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
                        <h5 class="mb-0">بيانات الواجب</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>العنوان:</strong> {{ $homework->title }}</p>
                        <p class="mb-2">
                            <strong>المجموعة:</strong> {{ $homework->group?->name }}
                            @if($homework->group?->subject)
                                <span class="text-muted d-block small">
                                    {{ $homework->group->subject->name }}
                                    @if($homework->group->grade)
                                        – {{ $homework->group->grade->name }}
                                    @endif
                                </span>
                            @endif
                        </p>
                        <p class="mb-2"><strong>المدرس:</strong> {{ $homework->teacher?->name }}</p>
                        <p class="mb-2"><strong>تاريخ التسليم:</strong> {{ $homework->due_date ?: '-' }}</p>
                        <p class="mb-3">
                            <strong>ملف الواجب:</strong>
                            @if($homework->file)
                                <a href="{{ asset('storage/'.$homework->file) }}" target="_blank">
                                    تحميل الملف
                                </a>
                            @else
                                <span class="text-muted">لا يوجد ملف</span>
                            @endif
                        </p>
                        <p class="mb-0"><strong>الوصف:</strong></p>
                        <p class="mb-0">{{ $homework->description ?: '-' }}</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">رفع حل طالب</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.homework.submit', $homework) }}" enctype="multipart/form-data" class="row g-3">
                            @csrf

                            <div class="col-12">
                                <label class="form-label">الطالب</label>
                                <select name="student_id" class="form-select @error('student_id') is-invalid @enderror">
                                    <option value="">اختر الطالب</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>
                                            {{ $student->name }} ({{ $student->student_code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">ملف الحل</label>
                                <input type="file" name="file" class="form-control @error('file') is-invalid @enderror">
                                @error('file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-success">
                                    رفع الحل
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">نتيجة الواجب للطلاب</h5>
                    </div>
                    <div class="card-body p-0">
                        <form method="POST" action="{{ route('admin.homework.grades', $homework) }}">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>الطالب</th>
                                            <th>الكود</th>
                                            <th>ملف الحل</th>
                                            <th>التسليم في</th>
                                            <th style="width: 120px;">الدرجة</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $submissionsByStudent = $homework->submissions->keyBy('student_id');
                                        @endphp
                                        @forelse($students as $student)
                                            @php
                                                $submission = $submissionsByStudent->get($student->id);
                                            @endphp
                                            <tr>
                                                <td>{{ $student->name }}</td>
                                                <td>{{ $student->student_code }}</td>
                                                <td>
                                                    @if($submission && $submission->file)
                                                        <a href="{{ asset('storage/'.$submission->file) }}" target="_blank">
                                                            تحميل الملف
                                                        </a>
                                                    @else
                                                        <span class="text-muted">لا يوجد</span>
                                                    @endif
                                                </td>
                                                <td>{{ $submission?->created_at ?: '-' }}</td>
                                                <td>
                                                    <input
                                                        type="number"
                                                        name="grades[{{ $student->id }}]"
                                                        min="0"
                                                        max="100"
                                                        value="{{ old('grades.'.$student->id, $submission?->grade) }}"
                                                        class="form-control form-control-sm text-center"
                                                    >
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
