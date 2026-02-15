@extends('layouts.auth')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 90vh;">
    <div class="col-xl-9 col-lg-10 col-md-11">
        <div class="card overflow-hidden shadow-lg border-0 bg-white" style="border-radius: 30px;">
            <div class="row g-0" style="min-height: 650px;">

                <div class="col-md-5 d-none d-md-flex flex-column p-5 position-relative text-white"
                     style="background: #aebbfd !important; min-height: 600px;">

                    <div class="position-absolute top-0 start-0 w-100 h-100"
                         style="background-image: url('{{ asset('images/bg-pattern.png') }}'); background-size: cover; background-position: center; opacity: 0.4; z-index: 1;"></div>

                    <div class="mb-4 position-relative" style="z-index: 10;">
                        <img src="{{ asset('images/start-logo.png') }}" alt="MediCare Logo" class="rounded-4 shadow-sm" style="width: 60px; height: 60px;">
                    </div>

                    <div class="position-relative mb-5" style="z-index: 10;">
                        <h1 class="font-brand fw-bold mb-2" style="font-size: 3.5rem; line-height: 1.1;">System Setup</h1>
                        <p class="fs-5 opacity-90 fw-light mb-0">Configure your Admin Account</p>
                    </div>

                    <div class="position-absolute pe-none"
                         style="bottom: 40px; right: -130px; z-index: 50; width: 85%; max-width: 550px;">
                         <img src="{{ asset('images/final-login.png') }}"
                              class="img-fluid"
                              style="filter: drop-shadow(0 20px 40px rgba(0,0,0,0.15)); transform: scale(1.35);">
                    </div>
                </div>

                <div class="col-md-7 bg-white p-5 d-flex flex-column justify-content-center position-relative">

                    <div class="mx-auto w-100" style="max-width: 480px;">

                        <div class="mb-4">
                            <h3 class="fw-bold text-dark mb-4" style="font-family: 'Poppins', sans-serif; font-size: 1.75rem;">Create Admin Account</h3>
                        </div>

                        <form action="{{ route('setup.store') }}" method="POST">
                            @csrf

                            @if(session('error'))
                                <div class="alert alert-danger py-2 small fw-medium mb-4 rounded-3 border-0 bg-danger bg-opacity-10 text-danger">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger py-2 small fw-medium mb-4 rounded-3 border-0 bg-danger bg-opacity-10 text-danger">
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-medium mb-1">Hospital Name</label>
                                <input type="text" name="hospital_name" class="form-control form-control-lg border fs-6"
                                       placeholder="e.g. City General Hospital" value="{{ old('hospital_name') }}" required
                                       style="border-color: #e2e8f0; border-radius: 10px; padding: 12px 15px;">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-secondary small fw-medium mb-1">Full Name</label>
                                    <input type="text" name="name" class="form-control form-control-lg border fs-6"
                                           placeholder="e.g. John Doe" value="{{ old('name') }}" required
                                           style="border-color: #e2e8f0; border-radius: 10px; padding: 12px 15px;">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-secondary small fw-medium mb-1">Phone Number</label>
                                    <input type="text" name="phone" class="form-control form-control-lg border fs-6"
                                           placeholder="+1 234 567 8900" value="{{ old('phone') }}"
                                           style="border-color: #e2e8f0; border-radius: 10px; padding: 12px 15px;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-medium mb-1">Email Address</label>
                                <input type="email" name="email" class="form-control form-control-lg border fs-6"
                                       placeholder="admin@hospital.com" value="{{ old('email') }}" required
                                       style="border-color: #e2e8f0; border-radius: 10px; padding: 12px 15px;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-secondary small fw-medium mb-1">Address</label>
                                <textarea name="address" class="form-control form-control-lg border fs-6" rows="2"
                                          placeholder="Hospital Address" style="border-color: #e2e8f0; border-radius: 10px; padding: 12px 15px;">{{ old('address') }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-secondary small fw-medium mb-1">Password</label>
                                    <input type="password" name="password" class="form-control form-control-lg border fs-6"
                                           placeholder="••••••••" required style="border-color: #e2e8f0; border-radius: 10px; padding: 12px 15px;">
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label text-secondary small fw-medium mb-1">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control form-control-lg border fs-6"
                                           placeholder="••••••••" required style="border-color: #e2e8f0; border-radius: 10px; padding: 12px 15px;">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-semibold shadow-md mb-4 hover-lift"
                                    style="background-color: #5b6cfd; border: none; letter-spacing: 0.5px; border-radius: 10px !important;">
                                Complete Setup
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4 text-muted small opacity-50">
            &copy; {{ date('Y') }} Secure EMR System. UI Design by <span class="fw-medium">MediCare Team</span>
        </div>
    </div>
</div>

<style>
    .hover-lift:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3) !important;
    }
    .form-control:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1) !important;
        border: 1px solid #eef2ff !important;
    }
</style>
@endsection