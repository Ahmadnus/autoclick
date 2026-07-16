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

            <div class="mb-4">
                <label for="duration_months" class="form-label">مدة الاشتراك</label>
                <select id="duration_months" name="duration_months" class="form-select @error('duration_months') is-invalid @enderror" required>
                    <option value="1" {{ old('duration_months') == 1 ? 'selected' : '' }}>شهر واحد</option>
                    <option value="3" {{ old('duration_months') == 3 ? 'selected' : '' }}>3 أشهر</option>
                </select>
                <div class="form-text">سيتم إنشاء كود تفعيل عشوائي فريد؛ أرسله للعميل عبر واتساب. تبدأ مدة الاشتراك من لحظة إدخال الكود داخل التطبيق، وليس من الآن.</div>
                @error('duration_months')
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
