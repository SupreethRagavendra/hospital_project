@extends('layouts.app')
@section('page-title', 'Medical Records')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 text-dark">My Medical Records</h3>
    <a href="{{ route('doctor.records.create', ['patient_id' => 0]) }}" class="btn btn-primary d-none">
        <i class="fas fa-plus me-2"></i> Create New Record
    </a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="{{ route('doctor.records') }}" method="GET" class="row g-3">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search by patient name or diagnosis..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Statuses</option>
                    @foreach(['active', 'completed', 'follow_up'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">Filter</button>
            </div>
             <div class="col-md-2">
                <a href="{{ route('doctor.records') }}" class="btn btn-light w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Date</th>
                    <th>Patient</th>
                    <th>Diagnosis</th>
                    <th>Status</th>
                    <th>Follow-Up</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                    <tr>
                        <td class="ps-4 text-nowrap text-muted small">
                            {{ $record->created_at->format('M d, Y') }}<br>
                            {{ $record->created_at->format('H:i') }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-3 bg-light text-primary small">
                                    {{ substr($record->patient->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $record->patient->user->name }}</div>
                                    <small class="text-muted">ID: {{ $record->patient->patient_id_number }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="fw-bold text-dark">{{ Str::limit($record->diagnosis, 40) }}</td>
                        <td>
                            <span class="badge bg-{{ $record->status == 'active' ? 'primary' : ($record->status == 'follow_up' ? 'warning' : 'success') }}">
                                {{ ucfirst(str_replace('_', ' ', $record->status)) }}
                            </span>
                        </td>
                        <td class="text-muted small">
                            {{ $record->follow_up_date ? $record->follow_up_date->format('M d, Y') : '-' }}
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('doctor.records.show', $record->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('doctor.records.edit', $record->id) }}" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('doctor.records.delete', $record->id) }}" method="POST" class="d-inline" id="delete-record-{{ $record->id }}">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete('delete-record-{{ $record->id }}')" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-file-medical-alt fa-2x mb-3 text-secondary"></i>
                            <p>No medical records found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white py-3">
        {{ $records->withQueryString()->links() }}
    </div>
</div>
@endsection