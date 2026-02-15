@extends('layouts.app')
@section('page-title', 'Prescription Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 text-dark">All Prescriptions</h3>
</div>

<!-- Filters -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="{{ route('doctor.prescriptions') }}" method="GET" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search medication or patient..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary w-100">Search</button>
            </div>
        </form>
    </div>
</div>

<!-- Prescriptions Table -->
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Date</th>
                    <th>Patient</th>
                    <th>Medication</th>
                    <th>Dosage</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prescriptions as $script)
                    <tr>
                        <td class="ps-4 text-nowrap text-muted small">
                            {{ $script->created_at->format('M d, Y') }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-3 bg-light text-primary small">
                                    {{ substr($script->patient->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $script->patient->user->name }}</div>
                                    <small class="text-muted">ID: {{ $script->patient->patient_id_number }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="fw-bold">{{ $script->medication_name }}</td>
                        <td>
                            <div>{{ $script->dosage }}</div>
                            <small class="text-muted">{{ $script->frequency }}</small>
                        </td>
                        <td>
                            @if($script->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Ended</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('doctor.records.show', $script->medical_record_id) }}" class="btn btn-sm btn-outline-primary" title="View Related Record">
                                    <i class="fas fa-file-medical"></i>
                                </a>
                                <form action="{{ route('doctor.prescriptions.delete', $script->id) }}" method="POST" class="d-inline" id="delete-script-{{ $script->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('delete-script-{{ $script->id }}')" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-pills fa-2x mb-3 text-secondary"></i>
                            <p>No prescriptions found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white py-3">
        {{ $prescriptions->withQueryString()->links() }}
    </div>
</div>
@endsection
