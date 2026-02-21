@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">تعديل الاشتراك</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.subscriptions.index') }}">الاشتراكات</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">تعديل الاشتراك</li>
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
                        <form method="POST" action="{{ route('admin.subscriptions.update', $subscription) }}" class="row g-3">
                            @csrf
                            @method('PUT')

                            <div class="col-12">
                                <label class="form-label">الطالب</label>
                                <select name="student_id" class="form-select @error('student_id') is-invalid @enderror">
                                    <option value="">اختر الطالب</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" @selected(old('student_id', $subscription->student_id) == $student->id)>
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
                                        <option value="{{ $group->id }}" @selected(old('group_id', $subscription->group_id) == $group->id)>
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

                            <div class="col-md-6">
                                <label class="form-label">المبلغ الكلي</label>
                                <input type="number" step="0.01" name="amount"
                                       class="form-control @error('amount') is-invalid @enderror"
                                       value="{{ old('amount', $subscription->amount) }}">
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">تاريخ الاستحقاق</label>
                                <input type="date" name="due_date"
                                       class="form-control @error('due_date') is-invalid @enderror"
                                       value="{{ old('due_date', optional($subscription->due_date)->format('Y-m-d')) }}">
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    حفظ التعديلات
                                </button>
                            </div>
                        </form>

                        <form method="POST" action="{{ route('admin.subscriptions.destroy', $subscription) }}"
                              onsubmit="return confirm('هل أنت متأكد من حذف هذا الاشتراك؟');"
                              class="mt-3 d-flex justify-content-end">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                حذف الاشتراك
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
