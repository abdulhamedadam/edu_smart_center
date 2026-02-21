@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">إضافة مدرس جديد</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.teachers.index') }}">المدرسون</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">إضافة مدرس</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">بيانات المدرس</h5>
                        <a href="{{ route('admin.teachers.index') }}" class="btn btn-sm btn-light">
                            رجوع لقائمة المدرسين
                        </a>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.teachers.store') }}">
                            @csrf

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">اسم المدرس</label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                               value="{{ old('name') }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">هاتف المدرس</label>
                                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                               value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">التخصص</label>
                                        <input type="text" name="specialization" class="form-control @error('specialization') is-invalid @enderror"
                                               value="{{ old('specialization') }}">
                                        @error('specialization')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">نوع الراتب</label>
                                        <input type="text" name="salary_type" class="form-control @error('salary_type') is-invalid @enderror"
                                               value="{{ old('salary_type') }}">
                                        @error('salary_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">نسبة العمولة % (إن وجد)</label>
                                        <input type="number" step="0.01" name="commission_rate"
                                               class="form-control @error('commission_rate') is-invalid @enderror"
                                               value="{{ old('commission_rate') }}">
                                        @error('commission_rate')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">المواد التي يدرسها</label>
                                        <select name="subjects[]" class="form-select select2-search @error('subjects') is-invalid @enderror" multiple>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" @selected(collect(old('subjects', []))->contains($subject->id))>
                                                    {{ $subject->name }} @if($subject->grade) - {{ $subject->grade->name }} @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('subjects')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">المجموعات التي يدرسها</label>
                                        <select name="groups[]" class="form-select select2-search @error('groups') is-invalid @enderror" multiple>
                                            @foreach($groups as $group)
                                                <option value="{{ $group->id }}" @selected(collect(old('groups', []))->contains($group->id))>
                                                    {{ $group->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('groups')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    حفظ المدرس
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
