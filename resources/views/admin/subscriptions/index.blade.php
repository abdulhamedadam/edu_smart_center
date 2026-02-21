@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">الاشتراكات</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">الاشتراكات</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.subscriptions.index', ['status' => 'overdue']) }}" class="btn btn-outline-danger btn-sm">
                    المتأخرون عن السداد
                </a>
                <a href="{{ route('admin.subscriptions.create') }}" class="btn btn-primary btn-sm">
                    إضافة اشتراك
                </a>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-lg-4">
                <form method="GET" action="{{ route('admin.subscriptions.index') }}" class="row g-2 align-items-end">
                    <div class="col-8">
                        <label class="form-label">المجموعة</label>
                        <select name="group_id" class="form-select">
                            <option value="">كل المجموعات</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" @selected($groupId == $group->id)>
                                    {{ $group->name }}
                                    @if($group->subject)
                                        - {{ $group->subject->name }}
                                    @endif
                                    @if($group->grade)
                                        ({{ $group->grade->name }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-secondary w-100">
                            تصفية
                        </button>
                    </div>
                    <input type="hidden" name="status" value="{{ $status }}">
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            @if($status === 'overdue')
                                المتأخرون عن السداد
                            @else
                                جميع الاشتراكات
                            @endif
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle" id="subscriptionsTable">
                                <thead>
                                    <tr>
                                        <th>الطالب</th>
                                        <th>الكود</th>
                                        <th>المجموعة</th>
                                        <th>المبلغ</th>
                                        <th>المدفوع</th>
                                        <th>المتبقي</th>
                                        <th>تاريخ الاستحقاق</th>
                                        <th style="width: 160px;">تسجيل دفعة</th>
                                        <th class="text-center">تحكم</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($subscriptions as $subscription)
                                        @php
                                            $remaining = $subscription->remaining;
                                            $overdue = $subscription->due_date && $subscription->due_date->isPast() && $remaining > 0;
                                        @endphp
                                        <tr class="{{ $overdue ? 'table-danger' : '' }}">
                                            <td>{{ $subscription->student?->name }}</td>
                                            <td>{{ $subscription->student?->student_code }}</td>
                                            <td>
                                                {{ $subscription->group?->name }}
                                                @if($subscription->group?->subject)
                                                    <span class="text-muted small d-block">
                                                        {{ $subscription->group->subject->name }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>{{ number_format($subscription->amount, 2) }}</td>
                                            <td>{{ number_format($subscription->paid, 2) }}</td>
                                            <td>{{ number_format($remaining, 2) }}</td>
                                            <td>{{ $subscription->due_date?->format('Y-m-d') ?: '-' }}</td>
                                            <td>
                                                @if($remaining > 0)
                                                    <form method="POST" action="{{ route('admin.subscriptions.pay', $subscription) }}" class="d-flex gap-1">
                                                        @csrf
                                                        <input type="hidden" name="status" value="{{ $status }}">
                                                        <input type="hidden" name="group_id" value="{{ $groupId }}">
                                                        <input type="number" name="amount" step="0.01" min="0.01"
                                                               max="{{ $remaining }}"
                                                               class="form-control form-control-sm"
                                                               placeholder="قيمة الدفعة">
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            دفع
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="badge bg-success">مسدد بالكامل</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.subscriptions.edit', $subscription) }}" class="btn btn-sm btn-outline-primary">
                                                    تعديل
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                لا توجد اشتراكات مسجلة
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
        const table = $('#subscriptionsTable');

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
                    { orderable: false, targets: [7, 8] }
                ]
            });
        }
    });
</script>
@endpush
