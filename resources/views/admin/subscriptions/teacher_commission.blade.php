@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">تقرير نسب المدرسين (عمولة)</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.subscriptions.index') }}">الاشتراكات</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">تقرير نسب المدرسين</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-6">
                <form method="GET" action="{{ route('admin.subscriptions.reports.teacher_commission') }}" class="row g-2 align-items-end">
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

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">نسب المدرسين</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>المدرس</th>
                                        <th>نوع الراتب</th>
                                        <th>نسبة العمولة %</th>
                                        <th>إجمالي دخل مجموعاته</th>
                                        <th>نصيبه من العمولة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($teacherData as $row)
                                        <tr>
                                            <td>{{ $row['teacher']->name }}</td>
                                            <td>{{ $row['teacher']->salary_type ?: '-' }}</td>
                                            <td>{{ $row['teacher']->commission_rate }}</td>
                                            <td>{{ number_format($row['total_amount'], 2) }}</td>
                                            <td>{{ number_format($row['commission'], 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                لا توجد بيانات عمولة لهذا الشهر
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

