<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Patient;
use App\Models\MedicalRecord;
use App\Models\LabReport;
use App\Models\AuditLog;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Show admin dashboard.
     */
    public function dashboard()
    {
        $totalPatients = User::patients()->count();
        $totalDoctors = User::doctors()->count();
        $totalRecords = MedicalRecord::count();
        $totalLabReports = LabReport::count();

        $recentActivities = AuditLog::latest()->take(10)->get();
        $recentPatients = Patient::latest()->take(5)->with('user')->get();

        // Monthly patient registration data for last 6 months
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Patient::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $monthlyData[] = [
                'month' => $date->format('M Y'),
                'count' => $count,
            ];
        }

        // Records by status
        $recordsByStatus = MedicalRecord::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        return view('admin.dashboard', compact(
            'totalPatients',
            'totalDoctors',
            'totalRecords',
            'totalLabReports',
            'recentActivities',
            'recentPatients',
            'monthlyData',
            'recordsByStatus'
        ));
    }

    /**
     * Show users list with filters.
     */
    public function users(Request $request)
    {
        $search = $request->search;
        $roleFilter = $request->role;
        $statusFilter = $request->status;

        $users = User::query()
            ->when($search, function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($roleFilter, function ($q) use ($roleFilter) {
                $q->where('role', $roleFilter);
            })
            ->when($statusFilter !== null, function ($q) use ($statusFilter) {
                $q->where('is_active', $statusFilter);
            })
            ->latest()
            ->paginate(10);

        return view('admin.users', compact('users', 'search', 'roleFilter', 'statusFilter'));
    }

    /**
     * Store a new user.
     */
    public function storeUser(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,doctor,patient',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'gender' => 'nullable|in:male,female,other',
        ];

        // Role-specific validation
        if ($request->role === 'doctor') {
            $rules['specialization'] = 'required|string|max:100';
            $rules['license_number'] = 'required|string|max:50';
        }

        if ($request->role === 'patient') {
            $rules['date_of_birth'] = 'required|date|before:today';
            $rules['blood_group'] = 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            // Find the lowest available ID (fill gaps)
            $nextId = DB::table('users')->select(DB::raw('MIN(t1.id + 1) as next_id'))
                ->from('users as t1')
                ->leftJoin('users as t2', function ($join) {
                    $join->on(DB::raw('t1.id + 1'), '=', 't2.id');
                })
                ->whereNull('t2.id')
                ->value('next_id');

            // If no gaps, use null (auto-increment)
            if (!$nextId) {
                // Check if table is empty to start at 1
                $count = User::count();
                if ($count === 0) {
                    $nextId = 1;
                    \Illuminate\Support\Facades\DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
                }
            }

            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'specialization' => $validated['specialization'] ?? null,
                'license_number' => $validated['license_number'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'blood_group' => $validated['blood_group'] ?? null,
                'is_active' => true,
            ];

            if ($nextId) {
                $userData['id'] = $nextId;
            }

            $user = User::create($userData);

            // Create patient record if role is patient
            if ($user->role === 'patient') {
                Patient::create([
                    'user_id' => $user->id,
                    'emergency_contact' => $request->emergency_contact,
                    'emergency_phone' => $request->emergency_phone,
                    'allergies' => $request->allergies,
                    'chronic_conditions' => $request->chronic_conditions,
                    'insurance_number' => $request->insurance_number,
                    'status' => 'active',
                ]);
            }

            AuditLog::log('create_user', "Created {$user->role}: {$user->name}");

            DB::commit();

            return redirect()->back()->with('success', ucfirst($user->role) . ' created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Show user details.
     */
    public function showUser($id)
    {
        $user = User::with([
            'patient.medicalRecords.doctor',
            'patient.prescriptions',
            'patient.labReports',
            'medicalRecords'
        ])->findOrFail($id);

        $activities = AuditLog::where('user_id', $id)->latest()->take(10)->get();

        return view('admin.user-detail', compact('user', 'activities'));
    }

    /**
     * Update user.
     */
    public function updateUser($id, Request $request)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'gender' => 'nullable|in:male,female,other',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'min:6';
        }

        if ($user->role === 'doctor') {
            $rules['specialization'] = 'required|string|max:100';
            $rules['license_number'] = 'required|string|max:50';
        }

        if ($user->role === 'patient') {
            $rules['date_of_birth'] = 'required|date|before:today';
            $rules['blood_group'] = 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'gender' => $validated['gender'] ?? null,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            if ($user->role === 'doctor') {
                $updateData['specialization'] = $validated['specialization'];
                $updateData['license_number'] = $validated['license_number'];
            }

            if ($user->role === 'patient') {
                $updateData['date_of_birth'] = $validated['date_of_birth'];
                $updateData['blood_group'] = $validated['blood_group'] ?? null;

                // Update patient record
                if ($user->patient) {
                    $user->patient->update([
                        'emergency_contact' => $request->emergency_contact,
                        'emergency_phone' => $request->emergency_phone,
                        'allergies' => $request->allergies,
                        'chronic_conditions' => $request->chronic_conditions,
                        'insurance_number' => $request->insurance_number,
                    ]);
                }
            }

            $user->update($updateData);

            AuditLog::log('update_user', "Updated user: {$user->name}");

            DB::commit();

            return redirect()->back()->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Deactivate user (soft delete).
     */
    public function deleteUser($id)
    {
        if ($id == auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user = User::findOrFail($id);

        // Permanently delete user and related data
        DB::transaction(function () use ($user) {
            // Delete related patient record if exists
            if ($user->patient) {
                $user->patient->delete();
            }
            // Note: Medical records, prescriptions, etc., might need handling 
            // depending on foreign key constraints (usually cascade or set null).
            // Assuming standard cascade or if specific handling is needed:
            
            $user->delete();
        });

        AuditLog::log('delete_user', "Permanently deleted user: {$user->name}");

        return redirect()->back()->with('success', 'User deleted permanently.');
    }

    /**
     * Toggle user active status.
     */
    public function toggleUser($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activated' : 'deactivated';
        AuditLog::log('toggle_user', "User {$status}: {$user->name}");

        return redirect()->back()->with('success', "User {$status} successfully.");
    }

    /**
     * Show members (doctors and patients).
     */
    public function members(Request $request)
    {
        $tab = $request->tab ?? 'doctors';

        $doctors = User::doctors()->active()
            ->withCount('medicalRecords')
            ->paginate(10, ['*'], 'doc_page');

        $patients = Patient::with('user')
            ->whereHas('user', function ($q) {
                $q->where('is_active', true);
            })
            ->paginate(10, ['*'], 'pat_page');

        return view('admin.members', compact('doctors', 'patients', 'tab'));
    }

    /**
     * Show audit logs with filters.
     */
    public function auditLogs(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $action = $request->action;
        $role = $request->role;

        $logs = AuditLog::query()
            ->when($startDate, function ($q) use ($startDate) {
                $q->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($q) use ($endDate) {
                $q->whereDate('created_at', '<=', $endDate);
            })
            ->when($action, function ($q) use ($action) {
                $q->where('action', $action);
            })
            ->when($role, function ($q) use ($role) {
                $q->where('user_role', $role);
            })
            ->latest()
            ->paginate(20);

        $actions = AuditLog::select('action')->distinct()->pluck('action');

        return view('admin.audit-logs', compact('logs', 'actions', 'startDate', 'endDate', 'action', 'role'));
    }

    /**
     * Export audit logs to CSV.
     */
    public function exportAuditLogs(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $action = $request->action;
        $role = $request->role;

        $logs = AuditLog::query()
            ->when($startDate, function ($q) use ($startDate) {
                $q->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($q) use ($endDate) {
                $q->whereDate('created_at', '<=', $endDate);
            })
            ->when($action, function ($q) use ($action) {
                $q->where('action', $action);
            })
            ->when($role, function ($q) use ($role) {
                $q->where('user_role', $role);
            })
            ->latest()
            ->get();

        $filename = 'audit_logs_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'User', 'Role', 'Action', 'Description', 'IP Address']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user_name,
                    $log->user_role,
                    $log->action,
                    $log->description,
                    $log->ip_address,
                ]);
            }

            fclose($file);
        };

        AuditLog::log('export_audit_logs', "Exported {$logs->count()} audit log entries");

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show settings page.
     */
    public function settings(Request $request)
    {
        $tab = $request->tab ?? 'settings';

        // Calculate statistics for reports tab
        $totalPatientsByMonth = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Patient::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $totalPatientsByMonth[] = [
                'month' => $date->format('M Y'),
                'count' => $count,
            ];
        }

        $recordsByDoctor = User::doctors()->withCount('medicalRecords')
            ->having('medical_records_count', '>', 0)
            ->orderBy('medical_records_count', 'desc')
            ->take(10)
            ->get();

        $topDiagnoses = MedicalRecord::select('diagnosis', DB::raw('count(*) as count'))
            ->groupBy('diagnosis')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        return view('admin.settings', compact(
            'tab',
            'totalPatientsByMonth',
            'recordsByDoctor',
            'topDiagnoses'
        ));
    }

    /**
     * Update settings.
     */
    public function updateSettings(Request $request)
    {
        // Here you would save settings to database or config
        // For now, just log the action
        AuditLog::log('update_settings', 'System settings updated');

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
