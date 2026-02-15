@extends('layouts.app')
@section('page-title', 'User Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 text-dark">User Management</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
        <i class="fas fa-plus me-2"></i> Create New User
    </button>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="{{ route('admin.users') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search name or email..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="doctor" {{ request('role') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                    <option value="patient" {{ request('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Name</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-3">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $user->name }}</div>
                                    <small class="text-muted">ID: #{{ $user->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-soft badge-soft-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'doctor' ? 'info' : 'success') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->is_active)
                                <span class="badge-soft badge-soft-success">Active</span>
                            @else
                                <span class="badge-soft badge-soft-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-muted small">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                        </td>
                        <td class="text-end pe-4">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border-0" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                    <li><a class="dropdown-item" href="{{ route('admin.users.show', $user->id) }}"><i class="fas fa-eye me-2 text-primary"></i> View Details</a></li>
                                    <li>
                                        <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button class="dropdown-item text-warning"><i class="fas fa-power-off me-2"></i> {{ $user->is_active ? 'Deactivate' : 'Activate' }}</button>
                                        </form>
                                    </li>
                                    @if(auth()->id() !== $user->id)
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" id="delete-form-{{ $user->id }}">
                                            @csrf @method('DELETE')
                                            <button type="button" class="dropdown-item text-danger" onclick="confirmDelete('delete-form-{{ $user->id }}')">
                                                <i class="fas fa-trash-alt me-2"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-users-slash fa-2x mb-3"></i>
                            <p>No users found matching your criteria.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white py-3">
        {{ $users->withQueryString()->links() }}
    </div>
</div>

<div class="modal fade" id="createUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Create New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required minlength="6">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role <span class="text-danger">*</span></label>
                            <select name="role" class="form-select" id="roleSelect" required>
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="doctor">Doctor</option>
                                <option value="patient">Patient</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="col-12 role-fields d-none" id="doctorFields">
                            <h6 class="text-primary border-bottom pb-2 mt-2">Doctor Details</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Specialization <span class="text-danger">*</span></label>
                                    <input type="text" name="specialization" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">License Number <span class="text-danger">*</span></label>
                                    <input type="text" name="license_number" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="col-12 role-fields d-none" id="patientFields">
                            <h6 class="text-primary border-bottom pb-2 mt-2">Patient Details</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                    <input type="date" name="date_of_birth" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Blood Group</label>
                                    <select name="blood_group" class="form-select">
                                        <option value="">Select Blood Group</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Emergency Contact Name</label>
                                    <input type="text" name="emergency_contact" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Emergency Phone</label>
                                    <input type="text" name="emergency_phone" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Allergies</label>
                                    <textarea name="allergies" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Chronic Conditions</label>
                                    <textarea name="chronic_conditions" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Insurance Number</label>
                                    <input type="text" name="insurance_number" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Create User</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('roleSelect').addEventListener('change', function() {

        document.querySelectorAll('.role-fields').forEach(el => el.classList.add('d-none'));

        if (this.value === 'doctor') {
            document.getElementById('doctorFields').classList.remove('d-none');

            document.querySelector('[name="specialization"]').required = true;
            document.querySelector('[name="license_number"]').required = true;

            document.querySelector('[name="date_of_birth"]').required = false;
        } else if (this.value === 'patient') {
            document.getElementById('patientFields').classList.remove('d-none');

            document.querySelector('[name="date_of_birth"]').required = true;

            document.querySelector('[name="specialization"]').required = false;
            document.querySelector('[name="license_number"]').required = false;
        } else {

            document.querySelector('[name="specialization"]').required = false;
            document.querySelector('[name="license_number"]').required = false;
            document.querySelector('[name="date_of_birth"]').required = false;
        }
    });
</script>
@endsection