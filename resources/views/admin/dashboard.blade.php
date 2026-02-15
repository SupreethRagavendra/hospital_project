@extends('layouts.app')
@section('page-title', 'Admin Dashboard')

@section('content')
    <h2 class="fw-bold text-dark mb-4">Dashboard Overview</h2>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            @include('components.stats-card', [
                'count' => $totalPatients,
                'label' => 'Total Patients',
                'icon' => 'user-injured',
                'color' => 'primary'
            ])
        </div>
        <div class="col-md-3">
            @include('components.stats-card', [
                'count' => $totalDoctors,
                'label' => 'Total Doctors',
                'icon' => 'user-md',
                'color' => 'success'
            ])
        </div>
        <div class="col-md-3">
            @include('components.stats-card', [
                'count' => $totalRecords,
                'label' => 'Total Records',
                'icon' => 'file-medical',
                'color' => 'info'
            ])
        </div>
        <div class="col-md-3">
            @include('components.stats-card', [
                'count' => $totalLabReports,
                'label' => 'Lab Reports',
                'icon' => 'flask',
                'color' => 'warning'
            ])
        </div>
    </div>

    <div class="row g-4">

        <div class="col-md-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="fas fa-history me-2 text-primary"></i> Recent System Activity
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">User</th>
                                <th>Role</th>
                                <th>Action</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentActivities as $log)
                                <tr>
                                    <td class="ps-4 fw-bold">
                                        {{ $log->user_name ?? 'System' }}
                                    </td>
                                    <td>
                                        <span class="badge-soft badge-soft-{{ $log->user_role == 'admin' ? 'danger' : ($log->user_role == 'doctor' ? 'info' : ($log->user_role == 'patient' ? 'success' : 'secondary')) }}">
                                            {{ ucfirst($log->user_role) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 200px;" title="{{ $log->description }}">
                                            {{ $log->description ?: $log->action }}
                                        </div>
                                    </td>
                                    <td class="text-muted small">
                                        {{ $log->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No recent activity found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white text-center border-top-0 py-3">
                    <a href="{{ route('admin.audit-logs') }}" class="text-decoration-none fw-bold small">View All Activity <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
             <div class="card shadow-sm border-0 h-100">
                 <div class="card-header bg-white fw-bold py-3">
                    <i class="fas fa-user-plus me-2 text-success"></i> Newest Patients
                 </div>
                 <ul class="list-group list-group-flush">
                     @forelse($recentPatients as $patient)
                         <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                             <div class="d-flex align-items-center">
                                 <div class="avatar-circle me-3">
                                     {{ substr($patient->user->name, 0, 1) }}
                                 </div>
                                 <div>
                                     <h6 class="mb-0 fw-bold">{{ $patient->user->name }}</h6>
                                     <small class="text-muted">ID: {{ $patient->patient_id_number }}</small>
                                 </div>
                             </div>
                             <span class="small text-muted">{{ $patient->created_at->format('d M') }}</span>
                         </li>
                     @empty
                         <li class="list-group-item text-center py-4 text-muted">No patient records found.</li>
                     @endforelse
                 </ul>
                 <div class="card-footer bg-white text-center border-top-0 py-3">
                    <a href="{{ route('admin.members', ['tab' => 'patients']) }}" class="text-decoration-none fw-bold small">View All Patients <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
             </div>
        </div>
    </div>

    <div class="row g-4 mt-1">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="fas fa-chart-pie me-2 text-info"></i> Records by Status
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold py-3">
                    <i class="fas fa-chart-line me-2 text-primary"></i> Patient Registrations (Last 6 Months)
                </div>
                <div class="card-body">
                    <canvas id="registrationChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($recordsByStatus)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($recordsByStatus)) !!},
                    backgroundColor: ['#6366f1', '#10b981', '#f59e0b'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        const regCtx = document.getElementById('registrationChart').getContext('2d');
        const months = {!! json_encode(array_column($monthlyData, 'month')) !!};
        const counts = {!! json_encode(array_column($monthlyData, 'count')) !!};

        new Chart(regCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'New Patients',
                    data: counts,
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    });
</script>
@endsection