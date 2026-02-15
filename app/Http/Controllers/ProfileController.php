<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\AuditLog;

class ProfileController extends Controller
{
   
    public function show()
    {
        $user = auth()->user();

        // Load patient record if user is a patient
        if ($user->isPatient()) {
            $user->load('patient');
        }

        return view('profile.show', compact('user'));
    }

    /**
     * Update profile.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = [
            'name' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ];

        // Patient-specific fields
        if ($user->isPatient()) {
            $rules['emergency_contact'] = 'nullable|string|max:100';
            $rules['emergency_phone'] = 'nullable|string|max:20';
        }

        $validated = $request->validate($rules);

        try {
            // Update user fields
            $user->update([
                'name' => $validated['name'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
            ]);

            // Update patient record if patient
            if ($user->isPatient() && $user->patient) {
                $user->patient->update([
                    'emergency_contact' => $request->emergency_contact,
                    'emergency_phone' => $request->emergency_phone,
                ]);
            }

            AuditLog::log('update_profile', 'Updated own profile');

            return redirect()->back()->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    /**
     * Change password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = auth()->user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors([
                'current_password' => 'Current password is incorrect.',
            ]);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        AuditLog::log('change_password', 'Changed own password');

        return redirect()->back()->with('success', 'Password changed successfully.');
    }
}
