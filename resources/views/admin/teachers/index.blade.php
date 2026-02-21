@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">المدرسون</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">المدرسون</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i>
                إضافة مدرس جديد
            </a>
        </div>

        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="teachersTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الاسم</th>
                                        <th>الهاتف</th>
                                        <th>التخصص</th>
                                        <th>نوع الراتب</th>
                                        <th>تاريخ الإضافة</th>
                                        <th class="text-center">التحكم</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($teachers as $teacher)
                                        <tr>
                                            <td>{{ $teacher->id }}</td>
                                            <td>{{ $teacher->name }}</td>
                                            <td>{{ $teacher->phone }}</td>
                                            <td>{{ $teacher->specialization }}</td>
                                            <td>{{ $teacher->salary_type }}</td>
                                            <td>{{ $teacher->created_at?->format('Y-m-d') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.teachers.show', $teacher) }}" class="btn btn-sm btn-outline-secondary">
                                                    عرض
                                                </a>
                                                <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-sm btn-outline-primary">
                                                    تعديل
                                                </a>
                                                <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" class="d-inline-block"
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
                                            <td colspan="7" class="text-center py-4">
                                                لا توجد مدرسون مضافون بعد
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
        const table = $('#teachersTable');

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
