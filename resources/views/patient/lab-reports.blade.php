@extends('layouts.app')
@section('page-title', 'My Lab Reports')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 text-dark">Lab Reports</h3>
</div>

<!-- Filters -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="{{ route('patient.lab-reports') }}" method="GET" class="row g-3">
            <div class="col-md-9">
                <select name="type" class="form-select">
                        <option value="">All Types</option>
                        @foreach(['blood_test','urine_test','xray','mri','ct_scan','ultrasound','ecg','other'] as $typ)
                            <option value="{{ $typ }}" {{ request('type') == $typ ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $typ)) }}</option>
                        @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<!-- Reports List -->
<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Date</th>
                    <th>Report Title</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    <tr>
                        <td class="ps-4 text-nowrap text-muted small">{{ $report->created_at->format('M d, Y') }}</td>
                        <td class="fw-bold text-dark">{{ $report->report_title }}</td>
                        <td>
                            <span class="badge bg-{{ $report->status == 'reviewed' ? 'success' : 'secondary' }}">
                                {{ ucfirst($report->status) }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary" onclick="loadReport({{ $report->id }})">
                                    <i class="fas fa-eye me-1"></i> View
                                </button>
                                <a href="{{ route('patient.lab-reports.download', $report->id) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="fas fa-flask fa-2x mb-3 text-secondary"></i>
                            <p>No lab reports found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white py-3">
        {{ $reports->withQueryString()->links() }}
    </div>
</div>

<!-- Report Detail Modal -->
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
    function loadReport(id) {
        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        document.getElementById('modalContent').innerHTML = '<div class="modal-body text-center py-5"><div class="spinner-border text-primary"></div></div>';
        modal.show();

        fetch(`{{ url('/patient/lab-reports') }}/${id}`, {
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
