@extends('layouts.app')
@section('page-title', 'Lab Report Details')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4 h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-flask me-2 text-info"></i> {{ $report->report_title }}
                </h5>
                <span class="badge bg-{{ $report->status == 'reviewed' ? 'success' : ($report->status == 'completed' ? 'primary' : 'warning') }} fs-6 px-3">
                    {{ ucfirst($report->status) }}
                </span>
            </div>
            <div class="card-body p-4">

                <div class="alert alert-light border d-flex align-items-center mb-4">
                    <div class="avatar-circle me-3 bg-secondary text-white small">
                        {{ substr($report->patient->user->name, 0, 1) }}
                    </div>
                    <div>
                        <strong>{{ $report->patient->user->name }}</strong>
                        <span class="text-muted mx-2">|</span>
                        <small>ID: {{ $report->patient->patient_id_number }}</small>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <small class="text-muted text-uppercase fw-bold">Report Type</small>
                        <p class="fs-5">{{ ucfirst(str_replace('_', ' ', $report->report_type)) }}</p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted text-uppercase fw-bold">Date</small>
                        <p class="fs-5">{{ $report->report_date->format('M d, Y') }}</p>
                    </div>
                </div>

                <div class="mb-4">
                    <small class="text-muted text-uppercase fw-bold">Findings</small>
                    <div class="p-3 bg-light rounded border-start border-4 border-info">
                        {{ $report->findings ?? 'No findings recorded.' }}
                    </div>
                </div>

                @if($report->conclusion)
                <div class="mb-4">
                    <small class="text-muted text-uppercase fw-bold">Conclusion</small>
                    <p>{{ $report->conclusion }}</p>
                </div>
                @endif

                @if($report->medicalRecord)
                <div class="mb-4">
                    <small class="text-muted text-uppercase fw-bold">Related Medical Record</small>
                    <div>
                        <a href="{{ route('doctor.records.show', $report->medical_record_id) }}" class="text-decoration-none">
                            <i class="fas fa-link me-1"></i> View Record #{{ $report->medical_record_id }}
                        </a>
                    </div>
                </div>
                @endif

                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <div>
                        @if($report->reviewed_at)
                            <small class="text-success"><i class="fas fa-check-circle me-1"></i> Reviewed by Dr. {{ $report->reviewer->name }} on {{ $report->reviewed_at->format('M d, Y') }}</small>
                        @endif
                    </div>
                    <div class="d-flex gap-2">
                        @if($report->status !== 'reviewed')
                            <form action="{{ route('doctor.lab-reports.review', $report->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-outline-success">
                                    <i class="fas fa-check me-2"></i> Mark as Reviewed
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('doctor.lab-reports.download', $report->id) }}" class="btn btn-primary">
                            <i class="fas fa-download me-2"></i> Download File
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection