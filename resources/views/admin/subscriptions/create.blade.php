@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">إضافة اشتراك جديد</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.subscriptions.index') }}">الاشتراكات</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">إضافة اشتراك</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">بيانات الاشتراك</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.subscriptions.store') }}" class="row g-3">
                            @csrf

                            <div class="col-12">
                                <label class="form-label">الطالب</label>
                                <select name="student_id" class="form-select @error('student_id') is-invalid @enderror">
                                    <option value="">اختر الطالب</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>
                                            {{ $student->name }} ({{ $student->student_code }})
                                            @if($student->grade)
                                                - {{ $student->grade->name }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">المجموعة</label>
                                <select name="group_id" class="form-select @error('group_id') is-invalid @enderror">
                                    <option value="">اختر المجموعة</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}" @selected(old('group_id') == $group->id)>
                                            {{ $group->name }}
                                            @if($group->subject)
                                                - {{ $group->subject->name }}
                                            @endif
                                            @if($group->grade)
                                                ({{ $group->grade->name }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('group_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">المبلغ الشهري</label>
                                <input type="number" step="0.01" name="amount"
                                       class="form-control @error('amount') is-invalid @enderror"
                                       value="{{ old('amount') }}">
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">دفعة أولى (اختياري)</label>
                                <input type="number" step="0.01" name="paid"
                                       class="form-control @error('paid') is-invalid @enderror"
                                       value="{{ old('paid') }}">
                                @error('paid')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">شهر الاشتراك</label>
                                <select name="month" class="form-select @error('month') is-invalid @enderror">
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" @selected(old('month', now()->month) == $m)>
                                            {{ $m }}
                                        </option>
                                    @endfor
                                </select>
                                @error('month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">سنة الاشتراك</label>
                                <input type="number" name="year"
                                       class="form-control @error('year') is-invalid @enderror"
                                       value="{{ old('year', now()->year) }}">
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    حفظ الاشتراك
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
