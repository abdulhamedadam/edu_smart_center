@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">تعديل بيانات الطالب</h5>
                        <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-light">
                            رجوع لقائمة الطلاب
                        </a>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.students.update', $student) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">المرحلة</label>
                                <select name="grade_id" class="form-select select2-search @error('grade_id') is-invalid @enderror">
                                    <option value="">اختر المرحلة</option>
                                    @foreach($grades as $grade)
                                        <option value="{{ $grade->id }}" @selected(old('grade_id', $student->grade_id) == $grade->id)>
                                            {{ $grade->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('grade_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label d-flex justify-content-between align-items-center">
                                    <span>ولي الأمر</span>
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#parentModal">
                                        <i class="bi bi-plus"></i>
                                        إضافة جديد
                                    </button>
                                </label>
                                <select name="parent_id" class="form-select select2-search @error('parent_id') is-invalid @enderror">
                                    <option value="">اختر ولي الأمر (اختياري)</option>
                                    @foreach($parents as $parent)
                                        <option value="{{ $parent->id }}" @selected(old('parent_id', $student->parent_id) == $parent->id)>
                                            {{ $parent->name }} @if($parent->phone) - {{ $parent->phone }} @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">اسم الطالب</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $student->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">رقم الطالب</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $student->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            
                            <div class="mb-3">
                                <label class="form-label">صورة الطالب</label>
                                <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror">
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($student->avatar_path)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/'.$student->avatar_path) }}" width="48" height="48" class="rounded-circle">
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex justify-content-end">
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

    <div class="modal fade" id="parentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة ولي أمر جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.parents.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">اسم ولي الأمر</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">هاتف ولي الأمر</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">صلة القرابة</label>
                            <input type="text" name="relation" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
