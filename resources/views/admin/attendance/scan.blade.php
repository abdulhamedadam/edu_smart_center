@extends('admin.layout.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="page-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title mb-1">Scan QR الحضور</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">الرئيسية</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.attendance.index') }}">الحضور والغياب</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Scan QR</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-6">
                @if(session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">تسجيل الحضور عن طريق QR</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.attendance.scan.store') }}" class="row g-3">
                            @csrf

                            <div class="col-12">
                                <label class="form-label">المجموعة</label>
                                <select name="group_id" class="form-select @error('group_id') is-invalid @enderror">
                                    <option value="">اختر المجموعة</option>
                                    @foreach($groups as $group)
                                        <option value="{{ $group->id }}" @selected(old('group_id', $groupId) == $group->id)>
                                            {{ $group->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('group_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">التاريخ</label>
                                <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                                       value="{{ old('date', $date) }}">
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">نتيجة Scan QR</label>
                                <input type="text" name="payload" class="form-control @error('payload') is-invalid @enderror"
                                       value="{{ old('payload') }}" placeholder="امسح الكود أو أدخل كود الطالب">
                                @error('payload')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-1">
                                    الكود يمكن أن يكون JSON كامل من QR أو مجرد كود الطالب
                                </small>
                            </div>

                            <div class="col-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-success">
                                    تسجيل الحضور
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">منطقة الكاميرا</h5>
                    </div>
                    <div class="card-body">
                        <div id="qr-reader" class="border rounded" style="min-height: 260px;"></div>
                        <small class="text-muted d-block mt-2">
                            عند نجاح قراءة الكود سيتم تعبئة الحقل تلقائياً ويمكنك حفظ الحضور مباشرة.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.3.10/html5-qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var readerElement = document.getElementById('qr-reader');
            var payloadInput = document.querySelector('input[name="payload"]');

            if (!readerElement || !payloadInput || typeof Html5QrcodeScanner === 'undefined') {
                return;
            }

            var scanner = new Html5QrcodeScanner('qr-reader', {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            }, false);

            scanner.render(onScanSuccess, onScanError);

            function onScanSuccess(decodedText) {
                payloadInput.value = decodedText;
                payloadInput.focus();
            }

            function onScanError() {
            }
        });
    </script>
@endpush
