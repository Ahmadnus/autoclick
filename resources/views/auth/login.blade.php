<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - لوحة تحكم التراخيص</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --indigo: #6366f1;
            --indigo-dark: #4f46e5;
            --indigo-soft: rgba(99, 102, 241, .12);
        }
        body {
            font-family: 'Cairo', sans-serif;
            background: radial-gradient(circle at 20% 20%, #1c2942 0%, #0b1120 55%, #070c17 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(600px circle at 85% 15%, rgba(99, 102, 241, .18), transparent 60%),
                radial-gradient(500px circle at 10% 85%, rgba(16, 185, 129, .12), transparent 60%);
            pointer-events: none;
        }
        .login-card {
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 20px;
            background: rgba(17, 26, 44, .55);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, .4);
            position: relative;
            z-index: 1;
        }
        .login-card .icon-badge {
            width: 64px;
            height: 64px;
            border-radius: 18px;
            background: var(--indigo-soft);
            color: #a5b4fc;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
        }
        .login-card h4 { color: #f1f5f9; font-weight: 800; }
        .login-card .text-muted { color: #94a3b8 !important; }
        .login-card .form-label { color: #cbd5e1; font-weight: 600; font-size: .88rem; }
        .login-card .form-control {
            background: rgba(255, 255, 255, .04);
            border: 1px solid rgba(255, 255, 255, .1);
            color: #f1f5f9;
            border-radius: 10px;
            padding: .65rem .9rem;
        }
        .login-card .form-control::placeholder { color: #64748b; }
        .login-card .form-control:focus {
            background: rgba(255, 255, 255, .06);
            border-color: var(--indigo);
            box-shadow: 0 0 0 .2rem var(--indigo-soft);
            color: #f1f5f9;
        }
        .login-card .form-check-label { color: #94a3b8; }
        .login-card .btn-primary {
            background: linear-gradient(135deg, var(--indigo), var(--indigo-dark));
            border: none;
            border-radius: 10px;
            font-weight: 700;
            padding: .7rem;
            box-shadow: 0 8px 20px rgba(99, 102, 241, .35);
        }
        .login-card .alert-danger {
            background: rgba(225, 29, 72, .12);
            border: 1px solid rgba(225, 29, 72, .25);
            color: #fda4af;
            border-radius: 12px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card login-card p-4">
                <div class="text-center mb-4">
                    <div class="icon-badge mb-3"><i class="bi bi-shield-lock"></i></div>
                    <h4 class="mb-0">لوحة تحكم التراخيص</h4>
                    <p class="text-muted small mb-0">سجّل الدخول للمتابعة</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger py-2">
                        <ul class="mb-0 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.submit') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">كلمة المرور</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label" for="remember">تذكرني</label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">تسجيل الدخول</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
