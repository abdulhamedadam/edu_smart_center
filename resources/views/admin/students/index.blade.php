@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">قائمة الطلاب</h5>
                        <a href="{{ route('admin.students.create') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-plus"></i>
                            إضافة طالب جديد
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
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

                        <div class="p-3">
                            {{ $students->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
