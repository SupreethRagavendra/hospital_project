@extends('layouts.app')
@section('page-title', 'System Settings & Reports')

@section('content')
<div class="row g-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0">
            <div class="list-group list-group-flush">
                <a href="{{ route('admin.settings', ['tab' => 'settings']) }}" class="list-group-item list-group-item-action {{ $tab == 'settings' ? 'active' : '' }}">
                    <i class="fas fa-cogs me-2"></i> General Settings
                </a>
                <a href="{{ route('admin.settings', ['tab' => 'reports']) }}" class="list-group-item list-group-item-action {{ $tab == 'reports' ? 'active' : '' }}">
                    <i class="fas fa-chart-bar me-2"></i> System Reports
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        @if($tab == 'settings')
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold py-3">
                    General System Configuration
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf
                        <h6 class="text-primary mb-3">Security Settings</h6>
                        <div class="mb-4">
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="maintenanceMode">
                                <label class="form-check-label" for="maintenanceMode">Maintenance Mode</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="2fa" checked disabled>
                                <label class="form-check-label" for="2fa">Enforce Strong Passwords</label>
                            </div>
                        </div>

                        <div class="alert alert-info border-0 bg-info bg-opacity-10">
                            <i class="fas fa-info-circle me-2"></i>
                            For security reasons, most configuration changes must be made directly in the .env file.
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        @elseif($tab == 'reports')
            <div class="row g-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white fw-bold py-3">
                            Patient Registration Trend (Last 12 Months)
                        </div>
                        <div class="card-body">
                            <canvas id="patientTrendChart" height="100"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white fw-bold py-3">
                            Start Performers (Doctors)
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-3">Doctor</th>
                                        <th class="text-end pe-3">Records Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recordsByDoctor as $doc)
                                        <tr>
                                            <td class="ps-3 fw-bold text-dark">{{ $doc->name }}</td>
                                            <td class="text-end pe-3">
                                                <span class="badge bg-primary rounded-pill">{{ $doc->medical_records_count }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="text-center py-3">No data available.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white fw-bold py-3">
                            Top 10 Diagnoses
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-3">Diagnosis</th>
                                        <th class="text-end pe-3">Frequency</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topDiagnoses as $diag)
                                        <tr>
                                            <td class="ps-3">{{ Str::ucfirst($diag->diagnosis) }}</td>
                                            <td class="text-end pe-3">
                                                <span class="badge bg-warning text-dark rounded-pill">{{ $diag->count }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="text-center py-3">No data available.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
@if($tab == 'reports')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('patientTrendChart').getContext('2d');
        const months = {!! json_encode(array_column($totalPatientsByMonth, 'month')) !!};
        const counts = {!! json_encode(array_column($totalPatientsByMonth, 'count')) !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'New Patients',
                    data: counts,
                    backgroundColor: '#2563EB',
                    borderRadius: 4
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
@endif
@endsection