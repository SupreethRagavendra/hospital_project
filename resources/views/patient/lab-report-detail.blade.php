@extends('layouts.app')
@section('page-title', 'Lab Report Details')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        @include('patient.partials.lab-report-detail', ['report' => $report])
    </div>
    <div class="card-footer bg-white border-top-0">
        <a href="{{ route('patient.lab-reports') }}" class="btn btn-secondary">Back to Reports</a>
    </div>
</div>
@endsection