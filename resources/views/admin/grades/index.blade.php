@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            @isset($grade)
                                تعديل مرحلة
                            @else
                                إضافة مرحلة جديدة
                            @endisset
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(session('status'))
                            <div class="alert alert-success mb-3">
                                {{ session('status') }}
                            </div>
                        @endif

                        @isset($grade)
                            <form method="POST" action="{{ route('admin.grades.update', $grade) }}">
                                @method('PUT')
                        @else
                            <form method="POST" action="{{ route('admin.grades.store') }}">
                        @endisset
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">اسم المرحلة</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $grade->name ?? '') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">
                                    @isset($grade)
                                        تحديث
                                    @else
                                        حفظ
                                    @endisset
                                </button>

                                @isset($grade)
                                    <a href="{{ route('admin.grades.index') }}" class="btn btn-light">
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
                        <h5 class="mb-0">المراحل الدراسية</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                        <table class="table mb-0" id="gradesTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>اسم المرحلة</th>
                                    <th class="text-center">التحكم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($grades as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.grades.edit', $item) }}" class="btn btn-sm btn-outline-primary">
                                                تعديل
                                            </a>
                                            <form action="{{ route('admin.grades.destroy', $item) }}" method="POST" class="d-inline-block"
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
                                        <td colspan="3" class="text-center py-4">
                                            لا توجد مراحل مضافة بعد
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
        const table = $('#gradesTable');

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
