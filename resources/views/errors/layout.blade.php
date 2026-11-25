<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('code') - @yield('title') | ClinicCare</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .card {
            background: #ffffff;
            width: 100%;
            max-width: 400px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
            padding: 32px 28px;
            text-align: center;
        }
        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            text-align: left;
        }
        .logo {
            width: 34px;
            height: 34px;
            background: #2563eb;
            color: #ffffff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
        }
        .brand-title {
            font-weight: 700;
            font-size: 16px;
            color: #0f172a;
            line-height: 1.2;
        }
        .brand-sub {
            font-size: 10px;
            font-weight: 600;
            color: #2563eb;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .badge {
            display: inline-block;
            background: #eff6ff;
            color: #1d4ed8;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 999px;
            margin-bottom: 12px;
        }
        h1 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #0f172a;
        }
        p {
            font-size: 13.5px;
            color: #475569;
            line-height: 1.5;
            margin-bottom: 24px;
        }
        .actions {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.15s ease;
        }
        .btn-primary {
            background: #2563eb;
            color: #ffffff;
            border: 1px solid #2563eb;
        }
        .btn-primary:hover {
            background: #1d4ed8;
        }
        .btn-secondary {
            background: #f8fafc;
            color: #334155;
            border: 1px solid #cbd5e1;
        }
        .btn-secondary:hover {
            background: #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="brand">
            <div class="logo">+</div>
            <div>
                <div class="brand-title">ClinicCare</div>
                <div class="brand-sub">Medical Center Portal</div>
            </div>
        </div>

        <div>
            <span class="badge">ERROR @yield('code')</span>
        </div>

        <h1>@yield('title')</h1>
        <p>@yield('message')</p>

        <div class="actions">
            @auth
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="btn btn-primary">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">
                    Go to Login
                </a>
            @endauth

            <button type="button" onclick="window.history.back()" class="btn btn-secondary">
                Go Back
            </button>
        </div>
    </div>
</body>
</html>