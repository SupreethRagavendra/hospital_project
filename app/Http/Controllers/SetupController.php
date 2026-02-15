<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\AuditLog;

class SetupController extends Controller
{
    public function index()
    {
        return view('setup.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'hospital_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        // Store Hospital Name
        \App\Models\Setting::updateOrCreate(
            ['key' => 'hospital_name'],
            ['value' => $request->hospital_name]
        );

        // Fix: Reset auto-increment to 1 if no users exist
        if (User::count() === 0) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
        }

        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        AuditLog::create([
            'user_id' => $admin->id,
            'user_name' => $admin->name,
            'user_role' => 'admin',
            'action' => 'setup_completed',
            'description' => 'Initial system setup completed. Admin account created.',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('login')->with('success', 'System Setup Complete! Please login with your new Admin account.');
    }
}
