@extends('layouts.app')
@section('page-title', 'Record Details')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        @include('patient.partials.record-detail', ['record' => $record])
    </div>
    <div class="card-footer bg-white border-top-0">
        <a href="{{ route('patient.records') }}" class="btn btn-secondary">Back to Records</a>
    </div>
</div>
@endsection