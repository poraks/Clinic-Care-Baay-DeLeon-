<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <style>
        .role-tab {
            flex: 1;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 700;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            cursor: pointer;
            transition: all 0.15s ease;
            text-align: center;
        }
        .tab-patient-active {
            background-color: #2563eb !important;
            color: #ffffff !important;
            border-color: #2563eb !important;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
        }
        .tab-admin-active {
            background-color: #063ed9 !important;
            color: #ffffff !important;
            border-color: #2563eb !important;
            box-shadow: 0 2px 4px rgba(217, 119, 6, 0.2);
        }
        .tab-inactive {
            background-color: #f8fafc !important;
            color: #475569 !important;
            border-color: #cbd5e1 !important;
        }
        .tab-inactive:hover {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
        }
    </style>

    <div style="margin-bottom: 24px;">
        <!-- Role Selection Tabs -->
        <div style="display: flex; gap: 10px;">
            <button type="button" id="tab-patient" onclick="selectRole('patient')" class="role-tab tab-patient-active">
                Patient / User Login
            </button>
            <button type="button" id="tab-admin" onclick="selectRole('admin')" class="role-tab tab-inactive">
                Admin / Staff Login
            </button>
        </div>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Hidden Input for Selected Role -->
        <input type="hidden" name="role" id="selected_role" value="patient">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            <div class="flex flex-col space-y-1">
                @if (Route::has('password.request'))
                    <a class="underline text-xs text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
                
                @if (Route::has('register'))
                    <a class="underline text-xs font-semibold text-indigo-600 hover:text-indigo-800" href="{{ route('register') }}">
                        {{ __("Don't have an account? Register") }}
                    </a>
                @endif
            </div>

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Interactive Tab Switcher Script -->
    <script>
        function selectRole(role) {
            document.getElementById('selected_role').value = role;

            const btnPatient = document.getElementById('tab-patient');
            const btnAdmin = document.getElementById('tab-admin');

            if (role === 'patient') {
                btnPatient.className = "role-tab tab-patient-active";
                btnAdmin.className = "role-tab tab-inactive";
            } else {
                btnAdmin.className = "role-tab tab-admin-active";
                btnPatient.className = "role-tab tab-inactive";
            }
        }
    </script>
</x-guest-layout>