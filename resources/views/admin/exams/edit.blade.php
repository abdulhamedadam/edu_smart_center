@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">تعديل الامتحان</h1>
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
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">بيانات الامتحان</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.exams.update', $exam) }}" class="row g-3">
                            @csrf
                            @method('PUT')

                            <div class="col-12">
                                <label class="form-label">المجموعة</label>
                                <select name="group_id" class="form-select @error('group_id') is-invalid @enderror">
                                    <option value="">اختر المجموعة</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}" @selected(old('group_id', $exam->group_id) == $group->id)>
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

                            <div class="col-12">
                                <label class="form-label">عنوان الامتحان</label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title', $exam->title) }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">الدرجة الكلية</label>
                                <input type="number" name="total_marks" min="1" max="1000"
                                       class="form-control @error('total_marks') is-invalid @enderror"
                                       value="{{ old('total_marks', $exam->total_marks) }}">
                                @error('total_marks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">تاريخ الامتحان</label>
                                <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                                       value="{{ old('date', $exam->date) }}">
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    حفظ التعديلات
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

