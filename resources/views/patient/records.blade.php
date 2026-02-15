@extends('layouts.app')
@section('page-title', 'My Medical History')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 text-dark">Medical Records</h3>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="{{ route('patient.records') }}" method="GET" class="row g-3" role="search">
            <div class="col-md-9">
                <label for="search" class="form-label visually-hidden">Search medical records</label>
                <input type="text" id="search" name="search" class="form-control" placeholder="Search diagnosis or doctor..." value="{{ request('search') }}" aria-label="Search medical records">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search me-2"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom">
        <h6 class="mb-0 fw-bold text-dark">
            <i class="fas fa-list me-2 text-primary"></i>
            Your Medical Records
        </h6>
    </div>
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0" role="table">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4" scope="col">Date</th>
                    <th scope="col">Doctor</th>
                    <th scope="col">Diagnosis</th>
                    <th scope="col">Status</th>
                    <th class="text-end pe-4" scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                    <tr>
                        <td class="ps-4 text-nowrap text-muted small" data-label="Date">
                            <div class="fw-bold">{{ $record->created_at->format('M d, Y') }}</div>
                            <div class="text-muted">{{ $record->created_at->format('h:i A') }}</div>
                        </td>
                        <td class="fw-bold text-dark" data-label="Doctor">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-2" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                    {{ substr($record->doctor->name, 0, 1) }}
                                </div>
                                <div>
                                    <div>Dr. {{ $record->doctor->name }}</div>
                                    <small class="text-muted">{{ $record->doctor->specialization ?? 'General' }}</small>
                                </div>
                            </div>
                        </td>
                        <td data-label="Diagnosis">
                            <div>{{ Str::limit($record->diagnosis, 50) }}</div>
                            @if(strlen($record->diagnosis) > 50)
                                <small class="text-muted">Click to view full details</small>
                            @endif
                        </td>
                        <td data-label="Status">
                            <span class="badge bg-{{ $record->status == 'active' ? 'primary' : 'secondary' }}">
                                {{ ucfirst($record->status) }}
                            </span>
                        </td>
                        <td class="text-end pe-4" data-label="Actions">
                            <button class="btn btn-sm btn-outline-primary" onclick="loadRecord({{ $record->id }})" title="View Record Details" aria-label="View record from {{ $record->created_at->format('M d, Y') }}">
                                <i class="fas fa-eye me-1"></i> View
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <div class="mb-3">
                                <i class="fas fa-folder-open fa-3x text-secondary"></i>
                            </div>
                            <h6 class="fw-bold text-muted">No Medical Records Found</h6>
                            <p class="text-muted mb-0">Your medical records will appear here once available.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white py-3">
        {{ $records->withQueryString()->links() }}
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-fullscreen-lg-down">
        <div class="modal-content" id="modalContent">

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function loadRecord(id) {
        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        document.getElementById('modalContent').innerHTML = '<div class="modal-body text-center py-5" style="height: 50vh;"><div class="spinner-border text-primary"></div></div>';
        modal.show();

        fetch(`{{ url('/patient/records') }}/${id}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            document.getElementById('modalContent').innerHTML = html;
        })
        .catch(err => {
            console.error('Error loading record:', err);
            document.getElementById('modalContent').innerHTML = `
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <p class="text-muted">Failed to load record details. Please try again.</p>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            `;
        });
    }
</script>
@endsection