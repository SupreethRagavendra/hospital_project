@extends('layouts.app')
@section('page-title', 'Audit Logs')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0 text-dark">System Audit Logs</h3>
    <a href="{{ route('admin.audit-logs.export', request()->all()) }}" class="btn btn-outline-success">
        <i class="fas fa-file-csv me-2"></i> Export CSV
    </a>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="{{ route('admin.audit-logs') }}" method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary mb-1">Start Date</label>
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary mb-1">End Date</label>
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary mb-1">Action Type</label>
                <select name="action" class="form-select form-select-sm">
                    <option value="">All Actions</option>
                    @foreach($actions as $act)
                        <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $act)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary mb-1">Role</label>
                <select name="role" class="form-select form-select-sm">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="doctor" {{ request('role') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                    <option value="patient" {{ request('role') == 'patient' ? 'selected' : '' }}>Patient</option>
                    <option value="system" {{ request('role') == 'system' ? 'selected' : '' }}>System</option>
                </select>
            </div>
            <div class="col-12 text-end mt-4">
                <a href="{{ route('admin.audit-logs') }}" class="btn btn-light px-4 me-2 rounded-3 fw-medium">Reset</a>
                <button type="submit" class="btn btn-primary px-5 rounded-3 fw-bold">Apply Filters</button>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead>
                <tr class="bg-light">
                    <th class="ps-4 py-3 text-secondary small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Time</th>
                    <th class="py-3 text-secondary small fw-bold text-uppercase" style="letter-spacing: 0.5px;">User</th>
                    <th class="py-3 text-secondary small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Role</th>
                    <th class="py-3 text-secondary small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Action</th>
                    <th class="py-3 text-secondary small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Description</th>
                    <th class="text-end pe-4 py-3 text-secondary small fw-bold text-uppercase" style="letter-spacing: 0.5px;">IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="ps-4 text-nowrap text-muted small">
                            {{ $log->created_at->format('Y-m-d H:i:s') }}
                        </td>
                        <td class="fw-bold text-dark">
                            @if($log->user_id)
                                <a href="{{ route('admin.users.show', $log->user_id) }}" class="text-decoration-none text-dark">{{ $log->user_name }}</a>
                            @else
                                {{ $log->user_name ?? 'System' }}
                            @endif
                        </td>
                        <td>
                            <span class="badge-soft badge-soft-{{ $log->user_role == 'admin' ? 'danger' : ($log->user_role == 'doctor' ? 'info' : ($log->user_role == 'patient' ? 'success' : 'secondary')) }}">
                                {{ ucfirst($log->user_role) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark fw-medium border-0 shadow-none" style="font-size: 0.7rem;">{{ strtoupper($log->action) }}</span>
                        </td>
                        <td class="text-break" style="max-width: 400px;">
                            {{ $log->description }}
                        </td>
                        <td class="text-end pe-4 font-monospace small text-muted">
                            {{ $log->ip_address }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-search-minus fa-2x mb-3"></i>
                            <p>No audit logs found matching your criteria.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-white py-3">
        {{ $logs->withQueryString()->links() }}
    </div>
</div>
@endsection