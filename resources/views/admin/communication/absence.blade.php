@extends('admin.layout.master')

@section('title', 'إشعارات الغياب')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">إشعارات الغياب</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">إشعارات الغياب</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.communication.absence') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">المجموعة</label>
                        <select name="group_id" class="form-select">
                            <option value="">كل المجموعات</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" @selected((int)$groupId === $group->id)>
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
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">التاريخ</label>
                        <input type="date" name="date" class="form-control" value="{{ $date }}">
                    </div>

                    <div class="col-md-5 text-end">
                        <button type="submit" class="btn btn-primary">
                            عرض الغياب
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    الغياب بتاريخ {{ $date }}
                    @if($groupId)
                        للمجموعة المختارة
                    @endif
                </h5>
            </div>
            <div class="card-body p-0">
                <form method="POST" action="{{ route('admin.communication.absence.send') }}">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    <input type="hidden" name="group_id" value="{{ $groupId }}">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th>الطالب</th>
                                    <th>الكود</th>
                                    <th>ولي الأمر</th>
                                    <th>هاتف ولي الأمر</th>
                                    <th>المجموعة</th>
                                    <th>المادة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($absences as $item)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="attendance_ids[]" value="{{ $item->id }}" class="select-row">
                                        </td>
                                        <td>{{ $item->student?->name }}</td>
                                        <td>{{ $item->student?->student_code }}</td>
                                        <td>{{ $item->student?->parent_name }}</td>
                                        <td>{{ $item->student?->parent_phone }}</td>
                                        <td>{{ $item->group?->name }}</td>
                                        <td>{{ $item->group?->subject?->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            لا يوجد طلاب غائبين في هذا اليوم
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($absences->isNotEmpty())
                        <div class="p-3 d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                سيتم إنشاء إشعار لكل طالب محدد، يمكن استخدامه لاحقاً في SMS أو WhatsApp.
                            </div>
                            <button type="submit" class="btn btn-success">
                                إنشاء إشعارات الغياب
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.select-row');

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    checkboxes.forEach(function (checkbox) {
                        checkbox.checked = selectAll.checked;
                    });
                });
            }
        });
    </script>
@endpush

