@extends('layouts.app')
@section('page-title', 'My Health Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
            <div class="card-body p-4 position-relative" style="z-index: 2;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-secondary small fw-bold text-uppercase mb-2" style="letter-spacing: 1px;">Next Appointment</div>
                        @if($nextAppointment)
                            <h4 class="fw-bold text-dark mb-1">{{ $nextAppointment->follow_up_date->format('M d, Y') }}</h4>
                            <div class="badge-soft badge-soft-primary small">Dr. {{ $nextAppointment->doctor->name }}</div>
                        @else
                            <h4 class="fw-bold text-muted mb-1">No Schedule</h4>
                            <small class="text-secondary">Keep in touch with your doctor</small>
                        @endif
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: rgba(99, 102, 241, 0.1);">
                        <i class="fas fa-calendar-check fs-3 text-primary"></i>
                    </div>
                </div>
            </div>
            <div class="position-absolute" style="bottom: -20px; right: -20px; width: 100px; height: 100px; background: rgba(99, 102, 241, 0.05); border-radius: 50%; z-index: 1;"></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
            <div class="card-body p-4 position-relative" style="z-index: 2;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-secondary small fw-bold text-uppercase mb-2" style="letter-spacing: 1px;">Active Prescriptions</div>
                        <h2 class="fw-bold text-dark mb-0" style="font-size: 2.5rem;">{{ $activePrescriptions }}</h2>
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: rgba(16, 185, 129, 0.1);">
                        <i class="fas fa-pills fs-3 text-success"></i>
                    </div>
                </div>
            </div>
            <div class="position-absolute" style="bottom: -20px; right: -20px; width: 100px; height: 100px; background: rgba(16, 185, 129, 0.05); border-radius: 50%; z-index: 1;"></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
            <div class="card-body p-4 position-relative" style="z-index: 2;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-secondary small fw-bold text-uppercase mb-2" style="letter-spacing: 1px;">Recent Vitals</div>
                        @if($lastVitals)
                        <div class="d-flex gap-3 mt-1">
                            <div><div class="text-secondary small fw-bold">BP</div><div class="fw-bold text-dark">{{ $lastVitals['bp'] ?? '-' }}</div></div>
                            <div><div class="text-secondary small fw-bold">HR</div><div class="fw-bold text-dark">{{ $lastVitals['hr'] ?? '-' }}</div></div>
                            <div><div class="text-secondary small fw-bold">Weight</div><div class="fw-bold text-dark">{{ $lastVitals['weight'] ?? '-' }}kg</div></div>
                        </div>
                        @else
                           <div class="fw-bold text-muted mt-1">No recent data</div>
                        @endif
                    </div>
                    <div class="rounded-4 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: rgba(14, 165, 233, 0.1);">
                        <i class="fas fa-heartbeat fs-3 text-info"></i>
                    </div>
                </div>
            </div>
            <div class="position-absolute" style="bottom: -20px; right: -20px; width: 100px; height: 100px; background: rgba(14, 165, 233, 0.05); border-radius: 50%; z-index: 1;"></div>
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