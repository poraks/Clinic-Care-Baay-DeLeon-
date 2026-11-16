<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ClinicCare System') }}</title>

    <!-- Fonts & Scripts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Inline Styles -->
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; margin: 0; padding: 0; }
        .app-container { display: flex; min-height: 100vh; }
        
        /* SIDEBAR STYLING */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            color: #94a3b8;
            display: flex;
            flex-direction: column;
            border-right: 1px solid #334155;
            flex-shrink: 0;
        }
        .sidebar-brand {
            padding: 20px 24px;
            background-color: #020617;
            border-bottom: 1px solid #1e293b;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .brand-icon {
            width: 38px;
            height: 38px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 800;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
        }
        .brand-title { font-weight: 800; font-size: 18px; color: #ffffff; margin: 0; line-height: 1.2; }
        .brand-subtitle { font-size: 10px; font-weight: 700; color: #60a5fa; text-transform: uppercase; letter-spacing: 0.5px; }
        
        .role-badge-box { padding: 12px 20px; border-bottom: 1px solid #1e293b; background: rgba(15, 23, 42, 0.5); }
        .badge-admin { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        .badge-patient { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
        
        .nav-section-title { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.8px; padding: 20px 20px 8px 20px; }
        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            margin: 2px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .nav-item:hover { background-color: #1e293b; color: #ffffff; }
        .nav-item.active { background-color: #2563eb; color: #ffffff; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3); }
        .nav-icon { width: 18px; height: 18px; margin-right: 12px; }

        /* MAIN BODY STYLING */
        .main-workspace { flex: 1; display: flex; flex-direction: column; min-width: 0; }
        .top-header {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .header-title { font-size: 20px; font-weight: 800; color: #0f172a; margin: 0; }
        .content-area { padding: 32px; flex: 1; }
    </style>
</head>
<body>
    <div class="app-container">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="brand-icon">+</div>
                <div>
                    <h1 class="brand-title">ClinicCare</h1>
                    <span class="brand-subtitle">Medical Center Portal</span>
                </div>
            </div>

            <div class="role-badge-box">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 11px; color: #94a3b8; font-weight: 600;">Account Role</span>
                    <span class="{{ Auth::user()->role === 'admin' ? 'badge-admin' : 'badge-patient' }}">
                        {{ Auth::user()->role }}
                    </span>
                </div>
            </div>

            <nav style="flex: 1;">
                <div class="nav-section-title">Main Menu</div>

                @if(Auth::user()->role === 'admin')
                    <!-- ADMIN SIDEBAR -->
                    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Manage Appointments
                    </a>

                    <a href="{{ route('prescriptions.index') }}" class="nav-item {{ request()->routeIs('prescriptions.index') ? 'active' : '' }}">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Prescriptions
                    </a>
                @else
                    <!-- PATIENT SIDEBAR -->
                    <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Book Appointment
                    </a>

                    <a href="{{ route('prescriptions.index') }}" class="nav-item {{ request()->routeIs('prescriptions.index') ? 'active' : '' }}">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        My Prescriptions
                    </a>
                @endif

                <div class="nav-section-title">Settings</div>

                <a href="{{ route('profile.edit') }}" class="nav-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Profile Settings
                </a>
            </nav>

            <div style="padding: 16px; border-top: 1px solid #1e293b; background-color: #020617;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 32px; height: 32px; border-radius: 50%; background-color: #2563eb; color: #fff; font-weight: 700; display: flex; align-items: center; justify-content: center; font-size: 13px;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                        <div style="font-size: 13px; font-weight: 700; color: #ffffff;">{{ Auth::user()->name }}</div>
                        <div style="font-size: 11px; color: #64748b;">{{ Auth::user()->email }}</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="main-workspace">
            <header class="top-header">
                <h1 class="header-title">{{ $header ?? 'Dashboard' }}</h1>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button style="display: flex; align-items: center; gap: 8px; padding: 8px 16px; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer;">
                            <span style="width: 8px; height: 8px; border-radius: 50%; background-color: #10b981;"></span>
                            {{ Auth::user()->name }}
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profile Settings
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" style="color: #ef4444;">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </header>

            <main class="content-area">
                {{ $slot }}
            </main>
        </div>

    </div>
</body>
</html>