@extends('layouts.app')
@section('page-title', 'Patient Details')

@section('content')
<div class="row g-4 mb-4">
    <!-- Patient Profile Card -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-body text-center p-5">
                <div class="avatar-circle mx-auto mb-3 bg-success text-white" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ substr($patient->user->name, 0, 1) }}
                </div>
                <h4 class="fw-bold mb-1">{{ $patient->user->name }}</h4>
                <p class="text-muted mb-2">{{ $patient->patient_id_number }}</p>
                <div class="d-flex justify-content-center gap-2 mb-4">
                    <span class="badge bg-primary">{{ $patient->user->age }} Years</span>
                    <span class="badge bg-secondary">{{ ucfirst($patient->user->gender) }}</span>
                    <span class="badge bg-danger">{{ $patient->user->blood_group ?? 'UNK' }}</span>
                </div>

                <div class="text-start border-top pt-4">
                    <div class="mb-3">
                        <small class="text-muted d-block fw-bold text-uppercase">CONTACT</small>
                        <p class="mb-0">{{ $patient->user->phone ?? 'N/A' }}</p>
                        <small class="text-muted">{{ $patient->user->email }}</small>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block fw-bold text-uppercase">EMERGENCY</small>
                        <p class="mb-0 text-danger">{{ $patient->emergency_contact ?? 'N/A' }}</p>
                        <small class="text-muted">{{ $patient->emergency_phone ?? '' }}</small>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block fw-bold text-uppercase">ALLERGIES</small>
                        <p class="mb-0 text-danger">{{ $patient->allergies ?? 'None' }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block fw-bold text-uppercase">CHRONIC CONDITIONS</small>
                        <p class="mb-0 warning-text">{{ $patient->chronic_conditions ?? 'None' }}</p>
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <a href="{{ route('doctor.records.create', ['patient_id' => $patient->id]) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> New Medical Record
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Content Area -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white p-0 border-bottom-0">
                <ul class="nav nav-tabs nav-justified" id="patientTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active py-3 fw-bold" id="records-tab" data-bs-toggle="tab" data-bs-target="#records" type="button" role="tab" aria-controls="records">Medical Records</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3 fw-bold" id="prescriptions-tab" data-bs-toggle="tab" data-bs-target="#prescriptions" type="button" role="tab" aria-controls="prescriptions">Prescriptions</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link py-3 fw-bold" id="labs-tab" data-bs-toggle="tab" data-bs-target="#labs" type="button" role="tab" aria-controls="labs">Lab Reports</button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-0">
                <div class="tab-content" id="patientTabsContent">
                    <!-- Medical Records Tab -->
                    <div class="tab-pane fade show active" id="records" role="tabpanel" aria-labelledby="records-tab">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Date</th>
                                        <th>Diagnosis</th>
                                        <th>Doctor</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($records as $record)
                                        <tr>
                                            <td class="ps-4 text-nowrap">{{ $record->created_at->format('M d, Y') }}</td>
                                            <td class="fw-bold">{{ Str::limit($record->diagnosis, 30) }}</td>
                                            <td class="small">{{ $record->doctor->name }}</td>
                                            <td><span class="badge bg-secondary">{{ ucfirst($record->status) }}</span></td>
                                            <td class="text-end pe-4">
                                                <a href="{{ route('doctor.records.show', $record->id) }}" class="btn btn-sm btn-light">View</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center py-5 text-muted">No medical records found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3 border-top">{{ $records->appends(['tab' => 'records'])->links() }}</div>
                    </div>

                    <!-- Prescriptions Tab -->
                    <div class="tab-pane fade" id="prescriptions" role="tabpanel" aria-labelledby="prescriptions-tab">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Date</th>
                                        <th>Medication</th>
                                        <th>Dosage</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($prescriptions as $script)
                                        <tr>
                                            <td class="ps-4 text-nowrap">{{ $script->created_at->format('Md,Y') }}</td>
                                            <td class="fw-bold">{{ $script->medication_name }}</td>
                                            <td>{{ $script->dosage }} ({{ $script->frequency }})</td>
                                            <td>
                                                <span class="badge bg-{{ $script->is_active ? 'success' : 'secondary' }}">
                                                    {{ $script->is_active ? 'Active' : 'Completed' }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <button class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#scriptModal{{ $script->id }}">View</button>
                                                
                                                <!-- Modal for Script Details -->
                                                <div class="modal fade text-start" id="scriptModal{{ $script->id }}" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Prescription Details</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p><strong>Medication:</strong> {{ $script->medication_name }}</p>
                                                                <p><strong>Dosage:</strong> {{ $script->dosage }}</p>
                                                                <p><strong>Frequency:</strong> {{ $script->frequency }}</p>
                                                                <p><strong>Duration:</strong> {{ $script->duration }}</p>
                                                                <p><strong>Route:</strong> {{ $script->route }}</p>
                                                                <p><strong>Instructions:</strong> {{ $script->instructions }}</p>
                                                                <hr>
                                                                <small class="text-muted">Prescribed by Dr. {{ $script->doctor->name }} on {{ $script->created_at->format('d M Y') }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center py-5 text-muted">No prescriptions found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                         <div class="p-3 border-top">{{ $prescriptions->appends(['tab' => 'prescriptions'])->links() }}</div>
                    </div>

                    <!-- Lab Reports Tab -->
                    <div class="tab-pane fade" id="labs" role="tabpanel" aria-labelledby="labs-tab">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Date</th>
                                        <th>Test Type</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($labReports as $report)
                                        <tr>
                                            <td class="ps-4 text-nowrap">{{ $report->created_at->format('M d, Y') }}</td>
                                            <td><span class="badge bg-info text-dark">{{ ucfirst(str_replace('_', ' ', $report->report_type)) }}</span></td>
                                            <td class="fw-bold">{{ $report->report_title }}</td>
                                            <td>
                                                <span class="badge bg-{{ $report->status == 'reviewed' ? 'success' : ($report->status == 'completed' ? 'primary' : 'warning') }}">
                                                    {{ ucfirst($report->status) }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="{{ route('doctor.lab-reports.show', $report->id) }}" class="btn btn-sm btn-light">View</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center py-5 text-muted">No lab reports found.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                         <div class="p-3 border-top">{{ $labReports->appends(['tab' => 'labs'])->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
