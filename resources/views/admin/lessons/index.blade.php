@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">جدول الحصص الأسبوعي</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">جدول الحصص</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                @if(session('status'))
                    <div class="alert alert-success mb-3">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">تصفية الجدول</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.schedule.index') }}" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label">المجموعة</label>
                                <select name="group_id" class="form-select">
                                    <option value="">كل المجموعات</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}" @selected($groupId == $group->id)>
                                            {{ $group->name }} @if($group->subject) - {{ $group->subject->name }} @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">المدرس</label>
                                <select name="teacher_id" class="form-select">
                                    <option value="">كل المدرسين</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" @selected($teacherId == $teacher->id)>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    عرض الجدول
                                </button>
                                <a href="{{ route('admin.schedule.index') }}" class="btn btn-light">
                                    إعادة تعيين
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">إضافة حصة جديدة</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.schedule.store') }}" class="row g-3">
                            @csrf

                            <div class="col-md-3">
                                <label class="form-label">المجموعة</label>
                                <select name="group_id" class="form-select @error('group_id') is-invalid @enderror">
                                    <option value="">اختر المجموعة</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}" @selected(old('group_id', $groupId) == $group->id)>
                                            {{ $group->name }} @if($group->subject) - {{ $group->subject->name }} @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('group_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">المدرس</label>
                                <select name="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror">
                                    <option value="">اختر المدرس</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" @selected(old('teacher_id', $teacherId) == $teacher->id)>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('teacher_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">اليوم</label>
                                <select name="day" class="form-select @error('day') is-invalid @enderror">
                                    <option value="">اختر اليوم</option>
                                    @foreach($days as $dayKey => $dayLabel)
                                        <option value="{{ $dayKey }}" @selected(old('day') == $dayKey)>
                                            {{ $dayLabel }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('day')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">من</label>
                                <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror"
                                       value="{{ old('start_time') }}">
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">إلى</label>
                                <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror"
                                       value="{{ old('end_time') }}">
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2">
                                <label class="form-label">القاعة</label>
                                <input type="text" name="room" class="form-control @error('room') is-invalid @enderror"
                                       value="{{ old('room') }}">
                                @error('room')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-success w-100">
                                    حفظ الحصة
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            @if($selectedGroup)
                                جدول الأسبوع للمجموعة: {{ $selectedGroup->name }}
                            @elseif($selectedTeacher)
                                جدول الأسبوع للمدرس: {{ $selectedTeacher->name }}
                            @else
                                جدول الحصص للأسبوع الحالي
                            @endif
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0 text-center align-middle">
                                <thead>
                                    <tr>
                                        <th style="width: 10%;">اليوم</th>
                                        <th>الحصص</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $lessonsByDay = $lessons->groupBy('day');
                                    @endphp

                                    @foreach($days as $dayKey => $dayLabel)
                                        <tr>
                                            <td class="fw-semibold">{{ $dayLabel }}</td>
                                            <td>
                                                @if(isset($lessonsByDay[$dayKey]) && $lessonsByDay[$dayKey]->count())
                                                    <div class="d-flex flex-wrap gap-2 justify-content-start">
                                                        @foreach($lessonsByDay[$dayKey] as $lesson)
                                                            <div class="border rounded px-3 py-2 text-start">
                                                                <div class="small">
                                                                    <span class="fw-semibold">
                                                                        {{ $lesson->start_time }} - {{ $lesson->end_time }}
                                                                    </span>
                                                                </div>
                                                                <div class="small">
                                                                    @if(!$selectedGroup)
                                                                        مجموعة: {{ $lesson->group?->name }}
                                                                    @endif
                                                                </div>
                                                                <div class="small">
                                                                    @if(!$selectedTeacher)
                                                                        مدرس: {{ $lesson->teacher?->name }}
                                                                    @endif
                                                                </div>
                                                                <div class="small">
                                                                    قاعة: {{ $lesson->room ?: '-' }}
                                                                </div>
                                                                <div class="mt-2">
                                                                    <a href="{{ route('admin.attendance.scan', ['group_id' => $lesson->group_id]) }}"
                                                                       class="btn btn-sm btn-outline-primary">
                                                                        Scan حضور
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-muted">لا توجد حصص مسجلة لهذا اليوم</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
