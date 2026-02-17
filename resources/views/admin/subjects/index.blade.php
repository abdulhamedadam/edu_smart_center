@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            @isset($subject)
                                تعديل مادة
                            @else
                                إضافة مادة جديدة
                            @endisset
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(session('status'))
                            <div class="alert alert-success mb-3">
                                {{ session('status') }}
                            </div>
                        @endif

                        @isset($subject)
                            <form method="POST" action="{{ route('admin.subjects.update', $subject) }}">
                                @method('PUT')
                        @else
                            <form method="POST" action="{{ route('admin.subjects.store') }}">
                        @endisset
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">المرحلة</label>
                                <select name="grade_id" class="form-select @error('grade_id') is-invalid @enderror">
                                    <option value="">اختر المرحلة</option>
                                    @foreach($grades as $grade)
                                        <option value="{{ $grade->id }}"
                                            @selected(old('grade_id', $subject->grade_id ?? null) == $grade->id)>
                                            {{ $grade->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('grade_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">اسم المادة</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $subject->name ?? '') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">
                                    @isset($subject)
                                        تحديث
                                    @else
                                        حفظ
                                    @endisset
                                </button>

                                @isset($subject)
                                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-light">
                                        إلغاء التعديل
                                    </a>
                                @endisset
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">مواد المراحل</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>المرحلة</th>
                                    <th>اسم المادة</th>
                                    <th class="text-center">التحكم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subjects as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->grade?->name }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.subjects.edit', $item) }}" class="btn btn-sm btn-outline-primary">
                                                تعديل
                                            </a>
                                            <form action="{{ route('admin.subjects.destroy', $item) }}" method="POST" class="d-inline-block"
                                                  onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    حذف
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            لا توجد مواد مضافة بعد
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="p-3">
                            {{ $subjects->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

