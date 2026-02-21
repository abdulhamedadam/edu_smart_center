@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">تقرير الدخل الشهري</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.subscriptions.index') }}">الاشتراكات</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">تقرير الدخل الشهري</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-6">
                <form method="GET" action="{{ route('admin.subscriptions.reports.monthly') }}" class="row g-2 align-items-end">
                    <div class="col-4">
                        <label class="form-label">الشهر</label>
                        <select name="month" class="form-select">
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" @selected($m == $month)>{{ $m }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-4">
                        <label class="form-label">السنة</label>
                        <input type="number" name="year" class="form-control" value="{{ $year }}">
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-secondary w-100">
                            عرض التقرير
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-2">إجمالي الدخل</h5>
                        <p class="display-6 mb-0 text-success">{{ number_format($totalIncome, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-2">إجمالي المصروفات</h5>
                        <p class="display-6 mb-0 text-danger">{{ number_format($totalExpenses, 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-2">صافي الربح</h5>
                        <p class="display-6 mb-0 {{ $net >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($net, 2) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">الدخل حسب المجموعات</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>المجموعة</th>
                                        <th>المادة</th>
                                        <th>المرحلة</th>
                                        <th>الدخل</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($groupIncome as $row)
                                        @php $group = $row['group']; @endphp
                                        <tr>
                                            <td>{{ $group?->name }}</td>
                                            <td>{{ $group?->subject?->name }}</td>
                                            <td>{{ $group?->grade?->name }}</td>
                                            <td>{{ number_format($row['amount'], 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                لا توجد مدفوعات في هذا الشهر
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">المصروفات حسب البنود</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>البند</th>
                                        <th>المصروف</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categoryExpenses as $row)
                                        <tr>
                                            <td>{{ $row['category']?->name ?? 'غير مصنف' }}</td>
                                            <td>{{ number_format($row['amount'], 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center py-4">
                                                لا توجد مصروفات في هذا الشهر
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
