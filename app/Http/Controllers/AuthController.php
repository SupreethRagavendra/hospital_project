<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\AuditLog;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Rate limiting
        $key = Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ])->withInput($request->only('email'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            // Clear rate limiter on successful login
            RateLimiter::clear($key);

            // Update last login time
            $user = Auth::user();
            $user->last_login_at = now();
            $user->save();

            // Regenerate session to prevent session fixation
            $request->session()->regenerate();

            // Log successful login
            AuditLog::log('login', 'User logged in successfully');

            return redirect()->intended('/dashboard');
        }

        // Increment rate limiter on failed attempt
        RateLimiter::hit($key, 60);

        // Log failed login attempt
        AuditLog::create([
            'user_id' => null,
            'user_name' => 'Unknown',
            'user_role' => 'unknown',
            'action' => 'login_failed',
            'description' => "Failed login attempt for email: {$request->email}",
            'ip_address' => $request->ip(),
        ]);

        return back()->withErrors([
            'email' => 'Invalid credentials. Please check your email and password.',
        ])->withInput($request->only('email'));
    }

    /**
     * Redirect to appropriate dashboard based on role.
     */
    public function redirectDashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isDoctor()) {
            return redirect()->route('doctor.dashboard');
        } elseif ($user->isPatient()) {
            return redirect()->route('patient.dashboard');
        }

        return redirect()->route('login');
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        // Log logout action before logging out
        AuditLog::log('logout', 'User logged out');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }
}
