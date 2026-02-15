@extends('layouts.app')
@section('page-title', 'My Health Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white shadow-sm border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-white-50 text-uppercase mb-2">Upcoming Appointment</h6>
                        @if($nextAppointment)
                            <h4 class="mb-1">{{ $nextAppointment->follow_up_date->format('M d, Y') }}</h4>
                            <small class="text-white-50">Dr. {{ $nextAppointment->doctor->name }}</small>
                        @else
                            <h4 class="mb-1">No appointments</h4>
                            <small class="text-white-50">Schedule with your doctor</small>
                        @endif
                    </div>
                    <i class="fas fa-calendar-check fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white shadow-sm border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-white-50 text-uppercase mb-2">Active Prescriptions</h6>
                        <h2 class="mb-0">{{ $activePrescriptions }}</h2>
                    </div>
                    <i class="fas fa-pills fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white shadow-sm border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="text-white-50 text-uppercase mb-2">Recent Vitals</h6>
                        @if($lastVitals)
                        <div class="d-flex gap-3">
                            <div><small>BP</small><div class="fw-bold">{{ $lastVitals['bp'] ?? '-' }}</div></div>
                            <div><small>HR</small><div class="fw-bold">{{ $lastVitals['hr'] ?? '-' }}</div></div>
                            <div><small>Wt</small><div class="fw-bold">{{ $lastVitals['weight'] ?? '-' }}kg</div></div>
                        </div>
                        @else
                           <div class="fw-bold">No data</div>
                        @endif
                    </div>
                    <i class="fas fa-heartbeat fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fas fa-history me-2 text-primary"></i> Recent Medical History
            </div>
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Date</th>
                            <th>Doctor</th>
                            <th>Diagnosis</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentRecords as $record)
                            <tr>
                                <td class="ps-4 text-nowrap text-muted small">
                                    {{ $record->created_at->format('M d, Y') }}
                                </td>
                                <td class="fw-bold text-dark">Dr. {{ $record->doctor->name }}</td>
                                <td>{{ Str::limit($record->diagnosis, 40) }}</td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-light" onclick="loadRecordDetails({{ $record->id }})">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fas fa-folder-open fa-2x mb-3 text-secondary"></i>
                                    <p>No medical records found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white text-center border-top-0 py-3">
                <a href="{{ route('patient.records') }}" class="text-decoration-none fw-bold small">View All History <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fas fa-flask me-2 text-info"></i> Recent Lab Reports
            </div>
            <ul class="list-group list-group-flush">
                @forelse($recentLabs as $lab)
                    <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $lab->report_title }}</h6>
                            <small class="text-muted">{{ $lab->created_at->format('M d, Y') }}</small>
                        </div>
                        <a href="{{ route('patient.lab-reports.download', $lab->id) }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-download"></i>
                        </a>
                    </li>
                @empty
                    <li class="list-group-item text-center py-4 text-muted">No lab reports.</li>
                @endforelse
            </ul>
             <div class="card-footer bg-white text-center border-top-0 py-3">
                <a href="{{ route('patient.lab-reports') }}" class="text-decoration-none fw-bold small">View All Reports <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- Record Detail Modal -->
<div class="modal fade" id="recordDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Medical Record Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="recordModalBody">
                <div class="text-center py-5"><div class="spinner-border text-primary"></div></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function loadRecordDetails(id) {
        const modal = new bootstrap.Modal(document.getElementById('recordDetailModal'));
        document.getElementById('recordModalBody').innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';
        modal.show();

        fetch(`{{ url('/patient/records') }}/${id}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('recordModalBody').innerHTML = html;
            })
            .catch(err => {
                document.getElementById('recordModalBody').innerHTML = '<div class="alert alert-danger">Failed to load details.</div>';
            });
    }
</script>
@endsection
