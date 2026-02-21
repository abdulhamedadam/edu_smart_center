@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">ملف المدرس</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.teachers.index') }}">المدرسون</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $teacher->name }}</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('admin.teachers.edit', $teacher) }}" class="btn btn-primary">
                تعديل بيانات المدرس
            </a>
        </div>

        <div class="row mb-4">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="mb-3">{{ $teacher->name }}</h5>
                        <p class="mb-2"><strong>الهاتف:</strong> {{ $teacher->phone ?: '-' }}</p>
                        <p class="mb-2"><strong>التخصص:</strong> {{ $teacher->specialization ?: '-' }}</p>
                        <p class="mb-2"><strong>نوع الراتب:</strong> {{ $teacher->salary_type ?: '-' }}</p>
                        <p class="mb-0"><strong>نسبة العمولة:</strong> {{ $teacher->commission_rate !== null ? $teacher->commission_rate.'%' : '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">المواد والمجموعات</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>المادة</th>
                                        <th>المرحلة</th>
                                        <th>المجموعة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $rows = [];
                                        foreach ($teacher->subjects as $subject) {
                                            $groupId = $subject->pivot->group_id;
                                            $group = $teacher->groups->firstWhere('id', $groupId);
                                            $rows[] = [
                                                'subject' => $subject,
                                                'group' => $group,
                                            ];
                                        }
                                    @endphp

                                    @forelse($rows as $row)
                                        <tr>
                                            <td>{{ $row['subject']->name }}</td>
                                            <td>{{ $row['subject']->grade?->name }}</td>
                                            <td>{{ $row['group']?->name }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4">
                                                لا توجد مواد أو مجموعات مرتبطة بالمدرس بعد
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
