<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة التحكم') - لوحة تحكم التراخيص</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy-950: #0b1120;
            --navy-900: #111a2c;
            --navy-800: #182338;
            --navy-700: #22304a;
            --indigo: #6366f1;
            --indigo-dark: #4f46e5;
            --indigo-soft: rgba(99, 102, 241, .12);
            --emerald: #10b981;
            --emerald-soft: rgba(16, 185, 129, .12);
            --crimson: #e11d48;
            --crimson-soft: rgba(225, 29, 72, .12);
            --amber: #f59e0b;
            --amber-soft: rgba(245, 158, 11, .12);
            --slate-50: #f6f8fb;
            --slate-100: #eef1f6;
            --slate-400: #94a3b8;
            --slate-500: #64748b;
        }

        body { font-family: 'Cairo', sans-serif; background-color: var(--slate-50); color: #1e293b; }

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--navy-900) 0%, var(--navy-950) 100%);
            box-shadow: 2px 0 24px rgba(11, 17, 32, .15);
        }
        .sidebar .brand {
            font-weight: 800;
            letter-spacing: .2px;
        }
        .sidebar .brand i {
            background: var(--indigo-soft);
            color: #a5b4fc;
            border-radius: 10px;
            padding: 8px;
            font-size: 1.1rem;
        }
        .sidebar .nav-link {
            color: #aab4c8;
            border-radius: 10px;
            padding: .65rem .9rem;
            font-weight: 500;
            transition: background-color .15s ease, color .15s ease, transform .15s ease;
        }
        .sidebar .nav-link:hover { color: #fff; background-color: var(--navy-700); transform: translateX(-2px); }
        .sidebar .nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, var(--indigo), var(--indigo-dark));
            box-shadow: 0 4px 14px rgba(99, 102, 241, .35);
        }
        .sidebar hr { border-color: rgba(255, 255, 255, .08); opacity: 1; }
        .sidebar .role-badge {
            background: var(--indigo-soft);
            color: #c7d2fe;
            font-weight: 600;
            border-radius: 999px;
            padding: .25rem .65rem;
        }
        .sidebar .btn-outline-light {
            border-radius: 10px;
            border-color: rgba(255, 255, 255, .18);
        }
        .sidebar .btn-outline-light:hover { background-color: var(--crimson); border-color: var(--crimson); }

        main.content-area { background-color: var(--slate-50); }
        .page-title { font-weight: 800; color: #0f172a; }

        .card { border: none; }
        .stat-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 1px 2px rgba(15, 23, 42, .04), 0 8px 24px rgba(15, 23, 42, .06);
            transition: transform .18s ease, box-shadow .18s ease;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(15, 23, 42, .06), 0 16px 32px rgba(15, 23, 42, .1);
        }

        .btn { border-radius: 10px; font-weight: 600; }
        .btn-primary { background: linear-gradient(135deg, var(--indigo), var(--indigo-dark)); border: none; }
        .btn-primary:hover { background: linear-gradient(135deg, var(--indigo-dark), var(--indigo-dark)); }
        .btn-outline-primary { color: var(--indigo-dark); border-color: var(--indigo); }
        .btn-outline-primary:hover { background: var(--indigo); border-color: var(--indigo); }

        .badge { font-weight: 600; border-radius: 999px; padding: .4em .75em; }

        .alert { border: none; border-radius: 14px; }

        code { direction: ltr; unicode-bidi: plaintext; }
    </style>
    @stack('styles')
</head>
<body>
<div class="d-flex">
    <nav class="sidebar p-3" style="width: 260px;">
        <h5 class="text-white mb-4 brand">
            <i class="bi bi-shield-lock ms-2"></i> لوحة تحكم التراخيص
        </h5>
        <ul class="nav nav-pills flex-column gap-1">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2 ms-2"></i> لوحة المعلومات
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('licenses.index') ? 'active' : '' }}" href="{{ route('licenses.index') }}">
                    <i class="bi bi-people ms-2"></i> إدارة السائقين
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('licenses.create') ? 'active' : '' }}" href="{{ route('licenses.create') }}">
                    <i class="bi bi-plus-circle ms-2"></i> إضافة جهاز جديد
                </a>
            </li>
        </ul>
        <hr>
        <div class="text-white-50 small mb-3 px-1">
            <div class="text-white fw-semibold mb-1">{{ auth()->user()->name }}</div>
            @foreach (auth()->user()->getRoleNames() as $role)
                <span class="role-badge small">{{ $role }}</span>
            @endforeach
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-light btn-sm w-100">
                <i class="bi bi-box-arrow-right ms-1"></i> تسجيل الخروج
            </button>
        </form>
    </nav>

    <main class="flex-grow-1 p-4 content-area">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 page-title">@yield('title')</h4>
        </div>

        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
