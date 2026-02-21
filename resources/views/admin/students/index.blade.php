@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">الطلاب</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">الطلاب</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.students.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i>
                إضافة طالب جديد
            </a>
        </div>
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="studentsTable">
                                <thead>
                                    <tr>
                                        <th>الطالب</th>
                                        <th>الكود</th>
                                        <th>المرحلة</th>
                                        <th>ولي الأمر</th>
                                        <th>هاتف ولي الأمر</th>
                                        <th>QR</th>
                                        <th class="text-center">التحكم</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($students as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @php
                                                        $avatar = $item->avatar_path
                                                            ? asset('storage/'.$item->avatar_path)
                                                            : 'https://ui-avatars.com/api/?name='.urlencode($item->name).'&background=random';
                                                    @endphp
                                                    <img src="{{ $avatar }}" alt="" class="rounded-circle me-2" width="32" height="32">
                                                    <span>{{ $item->name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $item->student_code }}</td>
                                            <td>{{ $item->grade?->name }}</td>
                                            <td>{{ $item->parent_name }}</td>
                                            <td>{{ $item->parent_phone }}</td>
                                            <td>
                                                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::encoding('UTF-8')->size(70)->generate($item->qr_payload) !!}
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.students.edit', $item) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ $item->publicProfileUrl() }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                    عرض
                                                </a>
                                                <form action="{{ route('admin.students.destroy', $item) }}" method="POST" class="d-inline-block"
                                                      onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                لا توجد طلاب مضافين بعد
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
        const table = $('#studentsTable');

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
                    { orderable: false, targets: [5, 6] }
                ]
            });
        }
    });
</script>
@endpush
