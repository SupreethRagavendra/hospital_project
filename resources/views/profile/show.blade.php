@extends('layouts.app')
@section('page-title', 'My Profile')

@section('content')
<div class="row g-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center p-5">
                <div class="avatar-circle mx-auto mb-3 bg-primary text-white" style="width: 100px; height: 100px; font-size: 2.5rem;">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                <p class="text-muted mb-2">{{ $user->email }}</p>
                <div class="mb-3">
                    <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'doctor' ? 'info' : 'success') }} px-3 py-2 rounded-pill">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
                <hr>
                <div class="text-start">
                    <p class="mb-2"><strong class="text-muted small">JOINED:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                    @if($user->last_login_at)
                    <p class="mb-2"><strong class="text-muted small">LAST LOGIN:</strong> {{ $user->last_login_at->format('M d, Y h:i A') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white fw-bold py-3">
                Edit Profile Information
            </div>
            <div class="card-body p-4">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-control" value="{{ $user->email }}" disabled readonly>
                        <small class="text-muted">Email cannot be changed directly.</small>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}">
                        </div>
                    </div>

                    @if($user->role === 'patient')
                        <h6 class="text-primary border-bottom pb-2 mt-4 mb-3">Patient Information</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Emergency Contact Name</label>
                                <input type="text" name="emergency_contact" class="form-control" value="{{ old('emergency_contact', $user->patient->emergency_contact ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Emergency Contact Phone</label>
                                <input type="text" name="emergency_phone" class="form-control" value="{{ old('emergency_phone', $user->patient->emergency_phone ?? '') }}">
                            </div>
                        </div>
                    @endif

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white fw-bold py-3 text-danger">
                Change Password
            </div>
            <div class="card-body p-4">
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf @method('PUT')

                    @if($errors->has('current_password'))
                        <div class="alert alert-danger">{{ $errors->first('current_password') }}</div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required minlength="6">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" class="form-control" required minlength="6">
                        </div>
                    </div>
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-outline-danger px-4">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection