@extends('admin.layout.master')

@section('title', 'الرسائل الجماعية')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">الرسائل الجماعية</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">الرسائل الجماعية</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.communication.broadcast.send') }}" class="row g-3">
                    @csrf

                    <div class="col-md-4">
                        <label class="form-label">نطاق الإرسال</label>
                        <select name="scope" id="scopeSelect" class="form-select @error('scope') is-invalid @enderror">
                            <option value="all" @selected(old('scope') === 'all')>جميع أولياء الأمور</option>
                            <option value="grade" @selected(old('scope') === 'grade')>حسب المرحلة الدراسية</option>
                            <option value="group" @selected(old('scope') === 'group')>حسب المجموعة</option>
                        </select>
                        @error('scope')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 scope-field scope-grade d-none">
                        <label class="form-label">المرحلة</label>
                        <select name="grade_id" class="form-select @error('grade_id') is-invalid @enderror">
                            <option value="">اختر المرحلة</option>
                            @foreach(\App\Models\Grade::orderBy('name')->get() as $grade)
                                <option value="{{ $grade->id }}" @selected(old('grade_id') == $grade->id)>
                                    {{ $grade->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('grade_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 scope-field scope-group d-none">
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

                    <div class="col-12">
                        <label class="form-label">عنوان الرسالة</label>
                        <input type="text" name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">نص الرسالة</label>
                        <textarea name="body" rows="5"
                                  class="form-control @error('body') is-invalid @enderror">{{ old('body') }}</textarea>
                        @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            إرسال الرسالة
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const scopeSelect = document.getElementById('scopeSelect');
            const gradeFields = document.querySelectorAll('.scope-grade');
            const groupFields = document.querySelectorAll('.scope-group');

            function updateScopeFields() {
                const scope = scopeSelect.value;

                gradeFields.forEach(el => el.classList.add('d-none'));
                groupFields.forEach(el => el.classList.add('d-none'));

                if (scope === 'grade') {
                    gradeFields.forEach(el => el.classList.remove('d-none'));
                } else if (scope === 'group') {
                    groupFields.forEach(el => el.classList.remove('d-none'));
                }
            }

            scopeSelect.addEventListener('change', updateScopeFields);
            updateScopeFields();
        });
    </script>
@endpush

