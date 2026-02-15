@extends('layouts.auth')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 90vh;">
    <div class="col-xl-9 col-lg-10 col-md-11">
        <div class="card overflow-hidden shadow-lg border-0 bg-white" style="border-radius: 30px;">
            <div class="row g-0" style="min-height: 650px;">
                
                <!-- Left Screen: Brand & Welcome -->
                <div class="col-md-5 d-none d-md-flex flex-column p-5 position-relative text-white" 
                     style="background: #aebbfd !important; min-height: 600px;">
                    
                    <!-- Background Pattern -->
                    <div class="position-absolute top-0 start-0 w-100 h-100" 
                         style="background-image: url('{{ asset('images/bg-pattern.png') }}'); background-size: cover; background-position: center; opacity: 0.4; z-index: 1;"></div>

                    <!-- Top Icon (Generated Logo) -->
                    <div class="mb-4 position-relative" style="z-index: 10;">
                        <img src="{{ asset('images/start-logo.png') }}" alt="MediCare Logo" class="rounded-4 shadow-sm" style="width: 60px; height: 60px;">
                    </div>

                    <!-- Main Text -->
                    <div class="position-relative mb-5" style="z-index: 10;">
                        <h1 class="font-brand fw-bold mb-2" style="font-size: 3.5rem; line-height: 1.1;">Welcome Back</h1>
                        <p class="fs-5 opacity-90 fw-light mb-0">Sign in to your EMR account</p>
                    </div>

                    <!-- Illustration (Positioned on Center Line - Near Sign In Button) -->
                    <div class="position-absolute pe-none" 
                         style="bottom: 40px; right: -130px; z-index: 50; width: 85%; max-width: 550px;">
                         <img src="{{ asset('images/final-login.png') }}" 
                              class="img-fluid" 
                              style="filter: drop-shadow(0 20px 40px rgba(0,0,0,0.15)); transform: scale(1.35);">
                    </div>
                </div>

                <!-- Right Screen: Design Form -->
                <div class="col-md-7 bg-white p-5 d-flex flex-column justify-content-center position-relative">
                    
                    <div class="mx-auto w-100" style="max-width: 420px;">
                        
                        <!-- Header -->
                        <div class="mb-4">
                            <h3 class="fw-bold text-dark mb-4" style="font-family: 'Poppins', sans-serif; font-size: 1.75rem;">Sign In</h3>
                        </div>

                        <!-- Login Form -->
                        <form action="{{ route('login.submit') }}" method="POST" id="loginForm">
                            @csrf
                            
                            @if(session('error'))
                                <div class="alert alert-danger py-2 small fw-medium mb-4 rounded-3 border-0 bg-danger bg-opacity-10 text-danger">
                                    <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                                </div>
                            @endif

                            <div class="mb-4">
                                <label class="form-label text-secondary small fw-medium mb-1">Email Address</label>
                                <input type="email" name="email" id="email" class="form-control form-control-lg border fs-6" 
                                       placeholder="name@company.com" value="{{ old('email') }}" required
                                       style="border-color: #e2e8f0; border-radius: 10px; padding: 12px 15px;">
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-secondary small fw-medium mb-1">Password</label>
                                <div class="position-relative">
                                    <input type="password" name="password" id="password" class="form-control form-control-lg border fs-6" 
                                           placeholder="••••••••" required style="border-color: #e2e8f0; border-radius: 10px; padding: 12px 15px;">
                                    <button type="button" class="btn position-absolute top-50 end-0 translate-middle-y text-muted border-0" 
                                            onclick="togglePassword()" style="z-index: 5;">
                                        <i class="fas fa-eye-slash small" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" style="cursor: pointer; border-color: #cbd5e1;">
                                    <label class="form-check-label text-secondary small user-select-none" for="remember" style="cursor: pointer;">Remember me</label>
                                </div>
                                <a href="#" class="text-decoration-none text-primary small fw-medium text-opacity-75 hover-opacity-100" onclick="alert('Please contact admin.')">Forgot Password?</a>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-semibold shadow-md mb-4 hover-lift" 
                                    style="background-color: #5b6cfd; border: none; letter-spacing: 0.5px; border-radius: 10px !important;">
                                Sign In
                            </button>
                        </form>

                        <!-- UX Pro: Quick Login Chips -->
                        <div class="mt-2">
                            <p class="text-secondary small fw-bold mb-3 text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">
                                <i class="fas fa-bolt text-warning me-1"></i> Quick Demo Login
                            </p>
                            <div class="d-flex gap-2 justify-content-between">
                                <button onclick="fillLogin('admin@hospital.com', 'password')" 
                                        class="btn btn-sm w-100 border-0 py-2 rounded-3 position-relative overflow-hidden group-hover"
                                        style="background: #eef2ff; color: #4f46e5; transition: all 0.2s;">
                                    <div class="fw-bold small">Admin</div>
                                    <div class="d-none d-sm-block" style="font-size: 0.7rem; opacity: 0.7;">Full Access</div>
                                </button>
                                
                                <button onclick="fillLogin('rajesh@hospital.com', 'password')" 
                                        class="btn btn-sm w-100 border-0 py-2 rounded-3"
                                        style="background: #f0fdf4; color: #16a34a; transition: all 0.2s;">
                                    <div class="fw-bold small">Doctor</div>
                                    <div class="d-none d-sm-block" style="font-size: 0.7rem; opacity: 0.7;">Medical</div>
                                </button>
                                
                                <button onclick="fillLogin('amit@hospital.com', 'password')" 
                                        class="btn btn-sm w-100 border-0 py-2 rounded-3"
                                        style="background: #fff7ed; color: #ea580c; transition: all 0.2s;">
                                    <div class="fw-bold small">Patient</div>
                                    <div class="d-none d-sm-block" style="font-size: 0.7rem; opacity: 0.7;">Records</div>
                                </button>
                            </div>
                        </div>

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
    .transition-hover {
        transition: all 0.2s ease;
    }
    .transition-hover:hover {
        background-color: #f8fafc !important;
        border-color: #cbd5e1 !important;
    }
</style>
@endsection

@section('scripts')
<script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        }
    }

    function fillLogin(email, password) {
        // Flash animation on inputs
        const emailInput = document.getElementById('email');
        const passInput = document.getElementById('password');
        
        emailInput.style.transition = 'background-color 0.2s';
        emailInput.style.backgroundColor = '#dbeafe';
        emailInput.value = email;
        
        passInput.style.transition = 'background-color 0.2s';
        passInput.style.backgroundColor = '#dbeafe';
        passInput.value = password;

        setTimeout(() => {
            emailInput.style.backgroundColor = '#f8f9fa';
            passInput.style.backgroundColor = '#f8f9fa';
        }, 300);
    }
</script>
@endsection
