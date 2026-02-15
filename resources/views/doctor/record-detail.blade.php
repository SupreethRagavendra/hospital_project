@extends('layouts.app')
@section('page-title', 'Record Details')

@section('content')
<div class="row g-4 justify-content-center">
    <!-- Main Record Info -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4 h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-file-medical-alt me-2 text-primary"></i> Medical Record #{{ $record->id }}
                </h5>
                <div>
                     <span class="badge bg-{{ $record->status == 'active' ? 'primary' : ($record->status == 'completed' ? 'success' : 'warning') }} fs-6 px-3">
                        {{ ucfirst(str_replace('_', ' ', $record->status)) }}
                    </span>
                    @if($record->confidential)
                        <span class="badge bg-danger ms-2"><i class="fas fa-lock me-1"></i> Confidential</span>
                    @endif
                </div>
            </div>
            <div class="card-body p-4">
                <!-- Patient Header (Small) -->
                <div class="alert alert-light border d-flex align-items-center mb-4">
                    <div class="avatar-circle me-3 bg-secondary text-white small">
                        {{ substr($record->patient->user->name, 0, 1) }}
                    </div>
                    <div>
                        <strong>{{ $record->patient->user->name }}</strong>
                        <span class="text-muted mx-2">|</span>
                        <span class="text-muted">{{ $record->patient->user->age }} yrs, {{ ucfirst($record->patient->user->gender) }}</span>
                    </div>
                    <div class="ms-auto">
                        <small class="text-muted">Recorded on: {{ $record->created_at->format('M d, Y H:i') }}</small>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="text-uppercase text-muted fw-bold small mb-2">Chief Complaint</h6>
                    <p class="fs-5">{{ $record->chief_complaint }}</p>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <h6 class="text-uppercase text-muted fw-bold small mb-2">Diagnosis</h6>
                        <div class="p-3 bg-light rounded border-start border-4 border-info">
                            {{ $record->diagnosis }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-uppercase text-muted fw-bold small mb-2">Symptoms</h6>
                        <p>{{ $record->symptoms ?? 'None recorded.' }}</p>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="text-uppercase text-muted fw-bold small mb-2">Treatment Plan</h6>
                    <div class="p-3 bg-light rounded border-start border-4 border-success">
                        {{ $record->treatment_plan ?? 'No plan recorded.' }}
                    </div>
                </div>

                <!-- Vitals Grid -->
                <div class="mb-4">
                    <h6 class="text-uppercase text-muted fw-bold small mb-3">Vital Signs</h6>
                    <div class="row g-3">
                        @foreach($record->vital_signs ?? [] as $key => $val)
                            @if($val)
                            <div class="col-md-2 col-4 text-center">
                                <div class="p-2 border rounded bg-white">
                                    <small class="d-block text-muted text-uppercase" style="font-size: 0.7rem;">{{ $key }}</small>
                                    <strong>{{ $val }}</strong>
                                </div>
                            </div>
                            @endif
                        @endforeach
                        @if(empty($record->vital_signs))
                            <div class="col-12 text-muted small">No vitals recorded.</div>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('doctor.records.edit', $record->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i> Edit Record
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar: Prescriptions & Follow Up -->
    <div class="col-lg-4">
        <!-- Follow Up Card -->
        @if($record->follow_up_date)
        <div class="card shadow-sm border-0 mb-4 bg-warning bg-opacity-10">
            <div class="card-body">
                <h6 class="fw-bold text-warning-emphasis mb-3"><i class="fas fa-calendar-alt me-2"></i> Follow Up Required</h6>
                <div class="d-flex justify-content-between mb-2">
                    <span>Date:</span>
                    <strong>{{ $record->follow_up_date->format('M d, Y') }}</strong>
                </div>
                @if($record->follow_up_notes)
                    <div class="small text-muted fst-italic mt-2">"{{ $record->follow_up_notes }}"</div>
                @endif
            </div>
        </div>
        @endif

        <!-- Prescriptions -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Prescriptions</h6>
                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addScriptModal">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <ul class="list-group list-group-flush">
                @forelse($record->prescriptions as $script)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $script->medication_name }}</strong>
                                <small class="text-muted d-block">{{ $script->dosage }} - {{ $script->frequency }}</small>
                            </div>
                            <span class="badge bg-{{ $script->is_active ? 'success' : 'secondary' }} rounded-pill">
                                {{ $script->is_active ? 'Active' : 'Ended' }}
                            </span>
                        </div>
                    </li>
                @empty
                    <li class="list-group-item text-center text-muted py-3 small">No prescriptions linked.</li>
                @endforelse
            </ul>
        </div>

        <!-- Lab Reports -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">Lab Reports</h6>
                <a href="{{ route('doctor.lab-reports', ['patient_id' => $record->patient_id, 'record_id' => $record->id]) }}" class="btn btn-sm btn-outline-info">
                    <i class="fas fa-upload"></i>
                </a>
            </div>
             <ul class="list-group list-group-flush">
                @forelse($record->labReports as $report)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $report->report_title }}</strong>
                            <small class="text-muted d-block">{{ $report->created_at->format('M d') }}</small>
                        </div>
                        <a href="{{ route('doctor.lab-reports.show', $report->id) }}" class="btn btn-sm btn-light">View</a>
                    </li>
                @empty
                    <li class="list-group-item text-center text-muted py-3 small">No lab reports linked.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

<!-- Add Prescription Modal -->
<div class="modal fade" id="addScriptModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('doctor.prescriptions.store') }}" method="POST">
            @csrf
            <input type="hidden" name="medical_record_id" value="{{ $record->id }}">
            <input type="hidden" name="patient_id" value="{{ $record->patient_id }}">
            
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add Prescription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Medication Name <span class="text-danger">*</span></label>
                        <input type="text" name="medication_name" class="form-control" required placeholder="e.g. Amoxicillin">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Dosage <span class="text-danger">*</span></label>
                            <input type="text" name="dosage" class="form-control" required placeholder="e.g. 500mg">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Frequency <span class="text-danger">*</span></label>
                            <input type="text" name="frequency" class="form-control" required placeholder="e.g. 3 times a day">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Duration <span class="text-danger">*</span></label>
                            <input type="text" name="duration" class="form-control" required placeholder="e.g. 7 days">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Route</label>
                            <select name="route" class="form-select">
                                <option value="oral">Oral</option>
                                <option value="injection">Injection</option>
                                <option value="topical">Topical</option>
                                <option value="inhalation">Inhalation</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Instructions</label>
                        <textarea name="instructions" class="form-control" rows="2" placeholder="e.g. Take after food"></textarea>
                    </div>
                    <div class="row g-3">
                         <div class="col-6">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Prescription</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
