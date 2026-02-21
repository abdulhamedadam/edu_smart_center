@extends('admin.layout.master')

@section('title', 'أولياء الأمور')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">أولياء الأمور</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">أولياء الأمور</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">إضافة ولي أمر</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.parents.store') }}" class="row g-3">
                            @csrf

                            <div class="col-12">
                                <label class="form-label">الاسم</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">الهاتف</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">البريد الإلكتروني</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">صلة القرابة</label>
                                <input type="text" name="relation" class="form-control @error('relation') is-invalid @enderror"
                                       value="{{ old('relation') }}">
                                @error('relation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    حفظ
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">قائمة أولياء الأمور</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="parentsTable">
                                <thead>
                                    <tr>
                                        <th>الاسم</th>
                                        <th>الهاتف</th>
                                        <th>البريد</th>
                                        <th>صلة القرابة</th>
                                        <th class="text-center">التحكم</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($parents as $parent)
                                        <tr>
                                            <td>{{ $parent->name }}</td>
                                            <td>{{ $parent->phone }}</td>
                                            <td>{{ $parent->email }}</td>
                                            <td>{{ $parent->relation }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.communication.parent_report', $parent) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    تقرير ولي الأمر
                                                </a>
                                                <form method="POST" action="{{ route('admin.parents.destroy', $parent) }}"
                                                      class="d-inline-block"
                                                      onsubmit="return confirm('هل أنت متأكد من حذف هذا ولي الأمر؟');">
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
                                            <td colspan="5" class="text-center py-4">
                                                لا توجد بيانات أولياء أمور بعد
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
        const table = $('#parentsTable');

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
