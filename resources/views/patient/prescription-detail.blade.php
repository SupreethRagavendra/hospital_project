@extends('layouts.app')
@section('page-title', 'Prescription Details')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        @include('patient.partials.prescription-detail', ['prescription' => $prescription])
    </div>
    <div class="card-footer bg-white border-top-0">
        <a href="{{ route('patient.prescriptions') }}" class="btn btn-secondary">Back to Prescriptions</a>
    </div>
</div>
@endsection
