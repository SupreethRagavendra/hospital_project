@extends('layouts.app')
@section('page-title', 'User Details')

@section('content')
<div class="row g-4">
    <!-- User Profile Card -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0 mb-4 h-100">
            <div class="card-body text-center p-5">
                <div class="avatar-circle mx-auto mb-3 bg-primary text-white" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-2">{{ $user->email }}</p>
                <div class="mb-3">
                    <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'doctor' ? 'info' : 'success') }} px-3 py-2 rounded-pill">
                        {{ ucfirst($user->role) }}
                    </span>
                    <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }} px-3 py-2 rounded-pill ms-1">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                
                <hr class="my-4">
                
                <div class="text-start">
                    <div class="mb-3">
                        <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.7rem;">PHONE</small>
                        <span>{{ $user->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.7rem;">ADDRESS</small>
                        <span>{{ $user->address ?? 'N/A' }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.7rem;">JOINED DATE</small>
                        <span>{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block uppercase fw-bold" style="font-size: 0.7rem;">LAST LOGIN</small>
                        <span>{{ $user->last_login_at ? $user->last_login_at->format('M d, Y h:i A') : 'Never' }}</span>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editUserModal">
                        <i class="fas fa-edit me-2"></i> Edit Profile
                    </button>
                    @if(auth()->id() !== $user->id)
                    <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="btn btn-outline-warning w-100">
                            <i class="fas fa-power-off me-2"></i> {{ $user->is_active ? 'Deactivate User' : 'Activate User' }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Details Column -->
    <div class="col-md-8">
        <!-- Role Specific Details -->
        @if($user->role === 'doctor')
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fas fa-stethoscope me-2 text-info"></i> Doctor Information
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold">SPECIALIZATION</label>
                        <p class="fs-5 mb-0">{{ $user->specialization ?? 'General' }}</p>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold">LICENSE NUMBER</label>
                        <p class="fs-5 mb-0">{{ $user->license_number ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
        @elseif($user->role === 'patient' && $user->patient)
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fas fa-user-injured me-2 text-success"></i> Patient Details
            </div>
            <div class="card-body">
                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <label class="small text-muted fw-bold">PATIENT ID</label>
                        <p class="fs-6 mb-0">{{ $user->patient->patient_id_number }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted fw-bold">BLOOD GROUP</label>
                        <p class="fs-6 mb-0">{{ $user->blood_group ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted fw-bold">DATE OF BIRTH</label>
                        <p class="fs-6 mb-0">{{ $user->date_of_birth ? $user->date_of_birth->format('M d, Y') : 'N/A' }} ({{ $user->age ?? '?' }} yrs)</p>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold">EMERGENCY CONTACT</label>
                        <p class="mb-0">{{ $user->patient->emergency_contact }}</p>
                        <small class="text-muted">{{ $user->patient->emergency_phone }}</small>
                    </div>
                    <div class="col-md-6">
                        <label class="small text-muted fw-bold">INSURANCE</label>
                        <p class="mb-0">{{ $user->patient->insurance_number ?? 'N/A' }}</p>
                    </div>
                    <div class="col-12">
                        <label class="small text-muted fw-bold">ALLERGIES</label>
                        <p class="mb-0 text-danger">{{ $user->patient->allergies ?? 'None' }}</p>
                    </div>
                    <div class="col-12">
                        <label class="small text-muted fw-bold">CHRONIC CONDITIONS</label>
                        <p class="mb-0">{{ $user->patient->chronic_conditions ?? 'None' }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Recent Activity Logs -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                <span><i class="fas fa-history me-2 text-secondary"></i> Recent Activity</span>
                <a href="{{ route('admin.audit-logs', ['user_id' => $user->id]) }}" class="btn btn-sm btn-light">View All</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Action</th>
                            <th>Description</th>
                            <th>IP Address</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $log)
                            <tr>
                                <td class="ps-4 fw-bold small text-uppercase">{{ $log->action }}</td>
                                <td class="small">{{ Str::limit($log->description, 50) }}</td>
                                <td class="small font-monospace text-muted">{{ $log->ip_address }}</td>
                                <td class="small text-muted">{{ $log->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No activity recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Edit User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <small class="text-muted">(Leave blank to keep current)</small></label>
                            <input type="password" name="password" class="form-control" minlength="6">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2">{{ $user->address }}</textarea>
                        </div>

                        @if($user->role === 'doctor')
                            <h6 class="text-primary border-bottom pb-2 mt-3">Doctor Details</h6>
                            <div class="col-md-6">
                                <label class="form-label">Specialization</label>
                                <input type="text" name="specialization" class="form-control" value="{{ $user->specialization }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">License Number</label>
                                <input type="text" name="license_number" class="form-control" value="{{ $user->license_number }}">
                            </div>
                        @endif

                        @if($user->role === 'patient')
                            <h6 class="text-primary border-bottom pb-2 mt-3">Patient Details</h6>
                            <div class="col-md-6">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control" value="{{ $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Blood Group</label>
                                <select name="blood_group" class="form-select">
                                    <option value="">Select</option>
                                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                        <option value="{{ $bg }}" {{ $user->blood_group == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Emergency Contact</label>
                                <input type="text" name="emergency_contact" class="form-control" value="{{ $user->patient->emergency_contact ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Emergency Phone</label>
                                <input type="text" name="emergency_phone" class="form-control" value="{{ $user->patient->emergency_phone ?? '' }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Allergies</label>
                                <textarea name="allergies" class="form-control" rows="2">{{ $user->patient->allergies ?? '' }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Chronic Conditions</label>
                                <textarea name="chronic_conditions" class="form-control" rows="2">{{ $user->patient->chronic_conditions ?? '' }}</textarea>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
