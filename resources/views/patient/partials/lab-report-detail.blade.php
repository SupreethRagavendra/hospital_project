<div class="modal-header border-bottom-0 pb-0">
    <h5 class="modal-title fw-bold">Lab Report Details</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <div class="alert alert-secondary d-flex align-items-center mb-4">
        <i class="fas fa-flask fa-3x me-3 opacity-50"></i>
        <div>
            <h4 class="fw-bold mb-1">{{ $report->report_title }}</h4>
            <span class="badge bg-{{ $report->status == 'reviewed' ? 'success' : 'warning' }}">
                {{ ucfirst($report->status) }}
            </span>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-6">
            <small class="text-muted fw-bold d-block">REPORT TYPE</small>
            <p class="fs-5 mb-0">{{ ucfirst(str_replace('_', ' ', $report->report_type)) }}</p>
        </div>
        <div class="col-6">
            <small class="text-muted fw-bold d-block">DATE</small>
            <p class="fs-5 mb-0">{{ $report->report_date->format('M d, Y') }}</p>
        </div>
    </div>

    <div class="mb-4">
        <small class="text-muted fw-bold d-block">FINDINGS</small>
        <div class="bg-light p-3 rounded border">{{ $report->findings ?? 'No findings available.' }}</div>
    </div>

    @if($report->conclusion)
    <div class="mb-4">
        <small class="text-muted fw-bold d-block">CONCLUSION</small>
        <p class="mb-0">{{ $report->conclusion }}</p>
    </div>
    @endif
</div>
<div class="modal-footer bg-light d-flex justify-content-between">
    <div class="small text-muted">Reviewed by Dr. {{ $report->reviewer->name ?? 'Pending' }}</div>
    <a href="{{ route('patient.lab-reports.download', $report->id) }}" class="btn btn-primary">
        <i class="fas fa-download me-2"></i> Download File
    </a>
</div>