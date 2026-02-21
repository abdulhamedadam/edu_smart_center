@extends('admin.layout.master')

@section('title', 'المصروفات')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-3">
            <div class="col-md-6">
                <h4 class="mb-0">المصروفات</h4>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('admin.expenses.create') }}" class="btn btn-primary">
                    إضافة مصروف
                </a>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.expenses.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">البند</label>
                        <select name="category_id" class="form-select">
                            <option value="">كل البنود</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected($categoryId == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">الشهر</label>
                        <select name="month" class="form-select">
                            <option value="">الكل</option>
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" @selected((int)($month ?? 0) === $m)>{{ $m }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">السنة</label>
                        <input type="number" name="year" class="form-control" value="{{ $year ?? '' }}">
                    </div>

                    <div class="col-md-4 text-end">
                        <button type="submit" class="btn btn-outline-secondary">
                            تصفية
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="p-3">
                    <strong>إجمالي المصروفات:</strong>
                    <span class="text-danger">{{ number_format($total, 2) }}</span>
                </div>

                <div class="table-responsive">
                <table class="table mb-0" id="expensesTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>التاريخ</th>
                            <th>البند</th>
                            <th>الوصف</th>
                            <th>المبلغ</th>
                            <th class="text-center">التحكم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                            <tr>
                                <td>{{ $expense->id }}</td>
                                <td>{{ $expense->date?->format('Y-m-d') }}</td>
                                <td>{{ $expense->category?->name }}</td>
                                <td>{{ $expense->description }}</td>
                                <td>{{ number_format($expense->amount, 2) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.expenses.edit', $expense) }}" class="btn btn-sm btn-outline-primary">
                                        تعديل
                                    </a>
                                    <form action="{{ route('admin.expenses.destroy', $expense) }}" method="POST" class="d-inline-block"
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
                                    لا توجد مصروفات مسجلة بعد
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function () {
        const table = $('#expensesTable');

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
