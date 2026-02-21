@extends('admin.layout.master')

@section('title', 'إضافة مصروف')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-3">
            <div class="col-md-6">
                <h4 class="mb-0">إضافة مصروف</h4>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.expenses.index') }}" class="btn btn-light">
                    رجوع للمصروفات
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.expenses.store') }}" class="row g-3">
                    @csrf

                    <div class="col-md-4">
                        <label class="form-label">البند</label>
                        <select name="expense_category_id"
                                class="form-select @error('expense_category_id') is-invalid @enderror">
                            <option value="">اختر البند</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('expense_category_id') == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('expense_category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">التاريخ</label>
                        <input type="date" name="date"
                               class="form-control @error('date') is-invalid @enderror"
                               value="{{ old('date', now()->toDateString()) }}">
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">المبلغ</label>
                        <input type="number" step="0.01" name="amount"
                               class="form-control @error('amount') is-invalid @enderror"
                               value="{{ old('amount') }}">
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">الوصف</label>
                        <input type="text" name="description"
                               class="form-control @error('description') is-invalid @enderror"
                               value="{{ old('description') }}">
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            حفظ المصروف
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

