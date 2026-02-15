@extends('layouts.app')
@section('page-title', isset($record) ? 'Edit Medical Record' : 'New Medical Record')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-file-medical me-2 text-primary"></i>
                        {{ isset($record) ? 'Edit Record #' . $record->id : 'Create New Medical Record' }}
                    </h5>
                    <a href="{{ url()->previous() }}" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
            <div class="card-body p-4">
                
                @if(isset($record))
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="fas fa-user-circle fa-2x me-3"></i>
                        <div>
                            <strong>Patient:</strong> {{ $record->patient->user->name }}<br>
                            <small>ID: {{ $record->patient->patient_id_number }}</small>
                        </div>
                    </div>
                @else
                    <div class="alert alert-success d-flex align-items-center">
                        <i class="fas fa-user-circle fa-2x me-3"></i>
                        <div>
                            <strong>Patient:</strong> {{ $patient->user->name }}<br>
                            <small>ID: {{ $patient->patient_id_number }}</small>
                        </div>
                    </div>
                @endif

                <form action="{{ isset($record) ? route('doctor.records.update', $record->id) : route('doctor.records.store') }}" method="POST">
                    @csrf
                    @if(isset($record))
                        @method('PUT')
                        <input type="hidden" name="patient_id" value="{{ $record->patient_id }}">
                    @else
                        <input type="hidden" name="patient_id" value="{{ $patient->id }}">
                    @endif

                    <h6 class="text-primary border-bottom pb-2 mb-3">Visit Details</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" name="record_date" class="form-control" value="{{ old('record_date', isset($record) ? $record->record_date->format('Y-m-d') : date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="active" {{ (old('status', $record->status ?? '') == 'active') ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ (old('status', $record->status ?? '') == 'completed') ? 'selected' : '' }}>Completed</option>
                                <option value="follow_up" {{ (old('status', $record->status ?? '') == 'follow_up') ? 'selected' : '' }}>Follow Up Required</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Confidentiality</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="confidential" value="1" id="confidential" {{ old('confidential', $record->confidential ?? 0) ? 'checked' : '' }}>
                                <label class="form-check-label" for="confidential">Mark as Confidential</label>
                            </div>
                        </div>
                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3">Clinical Notes</h6>
                    <div class="mb-3">
                        <label class="form-label">Chief Complaint <span class="text-danger">*</span></label>
                        <input type="text" name="chief_complaint" class="form-control" value="{{ old('chief_complaint', $record->chief_complaint ?? '') }}" required placeholder="e.g. Severe headache, Fever...">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Symptoms</label>
                            <textarea name="symptoms" class="form-control" rows="3">{{ old('symptoms', $record->symptoms ?? '') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Diagnosis <span class="text-danger">*</span></label>
                            <textarea name="diagnosis" class="form-control" rows="3" required>{{ old('diagnosis', $record->diagnosis ?? '') }}</textarea>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Treatment Plan</label>
                        <textarea name="treatment_plan" class="form-control" rows="3">{{ old('treatment_plan', $record->treatment_plan ?? '') }}</textarea>
                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3">Vital Signs</h6>
                    <div class="row g-3 mb-4 bg-light p-3 rounded">
                        <div class="col-md-3">
                            <label class="form-label small text-muted font-weight-bold">Blood Pressure</label>
                            <input type="text" name="vital_signs[bp]" class="form-control" placeholder="120/80" value="{{ old('vital_signs.bp', $record->vital_signs['bp'] ?? '') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted font-weight-bold">Heart Rate (bpm)</label>
                            <input type="number" name="vital_signs[hr]" class="form-control" placeholder="72" value="{{ old('vital_signs.hr', $record->vital_signs['hr'] ?? '') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted font-weight-bold">Temperature (°C)</label>
                            <input type="text" name="vital_signs[temp]" class="form-control" placeholder="36.6" value="{{ old('vital_signs.temp', $record->vital_signs['temp'] ?? '') }}">
                        </div>
                         <div class="col-md-3">
                            <label class="form-label small text-muted font-weight-bold">Weight (kg)</label>
                            <input type="number" step="0.1" name="vital_signs[weight]" class="form-control" placeholder="70.5" value="{{ old('vital_signs.weight', $record->vital_signs['weight'] ?? '') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted font-weight-bold">Height (cm)</label>
                            <input type="number" name="vital_signs[height]" class="form-control" placeholder="175" value="{{ old('vital_signs.height', $record->vital_signs['height'] ?? '') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted font-weight-bold">O2 Saturation (%)</label>
                            <input type="number" name="vital_signs[spo2]" class="form-control" placeholder="98" value="{{ old('vital_signs.spo2', $record->vital_signs['spo2'] ?? '') }}">
                        </div>
                    </div>

                    <h6 class="text-primary border-bottom pb-2 mb-3">Follow Up</h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Follow Up Date</label>
                            <input type="date" name="follow_up_date" class="form-control" value="{{ old('follow_up_date', isset($record->follow_up_date) ? $record->follow_up_date->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Notes for Follow Up</label>
                            <input type="text" name="follow_up_notes" class="form-control" value="{{ old('follow_up_notes', $record->follow_up_notes ?? '') }}" placeholder="Recall in 2 weeks...">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4 fw-bold">
                            <i class="fas fa-save me-2"></i> {{ isset($record) ? 'Update Record' : 'Save Record' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
