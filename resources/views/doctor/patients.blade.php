@extends('layouts.app')
@section('page-title', 'My Patients')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 text-dark">Patient Management</h3>
    <a href="{{ route('doctor.records.create', ['patient_id' => 0]) }}" class="btn btn-primary d-none">
        <i class="fas fa-plus me-2"></i> New Patient
    </a>
</div>

<!-- Search & Filter -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="{{ route('doctor.patients') }}" method="GET" class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search by name, email, or patient ID..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="active" id="activeOnly" value="1" {{ request('active') ? 'checked' : '' }}>
                    <label class="form-check-label" for="activeOnly">Active Patients Only</label>
                </div>
            </div>
            <div class="col-md-3 text-end">
                <button type="submit" class="btn btn-secondary px-4 me-2">Search</button>
                <a href="{{ route('doctor.patients') }}" class="btn btn-light">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Patients List -->
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Patient Name</th>
                    <th>Patient ID</th>
                    <th>Contact Info</th>
                    <th>Last Visit</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-3 bg-light text-primary">
                                    {{ substr($patient->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $patient->user->name }}</div>
                                    <small class="text-muted">{{ $patient->user->age }} yrs / {{ ucfirst($patient->user->gender) }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="font-monospace fw-bold text-secondary">{{ $patient->patient_id_number }}</td>
                        <td>
                            <div>{{ $patient->user->phone ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $patient->emergency_phone ?? 'No Emergency Contact' }}</small>
                        </td>
                        <td>
                            {{ $patient->medicalRecords->first() ? $patient->medicalRecords->first()->created_at->diffForHumans() : 'Never' }}
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('doctor.patients.show', $patient->id) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                    <i class="fas fa-eye"></i> Details
                                </a>
                                <a href="{{ route('doctor.records.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-outline-success" title="Add Record">
                                    <i class="fas fa-file-medical"></i> Add Record
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-user-slash fa-2x mb-3 text-secondary"></i>
                            <p>No patients found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white py-3">
        {{ $patients->withQueryString()->links() }}
    </div>
</div>
@endsection
