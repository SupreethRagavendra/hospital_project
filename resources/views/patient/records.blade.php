@extends('layouts.app')
@section('page-title', 'My Medical History')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 text-dark">Medical Records</h3>
</div>

<!-- Search -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="{{ route('patient.records') }}" method="GET" class="row g-3">
            <div class="col-md-9">
                <input type="text" name="search" class="form-control" placeholder="Search diagnosis or doctor..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
        </form>
    </div>
</div>

<!-- Records List -->
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Date</th>
                    <th>Doctor</th>
                    <th>Diagnosis</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                    <tr>
                        <td class="ps-4 text-nowrap text-muted small">{{ $record->created_at->format('M d, Y') }}</td>
                        <td class="fw-bold text-dark">Dr. {{ $record->doctor->name }}</td>
                        <td>{{ Str::limit($record->diagnosis, 40) }}</td>
                        <td>
                            <span class="badge bg-{{ $record->status == 'active' ? 'primary' : 'secondary' }}">
                                {{ ucfirst($record->status) }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-primary" onclick="loadRecord({{ $record->id }})">
                                <i class="fas fa-eye me-1"></i> View
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-2x mb-3 text-secondary"></i>
                            <p>No medical records found.</p>
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

<!-- Modal -->
<div class="modal fade" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
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
    function loadRecord(id) {
        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        document.getElementById('modalContent').innerHTML = '<div class="modal-body text-center py-5"><div class="spinner-border text-primary"></div></div>';
        modal.show();

        fetch(`{{ url('/patient/records') }}/${id}`, {
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
