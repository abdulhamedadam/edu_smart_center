@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            @isset($group)
                                تعديل مجموعة
                            @else
                                إضافة مجموعة جديدة
                            @endisset
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(session('status'))
                            <div class="alert alert-success mb-3">
                                {{ session('status') }}
                            </div>
                        @endif

                        @isset($group)
                            <form method="POST" action="{{ route('admin.groups.update', $group) }}">
                                @method('PUT')
                        @else
                            <form method="POST" action="{{ route('admin.groups.store') }}">
                        @endisset
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">المرحلة</label>
                                <select name="grade_id" id="gradeSelect" class="form-select select2-search @error('grade_id') is-invalid @enderror">
                                    <option value="">اختر المرحلة</option>
                                    @foreach($grades as $grade)
                                        <option value="{{ $grade->id }}"
                                            @selected(old('grade_id', $group->grade_id ?? null) == $grade->id)>
                                            {{ $grade->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('grade_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">المادة</label>
                                <select
                                    name="subject_id"
                                    id="subjectSelect"
                                    class="form-select select2-search @error('subject_id') is-invalid @enderror"
                                    data-subjects-url="{{ route('admin.grades.subjects', ':grade') }}"
                                >
                                    <option value="">اختر المادة</option>
                                    @php
                                        $selectedSubjectId = old('subject_id', $group->subject_id ?? null);
                                    @endphp
                                    @if(isset($group) && $group->subject)
                                        <option value="{{ $group->subject_id }}" selected>
                                            {{ $group->subject->name }} - {{ $group->grade?->name }}
                                        </option>
                                    @elseif($selectedSubjectId)
                                        @foreach($subjects as $subject)
                                            @if($subject->id == $selectedSubjectId)
                                                <option value="{{ $subject->id }}" selected>
                                                    {{ $subject->name }} - {{ $subject->grade?->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                @error('subject_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">اسم المجموعة</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $group->name ?? '') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">السعة</label>
                                <input type="number" name="capacity" class="form-control @error('capacity') is-invalid @enderror"
                                       value="{{ old('capacity', $group->capacity ?? 0) }}" min="1">
                                @error('capacity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">المصروف الشهري (رسوم المادة)</label>
                                <input type="number" step="0.01" name="monthly_fee" class="form-control @error('monthly_fee') is-invalid @enderror"
                                       value="{{ old('monthly_fee', $group->monthly_fee ?? '') }}">
                                @error('monthly_fee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">
                                    @isset($group)
                                        تحديث
                                    @else
                                        حفظ
                                    @endisset
                                </button>

                                @isset($group)
                                    <a href="{{ route('admin.groups.index') }}" class="btn btn-light">
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
                        <h5 class="mb-0">مجموعات المواد</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle" id="groupsTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>المرحلة</th>
                                    <th>المادة</th>
                                    <th>اسم المجموعة</th>
                                    <th>السعة</th>
                                    <th>رسوم شهرية</th>
                                    <th class="text-center">التحكم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($groups as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->grade?->name }}</td>
                                        <td>{{ $item->subject?->name }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->capacity }}</td>
                                        <td>{{ $item->monthly_fee !== null ? number_format($item->monthly_fee, 2) : '-' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.groups.edit', $item) }}" class="btn btn-sm btn-outline-primary">
                                                تعديل
                                            </a>
                                            <form action="{{ route('admin.groups.destroy', $item) }}" method="POST" class="d-inline-block"
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
                                        <td colspan="6" class="text-center py-4">
                                            لا توجد مجموعات مضافة بعد
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            const gradeSelect = $('#gradeSelect');
            const subjectSelect = $('#subjectSelect');

            const subjectsUrlTemplate = subjectSelect.data('subjects-url');
            const initialSubjectId = '{{ old('subject_id', $group->subject_id ?? '') }}';

            function loadSubjectsForGrade(gradeId, selectedId) {
                subjectSelect.empty();
                subjectSelect.append('<option value="">اختر المادة</option>');

                if (!gradeId) {
                    subjectSelect.val('').trigger('change');
                    return;
                }

                const url = subjectsUrlTemplate.replace(':grade', gradeId);

                $.getJSON(url, function (data) {
                    data.forEach(function (item) {
                        const option = new Option(item.name, item.id, false, String(item.id) === String(selectedId));
                        subjectSelect.append(option);
                    });

                    if (selectedId) {
                        subjectSelect.val(String(selectedId)).trigger('change');
                    } else {
                        subjectSelect.val('').trigger('change');
                    }
                });
            }

            gradeSelect.on('change', function () {
                const gradeId = $(this).val();
                loadSubjectsForGrade(gradeId, null);
            });

            if (gradeSelect.val()) {
                loadSubjectsForGrade(gradeSelect.val(), initialSubjectId);
            }

            const groupsTable = $('#groupsTable');

            if (groupsTable.length) {
                groupsTable.DataTable({
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json'
                    },
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    ordering: true,
                    responsive: true,
                    columnDefs: [
                        { orderable: false, targets: -1 }
                    ]
                });
            }
        });
    </script>
@endpush
