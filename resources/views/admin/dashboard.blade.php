@extends('layouts.app')

@section('title', 'لوحة المعلومات')

@push('styles')
    <style>
        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .stat-icon.indigo { background: var(--indigo-soft); color: var(--indigo-dark); }
        .stat-icon.emerald { background: var(--emerald-soft); color: var(--emerald); }
        .stat-icon.crimson { background: var(--crimson-soft); color: var(--crimson); }
        .stat-label { color: var(--slate-500); font-weight: 600; font-size: .85rem; }
        .stat-value { font-weight: 800; letter-spacing: -.5px; }
    </style>
@endpush

@section('content')
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card stat-card p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label mb-2">إجمالي أكواد التفعيل</div>
                        <h2 class="mb-0 stat-value">{{ $stats['total_drivers'] }}</h2>
                    </div>
                    <div class="stat-icon indigo"><i class="bi bi-people"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label mb-2">الاشتراكات الفعّالة</div>
                        <h2 class="mb-0 stat-value" style="color: var(--emerald);">{{ $stats['active_subscriptions'] }}</h2>
                    </div>
                    <div class="stat-icon emerald"><i class="bi bi-check-circle"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stat-label mb-2">أكواد منتهية / محظورة</div>
                        <h2 class="mb-0 stat-value" style="color: var(--crimson);">{{ $stats['expired_or_blocked'] }}</h2>
                    </div>
                    <div class="stat-icon crimson"><i class="bi bi-x-circle"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 d-flex gap-2">
        <a href="{{ route('licenses.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-list-ul ms-1"></i> عرض جميع الأكواد
        </a>
        <a href="{{ route('licenses.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle ms-1"></i> إنشاء كود جديد
        </a>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-md-6">
            <div class="card stat-card p-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-whatsapp text-success ms-1"></i> إعدادات واتساب</h6>
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <label for="whatsapp_number" class="stat-label mb-2 d-block">رقم واتساب الدعم</label>
                    <div class="input-group">
                        <input
                            type="text"
                            name="whatsapp_number"
                            id="whatsapp_number"
                            class="form-control @error('whatsapp_number') is-invalid @enderror"
                            value="{{ old('whatsapp_number', $whatsappNumber) }}"
                            placeholder="مثال: 966500000000"
                            dir="ltr"
                            required
                        >
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check2 ms-1"></i> حفظ
                        </button>
                        @error('whatsapp_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-text">بالصيغة الدولية بدون علامة + أو مسافات، مثل 966500000000.</div>
                </form>
            </div>
        </div>
    </div>
@endsection
