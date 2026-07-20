@extends('layouts.app')

@section('title', 'إنشاء كود تفعيل جديد')

@push('styles')
    <style>
        .form-label { font-weight: 700; font-size: .88rem; color: #334155; }
        .form-control, .form-select {
            border-radius: 10px;
            border-color: #e2e8f0;
            padding: .6rem .9rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--indigo);
            box-shadow: 0 0 0 .2rem var(--indigo-soft);
        }
        .form-text { color: var(--slate-400); }
    </style>
@endpush

@section('content')
    <div class="card stat-card p-4" style="max-width: 640px;">
        <form action="{{ route('licenses.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="driver_name" class="form-label">اسم العميل</label>
                <input type="text" id="driver_name" name="driver_name" value="{{ old('driver_name') }}"
                       class="form-control @error('driver_name') is-invalid @enderror" required>
                @error('driver_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="phone_number" class="form-label">رقم الهاتف</label>
                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}"
                       class="form-control @error('phone_number') is-invalid @enderror" dir="ltr" required>
                @error('phone_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="duration_type" class="form-label">مدة الاشتراك</label>
                <select id="duration_type" name="duration_type" class="form-select @error('duration_type') is-invalid @enderror" required
                        onchange="document.getElementById('manual_days_group').style.display = this.value === 'manual' ? '' : 'none';">
                    @foreach (\App\Models\License::DURATION_OPTIONS as $value => $label)
                        <option value="{{ $value }}" {{ old('duration_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <div class="form-text">سيتم إنشاء كود تفعيل عشوائي فريد؛ أرسله للعميل عبر واتساب. تبدأ مدة الاشتراك من لحظة إدخال الكود داخل التطبيق، وليس من الآن.</div>
                @error('duration_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4" id="manual_days_group" style="{{ old('duration_type') === 'manual' ? '' : 'display: none;' }}">
                <label for="manual_days" class="form-label">عدد الأيام (مدة يدوية)</label>
                <input type="number" id="manual_days" name="manual_days" min="1" max="3650" value="{{ old('manual_days') }}"
                       class="form-control @error('manual_days') is-invalid @enderror">
                @error('manual_days')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-plus-circle ms-1"></i> إنشاء الكود
            </button>
            <a href="{{ route('licenses.index') }}" class="btn btn-outline-secondary">إلغاء</a>
        </form>
    </div>
@endsection
