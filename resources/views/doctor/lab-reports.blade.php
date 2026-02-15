@extends('layouts.app')
@section('page-title', 'Lab Reports')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 text-dark">Lab Reports</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadReportModal">
        <i class="fas fa-file-upload me-2"></i> Upload New Report
    </button>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="{{ route('doctor.lab-reports') }}" method="GET" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search report title or patient..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    @foreach(['blood_test','urine_test','xray','mri','ct_scan','ultrasound','ecg','other'] as $type)
                        <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-secondary w-100">Search</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Report Date</th>
                    <th>Patient</th>
                    <th>Title & Type</th>
                    <th>Status</th>
                    <th>Review</th>
                    <th class="text-end pe-4">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($labReports as $report)
                    <tr>
                        <td class="ps-4 text-nowrap text-muted small">
                            {{ $report->report_date->format('M d, Y') }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-3 bg-light text-primary small">
                                    {{ substr($report->patient->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $report->patient->user->name }}</div>
                                    <small class="text-muted">ID: {{ $report->patient->patient_id_number }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $report->report_title }}</div>
                            <span class="badge bg-light text-dark border">{{ ucfirst(str_replace('_', ' ', $report->report_type)) }}</span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $report->status == 'reviewed' ? 'success' : ($report->status == 'completed' ? 'primary' : 'warning') }}">
                                {{ ucfirst($report->status) }}
                            </span>
                        </td>
                        <td class="small text-muted">
                            @if($report->reviewed_at)
                                <i class="fas fa-check-circle text-success me-1"></i> {{ $report->reviewed_at->format('M d') }}
                            @else
                                <span class="text-warning">Pending</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <a href="{{ route('doctor.lab-reports.show', $report->id) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('doctor.lab-reports.download', $report->id) }}" class="btn btn-sm btn-outline-secondary" title="Download File">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-flask fa-2x mb-3 text-secondary"></i>
                            <p>No lab reports found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white py-3">
        {{ $labReports->withQueryString()->links() }}
    </div>
</div>

<div class="modal fade" id="uploadReportModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('doctor.lab-reports.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Upload New Lab Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Patient <span class="text-danger">*</span></label>
                        <select name="patient_id" class="form-select" id="patientSelect" required>
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->user->name }} ({{ $patient->patient_id_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <input type="hidden" name="medical_record_id" value="{{ request('record_id') }}">

                    <div class="mb-3">
                        <label class="form-label">Report Title <span class="text-danger">*</span></label>
                        <input type="text" name="report_title" class="form-control" required placeholder="e.g. Annual Blood Work">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Report Type <span class="text-danger">*</span></label>
                            <select name="report_type" class="form-select" required>
                                <option value="blood_test">Blood Test</option>
                                <option value="urine_test">Urine Test</option>
                                <option value="xray">X-Ray</option>
                                <option value="mri">MRI</option>
                                <option value="ct_scan">CT Scan</option>
                                <option value="ultrasound">Ultrasound</option>
                                <option value="ecg">ECG</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Report Date <span class="text-danger">*</span></label>
                            <input type="date" name="report_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Findings / Conclusion</label>
                        <textarea name="findings" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Report File (PDF/Image) <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" required accept=".pdf,.jpg,.jpeg,.png">
                        <small class="text-muted">Max size: 5MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload Report</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('patient_id') || urlParams.has('record_id')) {
            const modalElement = document.getElementById('uploadReportModal');
            if (modalElement) {
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
            }
        }
    });
</script>
@endsection