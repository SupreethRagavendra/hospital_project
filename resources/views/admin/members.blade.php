@extends('layouts.app')
@section('page-title', 'Medical Staff & Patients')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-12">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item shadow-sm me-2 rounded" role="presentation">
                <a class="nav-link {{ request('tab', 'doctors') == 'doctors' ? 'active' : '' }} fw-bold" href="{{ route('admin.members', ['tab' => 'doctors']) }}">
                    <i class="fas fa-user-md me-2"></i> Doctors
                    <span class="badge bg-white text-primary rounded-pill ms-2">{{ $doctors->total() }}</span>
                </a>
            </li>
            <li class="nav-item shadow-sm rounded" role="presentation">
                <a class="nav-link {{ request('tab') == 'patients' ? 'active' : '' }} fw-bold" href="{{ route('admin.members', ['tab' => 'patients']) }}">
                    <i class="fas fa-user-injured me-2"></i> Patients
                    <span class="badge bg-white text-success rounded-pill ms-2">{{ $patients->total() }}</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="card shadow-sm border-0">
    @if(request('tab', 'doctors') == 'doctors')

        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Doctor</th>
                        <th>Specialization</th>
                        <th>License</th>
                        <th>Patients</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($doctors as $doc)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3">
                                        {{ substr($doc->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $doc->name }}</div>
                                        <small class="text-muted">{{ $doc->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $doc->specialization ?? 'General' }}</td>
                            <td><span class="small fw-bold text-secondary">{{ $doc->license_number ?? 'N/A' }}</span></td>
                            <td>{{ $doc->medical_records_count ?? 0 }} records</td>
                            <td>
                                <span class="badge-soft badge-soft-{{ $doc->is_active ? 'success' : 'secondary' }}">
                                    {{ $doc->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.users.show', $doc->id) }}" class="btn btn-sm btn-light border-0 px-3">
                                    View Profile
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-5">No doctors found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white py-3">
            {{ $doctors->appends(['tab' => 'doctors'])->links() }}
        </div>

    @else

        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Patient</th>
                        <th>Patient ID</th>
                        <th>Age/Gender</th>
                        <th>Blood Group</th>
                        <th>Emergency</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $p)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3" style="background-color: #f0fdf4; color: #16a34a;">
                                        {{ substr($p->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $p->user->name }}</div>
                                        <small class="text-muted">{{ $p->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="font-monospace fw-bold text-secondary">{{ $p->patient_id_number }}</td>
                            <td>
                                {{ $p->user->age }} yrs / {{ ucfirst($p->user->gender ?? 'N/A') }}
                            </td>
                            <td>
                                <span class="badge-soft badge-soft-danger">
                                    {{ $p->user->blood_group ?? '?' }}
                                </span>
                            </td>
                            <td>
                                <div class="small fw-bold">{{ $p->emergency_contact ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $p->emergency_phone }}</small>
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.users.show', $p->user_id) }}" class="btn btn-sm btn-light border-0 px-3">
                                    View Profile
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-5">No patients found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white py-3">
            {{ $patients->appends(['tab' => 'patients'])->links() }}
        </div>
    @endif
</div>
@endsection