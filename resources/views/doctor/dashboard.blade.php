@extends('layouts.app')
@section('page-title', 'Doctor Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        @include('components.stats-card', [
            'count' => $myPatientsCount, 
            'label' => 'My Patients', 
            'icon' => 'users', 
            'color' => 'primary'
        ])
    </div>
    <div class="col-md-3">
        @include('components.stats-card', [
            'count' => $myRecordsCount, 
            'label' => 'Medical Records', 
            'icon' => 'file-medical', 
            'color' => 'success'
        ])
    </div>
    <div class="col-md-3">
        @include('components.stats-card', [
            'count' => $myPrescriptionsCount, 
            'label' => 'Prescriptions', 
            'icon' => 'prescription', 
            'color' => 'info'
        ])
    </div>
    <div class="col-md-3">
        @include('components.stats-card', [
            'count' => $pendingLabsCount, 
            'label' => 'Pending Labs', 
            'icon' => 'vial', 
            'color' => 'warning'
        ])
    </div>
</div>

<div class="row g-4">
    <!-- Today's Appointments -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fas fa-calendar-check me-2 text-primary"></i> Today's Follow-Ups
            </div>
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Patient</th>
                            <th>Recent Diagnosis</th>
                            <th>Last Visit</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todayAppointments as $record)
                            <tr>
                                <td class="ps-4 fw-bold">
                                    <a href="{{ route('doctor.patients.show', $record->patient_id) }}" class="text-decoration-none text-dark">
                                        {{ $record->patient->user->name }}
                                    </a>
                                </td>
                                <td>{{ Str::limit($record->diagnosis, 30) }}</td>
                                <td class="text-muted small">
                                    {{ $record->record_date->format('M d, Y') }}
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('doctor.records.create', ['patient_id' => $record->patient_id]) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-plus me-1"></i> New Record
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fas fa-calendar-times fa-2x mb-3 text-secondary"></i>
                                    <p>No follow-ups scheduled for today.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Patients -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fas fa-user-clock me-2 text-info"></i> Recent Patients
            </div>
            <ul class="list-group list-group-flush">
                @forelse($recentPatients as $record)
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle me-3 bg-light text-primary small">
                                {{ substr($record->patient->user->name, 0, 1) }}
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $record->patient->user->name }}</h6>
                                <small class="text-muted">Last: {{ $record->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <a href="{{ route('doctor.patients.show', $record->patient_id) }}" class="btn btn-sm btn-light border">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </li>
                @empty
                    <li class="list-group-item text-center py-4 text-muted">No recent patients found.</li>
                @endforelse
            </ul>
            <div class="card-footer bg-white text-center border-top-0 py-3">
                <a href="{{ route('doctor.patients') }}" class="text-decoration-none fw-bold small">View All Patients <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
</div>
@endsection
