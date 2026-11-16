<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // 1. Authenticate user credentials (email & password)
        $request->authenticate();

        // 2. Fetch logged-in user
        $user = Auth::user();

        // 3. Strict Role Matching Validation
        // If the user attempts to log in under a role that doesn't match their account role:
        if ($request->has('role') && $user->role !== $request->role) {
            
            // Log out immediately
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Return error message to login page
            throw ValidationException::withMessages([
                'email' => ["Access denied: This account is registered as '{$user->role}'. Please use the correct login tab."],
            ]);
        }

        // 4. Regenerate session and redirect to appropriate dashboard
        $request->session()->regenerate();

        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session (Log Out).
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Redirect directly to login page
        return redirect()->route('login');
    }
}