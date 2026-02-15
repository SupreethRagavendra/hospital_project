@extends('layouts.app')
@section('page-title', 'My Prescriptions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 text-dark">Prescriptions</h3>
</div>

<!-- Filters -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="{{ route('patient.prescriptions') }}" method="GET" class="row g-3">
            <div class="col-md-9">
                <select name="filter" class="form-select">
                    <option value="">All Prescriptions</option>
                    <option value="active" {{ request('filter') == 'active' ? 'selected' : '' }}>Active Only</option>
                    <option value="inactive" {{ request('filter') == 'inactive' ? 'selected' : '' }}>Inactive Only</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Prescriptions List -->
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Date</th>
                    <th>Dr.</th>
                    <th>Medication</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prescriptions as $script)
                    <tr>
                        <td class="ps-4 text-nowrap text-muted small">{{ $script->created_at->format('M d, Y') }}</td>
                        <td>Dr. {{ $script->doctor->name }}</td>
                        <td class="fw-bold">{{ $script->medication_name }}</td>
                        <td>
                            @if($script->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-primary" onclick="loadPrescription({{ $script->id }})">
                                <i class="fas fa-eye me-1"></i> View
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-prescription-bottle-alt fa-2x mb-3 text-secondary"></i>
                            <p>No prescriptions found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white py-3">
        {{ $prescriptions->withQueryString()->links() }}
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" id="modalContent">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function loadPrescription(id) {
        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        document.getElementById('modalContent').innerHTML = '<div class="modal-body text-center py-5"><div class="spinner-border text-primary"></div></div>';
        modal.show();

        fetch(`{{ url('/patient/prescriptions') }}/${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('modalContent').innerHTML = html;
        })
        .catch(err => {
            document.getElementById('modalContent').innerHTML = '<div class="modal-body text-center text-danger">Failed to load details.</div>';
        });
    }
</script>
@endsection
