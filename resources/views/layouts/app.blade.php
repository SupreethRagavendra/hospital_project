<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title') - Secure EMR System</title>
    
    <!-- CDN Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #2563EB;
            --secondary-color: #64748B;
            --success-color: #059669;
            --danger-color: #DC2626;
            --warning-color: #F59E0B;
            --info-color: #3B82F6;
            --dark-color: #1E293B;
            --light-color: #F8FAFC;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F1F5F9;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            height: 100vh;
            background-color: var(--dark-color);
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            transition: all 0.3s ease;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-menu {
            flex: 1;
            padding: 20px 0;
            overflow-y: auto;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #CBD5E1;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .menu-item:hover, .menu-item.active {
            background-color: rgba(255,255,255,0.05);
            color: white;
            border-left-color: var(--primary-color);
        }

        .menu-item i {
            width: 25px;
            margin-right: 10px;
        }
        
        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }
        
        .top-bar {
            background: white;
            height: 70px;
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .content-body {
            flex: 1;
            padding: 30px;
        }

        .footer {
            background: white;
            padding: 20px 30px;
            text-align: center;
            color: var(--secondary-color);
            font-size: 0.9rem;
            border-top: 1px solid #E2E8F0;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
        }
        
        /* Utility */
        .avatar-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-heartbeat fa-2x text-primary"></i>
            <div>
                <h5 class="mb-0 fw-bold">MediCare+</h5>
                <small class="text-white-50">EMR System</small>
            </div>
        </div>

        <nav class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Dashboard
            </a>

            @if(auth()->user()->role === 'admin')
                <div class="px-3 py-2 text-uppercase text-white-50 small fw-bold mt-2">Administration</div>
                <a href="{{ route('admin.users') }}" class="menu-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="fas fa-users-cog"></i> User Management
                </a>
                <a href="{{ route('admin.members') }}" class="menu-item {{ request()->routeIs('admin.members*') ? 'active' : '' }}">
                    <i class="fas fa-user-md"></i> Doctors & Patients
                </a>
                <a href="{{ route('admin.audit-logs') }}" class="menu-item {{ request()->routeIs('admin.audit-logs') ? 'active' : '' }}">
                    <i class="fas fa-shield-alt"></i> Audit Logs
                </a>
                <a href="{{ route('admin.settings') }}" class="menu-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
                    <i class="fas fa-cogs"></i> System Settings
                </a>

            @elseif(auth()->user()->role === 'doctor')
                <div class="px-3 py-2 text-uppercase text-white-50 small fw-bold mt-2">Clinical</div>
                <a href="{{ route('doctor.patients') }}" class="menu-item {{ request()->routeIs('doctor.patients*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> My Patients
                </a>
                <a href="{{ route('doctor.records') }}" class="menu-item {{ request()->routeIs('doctor.records*') ? 'active' : '' }}">
                    <i class="fas fa-file-medical"></i> Medical Records
                </a>
                <a href="{{ route('doctor.prescriptions') }}" class="menu-item {{ request()->routeIs('doctor.prescriptions*') ? 'active' : '' }}">
                    <i class="fas fa-prescription"></i> Prescriptions
                </a>
                <a href="{{ route('doctor.lab-reports') }}" class="menu-item {{ request()->routeIs('doctor.lab-reports*') ? 'active' : '' }}">
                    <i class="fas fa-microscope"></i> Lab Reports
                </a>

            @elseif(auth()->user()->role === 'patient')
                <div class="px-3 py-2 text-uppercase text-white-50 small fw-bold mt-2">My Health</div>
                <a href="{{ route('patient.records') }}" class="menu-item {{ request()->routeIs('patient.records') ? 'active' : '' }}">
                    <i class="fas fa-notes-medical"></i> Medical History
                </a>
                <a href="{{ route('patient.prescriptions') }}" class="menu-item {{ request()->routeIs('patient.prescriptions') ? 'active' : '' }}">
                    <i class="fas fa-pills"></i> Prescriptions
                </a>
                <a href="{{ route('patient.lab-reports') }}" class="menu-item {{ request()->routeIs('patient.lab-reports') ? 'active' : '' }}">
                    <i class="fas fa-flask"></i> Lab Reports
                </a>
                <a href="{{ route('profile') }}" class="menu-item {{ request()->routeIs('profile') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i> My Profile
                </a>
            @endif
        </nav>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger w-100 d-flex align-items-center justify-content-center gap-2">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <button class="btn btn-light d-md-none" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <h5 class="mb-0 fw-bold text-dark d-none d-md-block">@yield('page-title')</h5>
            
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                        <div class="text-end me-2 d-none d-md-block">
                            <div class="fw-bold small">{{ auth()->user()->name }}</div>
                            <div class="text-muted small" style="font-size: 0.75rem;">{{ ucfirst(auth()->user()->role) }}</div>
                        </div>
                        <div class="avatar-circle">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li><a class="dropdown-item" href="{{ route('profile') }}"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Content Body -->
        <div class="content-body">
            @include('components.alert')
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="footer">
            <p class="mb-0">&copy; {{ date('Y') }} Secure EMR System. All rights reserved.</p>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Sidebar Toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Confirm Delete Helper
        function confirmDelete(formId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            })
        }
    </script>
    @yield('scripts')
</body>
</html>
