@extends('admin.layout.master')

@section('title', 'إشعارات الامتحانات')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">إشعارات الامتحانات</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">إشعارات الامتحانات</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.communication.exams') }}" class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label">المجموعة</label>
                        <select name="group_id" class="form-select">
                            <option value="">كل المجموعات</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" @selected((int)$groupId === $group->id)>
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

                    <div class="col-md-6 text-end">
                        <button type="submit" class="btn btn-primary">
                            عرض الامتحانات
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">الامتحانات</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>العنوان</th>
                                <th>المجموعة</th>
                                <th>المادة</th>
                                <th>المرحلة</th>
                                <th>التاريخ</th>
                                <th class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($exams as $exam)
                                <tr>
                                    <td>{{ $exam->title }}</td>
                                    <td>{{ $exam->group?->name }}</td>
                                    <td>{{ $exam->group?->subject?->name }}</td>
                                    <td>{{ $exam->group?->grade?->name }}</td>
                                    <td>{{ $exam->date?->format('Y-m-d') }}</td>
                                    <td class="text-center">
                                        <form method="POST" action="{{ route('admin.communication.exams.send', $exam) }}"
                                              onsubmit="return confirm('إرسال إشعارات الامتحان لجميع طلاب هذه المجموعة؟');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                إرسال إشعار للطلاب
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        لا توجد امتحانات مسجلة
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

