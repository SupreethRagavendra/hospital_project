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
            <label class="small text-muted fw-bold">CHIEF COMPLAINT</label>
            <p class="mb-0">{{ $record->chief_complaint }}</p>
        </div>

        <div class="mb-3">
            <label class="small text-muted fw-bold">DIAGNOSIS</label>
            <div class="bg-light p-2 rounded border">{{ $record->diagnosis }}</div>
        </div>

        @if($record->treatment_plan)
        <div class="mb-3">
            <label class="small text-muted fw-bold">TREATMENT PLAN</label>
            <p class="mb-0 small">{{Str::limit($record->treatment_plan, 200)}}</p>
        </div>
        @endif
    </div>

    <!-- Linked Items -->
    <div class="col-12">
        <div class="accordion accordion-flush" id="linkedItems">
            @if($record->prescriptions->count() > 0)
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed py-2 small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#prescriptions">
                        Prescriptions ({{ $record->prescriptions->count() }})
                    </button>
                </h2>
                <div id="prescriptions" class="accordion-collapse collapse" data-bs-parent="#linkedItems">
                    <div class="accordion-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($record->prescriptions as $p)
                                <li class="list-group-item small">
                                    <strong>{{ $p->medication_name }}</strong> - {{ $p->dosage }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            @if($record->labReports->count() > 0)
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed py-2 small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#labs">
                        Lab Reports ({{ $record->labReports->count() }})
                    </button>
                </h2>
                <div id="labs" class="accordion-collapse collapse" data-bs-parent="#linkedItems">
                    <div class="accordion-body p-0">
                        <ul class="list-group list-group-flush">
                            @foreach($record->labReports as $l)
                                <li class="list-group-item small d-flex justify-content-between">
                                    <span>{{ $l->report_title }}</span>
                                    <a href="{{ route('patient.lab-reports.download', $l->id) }}" class="text-decoration-none">Download</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
