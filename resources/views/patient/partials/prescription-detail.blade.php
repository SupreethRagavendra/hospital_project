<div class="modal-header border-bottom-0 pb-0">
    <h5 class="modal-title fw-bold">Prescription Details</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>
<div class="modal-body">
    <div class="alert alert-secondary d-flex align-items-center mb-4">
        <i class="fas fa-prescription-bottle-alt fa-3x me-3 opacity-50"></i>
        <div>
            <h4 class="fw-bold mb-1">{{ $prescription->medication_name }}</h4>
            <span class="badge bg-{{ $prescription->is_active ? 'success' : 'secondary' }}">
                {{ $prescription->is_active ? 'Active' : 'Completed' }}
            </span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-6">
            <small class="text-muted fw-bold d-block">DOSAGE</small>
            <p class="fs-5 mb-0">{{ $prescription->dosage }}</p>
        </div>
        <div class="col-6">
            <small class="text-muted fw-bold d-block">FREQUENCY</small>
            <p class="fs-5 mb-0">{{ $prescription->frequency }}</p>
        </div>
        <div class="col-6">
            <small class="text-muted fw-bold d-block">DURATION</small>
            <p class="fs-5 mb-0">{{ $prescription->duration }}</p>
        </div>
        <div class="col-6">
            <small class="text-muted fw-bold d-block">ROUTE</small>
            <p class="fs-5 mb-0">{{ ucfirst($prescription->route) }}</p>
        </div>

        <div class="col-12">
            <small class="text-muted fw-bold d-block">INSTRUCTIONS</small>
            <p class="mb-0 bg-light p-3 rounded border">{{ $prescription->instructions ?? 'No specific instructions.' }}</p>
        </div>

        <div class="col-12">
            <hr class="my-2">
            <div class="d-flex justify-content-between text-muted small mt-2">
                <span>Prescribed by Dr. {{ $prescription->doctor->name }}</span>
                <span>{{ $prescription->created_at->format('M d, Y') }}</span>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer bg-light">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
