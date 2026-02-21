@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">تقرير حضور الطالب</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.attendance.index') }}">الحضور والغياب</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $student->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">{{ $student->name }}</h5>
                        <p class="mb-1"><strong>الكود:</strong> {{ $student->student_code }}</p>
                        <p class="mb-1"><strong>المرحلة:</strong> {{ $student->grade?->name }}</p>
                        <p class="mb-1"><strong>ولي الأمر:</strong> {{ $student->parent_name }}</p>
                        <p class="mb-0"><strong>هاتف ولي الأمر:</strong> {{ $student->parent_phone ?: '-' }}</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">تقرير الغياب الشهري</h5>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.attendance.student', $student) }}" class="mb-3">
                            <div class="mb-2">
                                <label class="form-label">اختر شهر</label>
                                <input type="month" name="month" class="form-control" value="{{ $month }}">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                عرض الغياب في الشهر
                            </button>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th>الشهر</th>
                                        <th class="text-center">عدد أيام الغياب</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($monthlyAbsences as $row)
                                        <tr>
                                            <td>{{ $row->month }}</td>
                                            <td class="text-center">{{ $row->total }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center py-3">
                                                لا توجد سجلات غياب بعد
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">سجل الحضور</h5>
                        @if($month)
                            <span class="text-muted small">للشهر: {{ $month }}</span>
                        @endif
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>التاريخ</th>
                                        <th>المجموعة</th>
                                        <th>الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($attendances as $item)
                                        <tr>
                                            <td>{{ $item->date }}</td>
                                            <td>{{ $item->group?->name }}</td>
                                            <td>
                                                @if($item->status === 'present')
                                                    <span class="badge bg-success">حاضر</span>
                                                @else
                                                    <span class="badge bg-danger">غائب</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4">
                                                لا توجد سجلات حضور بعد
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

