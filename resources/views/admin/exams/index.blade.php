@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">الامتحانات</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">الامتحانات</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.exams.create') }}" class="btn btn-primary">
                إضافة امتحان جديد
            </a>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">قائمة الامتحانات</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="examsTable">
                                <thead>
                                    <tr>
                                        <th>العنوان</th>
                                        <th>المجموعة</th>
                                        <th>تاريخ الامتحان</th>
                                        <th>الدرجة الكلية</th>
                                        <th>عدد الدرجات المسجلة</th>
                                        <th class="text-center">التحكم</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($exams as $exam)
                                        <tr>
                                            <td>{{ $exam->title }}</td>
                                            <td>
                                                {{ $exam->group?->name }}
                                                @if($exam->group?->subject)
                                                    <span class="text-muted small d-block">
                                                        {{ $exam->group->subject->name }}
                                                        @if($exam->group->grade)
                                                            – {{ $exam->group->grade->name }}
                                                        @endif
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $exam->date }}</td>
                                            <td>{{ $exam->total_marks }}</td>
                                            <td>{{ $exam->results_count }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.exams.show', $exam) }}" class="btn btn-sm btn-outline-primary">
                                                    عرض
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                لا توجد امتحانات مضافة بعد
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
        const table = $('#examsTable');

        if (table.length) {
            table.DataTable({
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
