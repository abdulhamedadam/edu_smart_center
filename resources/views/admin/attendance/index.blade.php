@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">شاشة الحضور</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">الحضور والغياب</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.attendance.scan') }}" class="btn btn-outline-primary">
                <i class="bi bi-qr-code-scan"></i>
                شاشة Scan QR
            </a>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                @if(session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">فلتر الحضور</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.attendance.index') }}" class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label">المجموعة</label>
                                <select name="group_id" class="form-select">
                                    <option value="">اختر المجموعة</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}" @selected($groupId == $group->id)>
                                            {{ $group->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">التاريخ</label>
                                <input type="date" name="date" class="form-control" value="{{ $date }}">
                            </div>
                            <div class="col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    عرض الحضور
                                </button>
                                <a href="{{ route('admin.attendance.index') }}" class="btn btn-light">
                                    إعادة تعيين
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">تسجيل غياب يدوي</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.attendance.absence.store') }}" class="row g-3">
                            @csrf

                            <div class="col-md-4">
                                <label class="form-label">المجموعة</label>
                                <select name="group_id" class="form-select @error('group_id') is-invalid @enderror">
                                    <option value="">اختر المجموعة</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}" @selected(old('group_id', $groupId) == $group->id)>
                                            {{ $group->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('group_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">التاريخ</label>
                                <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                                       value="{{ old('date', $date) }}">
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">كود الطالب</label>
                                <input type="text" name="student_code" class="form-control @error('student_code') is-invalid @enderror"
                                       value="{{ old('student_code') }}" placeholder="مثلًا: STU001">
                                @error('student_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-danger w-100">
                                    تسجيل غياب
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            @if($selectedGroup)
                                الحضور للمجموعة: {{ $selectedGroup->name }} في {{ $date }}
                            @else
                                اختر مجموعة وتاريخ لعرض الحضور
                            @endif
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="attendanceTable">
                                <thead>
                                    <tr>
                                        <th>الطالب</th>
                                        <th>الكود</th>
                                        <th>التاريخ</th>
                                        <th>الحالة</th>
                                        <th class="text-center">الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($attendances as $item)
                                        <tr>
                                            <td>{{ $item->student?->name }}</td>
                                            <td>{{ $item->student?->student_code }}</td>
                                            <td>{{ $item->date }}</td>
                                            <td>
                                                @if($item->status === 'present')
                                                    <span class="badge bg-success">حاضر</span>
                                                @else
                                                    <span class="badge bg-danger">غائب</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="d-inline-flex gap-2">
                                                    @if($item->student)
                                                        <a href="{{ route('admin.attendance.student', $item->student) }}" class="btn btn-sm btn-outline-secondary">
                                                            تقرير
                                                        </a>
                                                    @endif
                                                    <form method="POST" action="{{ route('admin.attendance.toggle', $item->id) }}">
                                                        @csrf
                                                        <input type="hidden" name="group_id" value="{{ $groupId }}">
                                                        <input type="hidden" name="date" value="{{ $date }}">
                                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                                            تبديل
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                @if($selectedGroup)
                                                    لا توجد سجلات حضور لهذا اليوم
                                                @else
                                                    لم يتم اختيار مجموعة بعد
                                                @endif
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
    </div>
@endsection

@push('scripts')
<script>
    $(function () {
        const table = $('#attendanceTable');

        if (table.length) {
            table.DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json'
                },
                pageLength: 25,
                lengthMenu: [25, 50, 100],
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
