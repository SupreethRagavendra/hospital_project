<div class="modal-header border-bottom">
    <h5 class="modal-title fw-bold">
        <i class="fas fa-file-medical me-2 text-primary"></i>
        Medical Record Details
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body p-0" style="max-height: 75vh; overflow-y: auto;">
    <div class="p-4">
        <div class="row g-4">
    <div class="col-md-5">
        <h6 class="fw-bold mb-3 border-bottom pb-2">Record Information</h6>
        <div class="small mb-3">
            <span class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">DATE & TIME</span>
            <div class="fw-bold">{{ $record->created_at->format('M d, Y') }}</div>
            <div class="text-muted">{{ $record->created_at->format('h:i A') }}</div>
        </div>
        <div class="small mb-3">
            <span class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">DOCTOR</span>
            <div class="fw-bold">Dr. {{ $record->doctor->name }}</div>
            <div class="text-muted">{{ $record->doctor->specialization ?? 'General' }}</div>
        </div>
        <div class="small mb-3">
            <span class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">STATUS</span>
            <span class="badge bg-{{ $record->status == 'active' ? 'primary' : 'success' }}">
                {{ ucfirst($record->status) }}
            </span>
        </div>
    </div>
    <div class="col-md-7">
        <h6 class="fw-bold mb-3 border-bottom pb-2">Medical Details</h6>

        <div class="mb-3">
            <label class="small text-muted fw-bold d-block">CHIEF COMPLAINT</label>
            <div class="bg-light p-3 rounded border-start border-4 border-primary">
                <p class="mb-0">{{ $record->chief_complaint }}</p>
            </div>
        </div>

        <div class="mb-3">
            <label class="small text-muted fw-bold d-block">DIAGNOSIS</label>
            <div class="bg-success bg-opacity-10 p-3 rounded border-start border-4 border-success">
                <div class="fw-bold">{{ $record->diagnosis }}</div>
            </div>
        </div>

        @if($record->treatment_plan)
        <div class="mb-3">
            <label class="small text-muted fw-bold d-block">TREATMENT PLAN</label>
            <div class="bg-info bg-opacity-10 p-3 rounded border-start border-4 border-info">
                <p class="mb-0">{{ $record->treatment_plan }}</p>
            </div>
        </div>
        @endif
    </div>

    <div class="col-12">
        <div class="accordion accordion-flush" id="linkedItems">
            @if($record->prescriptions->count() > 0)
            <div class="accordion-item border">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed py-3 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#prescriptions">
                        <i class="fas fa-prescription me-2 text-primary"></i>
                        Prescriptions ({{ $record->prescriptions->count() }})
                    </button>
                </h2>
                <div id="prescriptions" class="accordion-collapse collapse" data-bs-parent="#linkedItems">
                    <div class="accordion-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($record->prescriptions as $p)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong class="text-primary">{{ $p->medication_name }}</strong>
                                        <div class="small text-muted">{{ $p->dosage }}</div>
                                    </div>
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        <i class="fas fa-pills"></i>
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($record->labReports->count() > 0)
            <div class="accordion-item border">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed py-3 fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#labs">
                        <i class="fas fa-flask me-2 text-success"></i>
                        Lab Reports ({{ $record->labReports->count() }})
                    </button>
                </h2>
                <div id="labs" class="accordion-collapse collapse" data-bs-parent="#linkedItems">
                    <div class="accordion-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($record->labReports as $l)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $l->report_title }}</strong>
                                        <div class="small text-muted">{{ $l->created_at->format('M d, Y') }}</div>
                                    </div>
                                    <a href="{{ route('patient.lab-reports.download', $l->id) }}" class="btn btn-sm btn-outline-success" title="Download Report">
                                        <i class="fas fa-download me-1"></i> Download
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    </div>
</div>
<div class="modal-footer border-top bg-light">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
        <i class="fas fa-times me-1"></i> Close
    </button>
    @if($record->prescriptions->count() > 0)
        <button type="button" class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print me-1"></i> Print
        </button>
    @endif
</div>