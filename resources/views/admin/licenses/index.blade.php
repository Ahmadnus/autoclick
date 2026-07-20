@extends('layouts.app')

@section('title', 'أكواد التفعيل')

@push('styles')
    <style>
        .badge-status { font-weight: 700; font-size: .78rem; padding: .45em .85em; }
        .badge-active { background: var(--emerald-soft); color: var(--emerald); }
        .badge-blocked { background: var(--crimson-soft); color: var(--crimson); }
        .badge-expired { background: rgba(100, 116, 139, .12); color: var(--slate-500); }
        .badge-unused { background: var(--indigo-soft); color: var(--indigo-dark); }

        table thead.table-light th {
            background-color: var(--slate-50);
            color: var(--slate-500);
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            font-weight: 700;
            border-bottom-width: 1px;
        }
        table tbody tr:hover { background-color: var(--indigo-soft); }
        table td { vertical-align: middle; }

        .action-btn {
            border-radius: 999px;
            font-size: .78rem;
            font-weight: 700;
            padding: .35rem .8rem;
            border: none;
            transition: transform .12s ease, box-shadow .12s ease;
        }
        .action-btn:hover { transform: translateY(-1px); }
        .action-btn.activate { background: var(--emerald-soft); color: var(--emerald); }
        .action-btn.activate:hover { background: var(--emerald); color: #fff; }
        .action-btn.block { background: var(--crimson-soft); color: var(--crimson); }
        .action-btn.block:hover { background: var(--crimson); color: #fff; }
        .action-btn.extend { background: var(--amber-soft); color: var(--amber); }
        .action-btn.extend:hover { background: var(--amber); color: #fff; }
        .action-btn.destroy { background: transparent; color: var(--slate-400); padding: .35rem .55rem; }
        .action-btn.destroy:hover { background: var(--crimson-soft); color: var(--crimson); }
    </style>
@endpush

@section('content')
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('licenses.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle ms-1"></i> إنشاء كود جديد
        </a>
    </div>

    <div class="card stat-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>الكود</th>
                        <th>المدة</th>
                        <th>اسم المشترك</th>
                        <th>رقم الهاتف</th>
                        <th>معرف الجهاز</th>
                        <th>الحالة</th>
                        <th>تاريخ الانتهاء</th>
                        <th class="text-center">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($licenses as $license)
                        <tr>
                            <td class="text-muted">{{ $license->id }}</td>
                            <td class="fw-semibold"><code>{{ $license->code }}</code></td>
                            <td>{{ $license->durationLabel() }}</td>
                            <td>{{ $license->driver_name ?? '—' }}</td>
                            <td class="text-end">
                                @if ($license->phone_number)
                                    <code>{{ $license->phone_number }}</code>
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                @if ($license->device_id)
                                    <code>{{ \Illuminate\Support\Str::limit($license->device_id, 18) }}</code>
                                @else
                                    <span class="text-muted">— لم يُستخدم بعد —</span>
                                @endif
                            </td>
                            <td>
                                @if (! $license->is_active)
                                    <span class="badge badge-status badge-blocked">محظور</span>
                                @elseif (! $license->device_id)
                                    <span class="badge badge-status badge-unused">غير مستخدم</span>
                                @elseif ($license->isActive())
                                    <span class="badge badge-status badge-active">فعّال</span>
                                @else
                                    <span class="badge badge-status badge-expired">منتهي</span>
                                @endif
                            </td>
                            <td>{{ optional($license->expires_at)->format('Y-m-d') ?? '—' }}</td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center flex-wrap">
                                    <form action="{{ route('licenses.activate', $license) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="action-btn activate">
                                            <i class="bi bi-check-lg"></i> تفعيل
                                        </button>
                                    </form>
                                    <form action="{{ route('licenses.block', $license) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="action-btn block">
                                            <i class="bi bi-slash-circle"></i> حظر
                                        </button>
                                    </form>
                                    <form action="{{ route('licenses.extend', $license) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="action-btn extend">
                                            <i class="bi bi-calendar-plus"></i> تمديد شهر
                                        </button>
                                    </form>
                                    @role('Super Admin')
                                        <form action="{{ route('licenses.destroy', $license) }}" method="POST"
                                              onsubmit="return confirm('هل أنت متأكد من حذف هذا الكود؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn destroy">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endrole
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">لا توجد أكواد تفعيل بعد.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $licenses->links() }}
    </div>
@endsection
