@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">الواجبات</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">الواجبات</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.homework.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i>
                إضافة واجب جديد
            </a>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                @if(session('status'))
                    <div class="alert alert-success mb-3">
                        {{ session('status') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="homeworkTable">
                                <thead>
                                    <tr>
                                        <th>العنوان</th>
                                        <th>المجموعة</th>
                                        <th>المدرس</th>
                                        <th>تاريخ التسليم</th>
                                        <th>عدد التسليمات</th>
                                        <th class="text-center">التحكم</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($homeworks as $homework)
                                        <tr>
                                            <td>{{ $homework->title }}</td>
                                            <td>
                                                {{ $homework->group?->name }}
                                                @if($homework->group?->subject)
                                                    <span class="text-muted small d-block">
                                                        {{ $homework->group->subject->name }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ $homework->teacher?->name }}</td>
                                            <td>{{ $homework->due_date ?: '-' }}</td>
                                            <td>{{ $homework->submissions()->count() }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.homework.show', $homework) }}" class="btn btn-sm btn-outline-primary">
                                                    عرض
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                لا توجد واجبات مضافة بعد
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
        const table = $('#homeworkTable');

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
