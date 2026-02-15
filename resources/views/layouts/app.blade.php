<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page-title') - Secure EMR System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Cinzel:wght@600&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #6366f1;
            --primary-rgb: 99, 102, 241;
            --primary-hover: #4f46e5;
            --secondary-color: #64748B;
            --secondary-rgb: 100, 116, 139;
            --success-color: #10b981;
            --success-rgb: 16, 185, 129;
            --danger-color: #ef4444;
            --danger-rgb: 239, 68, 68;
            --warning-color: #f59e0b;
            --warning-rgb: 245, 158, 11;
            --info-color: #0ea5e9;
            --info-rgb: 14, 165, 233;
            --dark-color: #0f172a;
            --light-color: #f8fafc;
            --bg-color: #f1f5f9;

            --bs-primary: var(--primary-color);
            --bs-primary-rgb: var(--primary-rgb);
            --bs-success: var(--success-color);
            --bs-success-rgb: var(--success-rgb);
            --bs-danger: var(--danger-color);
            --bs-danger-rgb: var(--danger-rgb);
            --bs-warning: var(--warning-color);
            --bs-warning-rgb: var(--warning-rgb);
            --bs-info: var(--info-color);
            --bs-info-rgb: var(--info-rgb);
            --bs-body-bg: var(--bg-color);
            --bs-body-font-family: 'Poppins', sans-serif;
        }

        .badge-soft {
            padding: 6px 14px;
            border-radius: 10px;
            font-weight: 600;
            text-transform: capitalize;
            border: 1px solid transparent;
        }
        .badge-soft-primary { background: rgba(99, 102, 241, 0.1) !important; color: #6366f1 !important; }
        .badge-soft-success { background: rgba(16, 185, 129, 0.1) !important; color: #10b981 !important; }
        .badge-soft-danger { background: rgba(239, 68, 68, 0.1) !important; color: #ef4444 !important; }
        .badge-soft-info { background: rgba(14, 165, 233, 0.1) !important; color: #0ea5e9 !important; }
        .badge-soft-warning { background: rgba(245, 158, 11, 0.1) !important; color: #f59e0b !important; }

        .pagination {
            gap: 5px;
            margin-bottom: 0;
        }
        .page-link {
            border: none;
            padding: 8px 16px;
            border-radius: 10px !important;
            color: var(--secondary-color);
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        .page-item.active .page-link {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.2);
        }
        .page-link:hover {
            background-color: #eef2ff;
            color: var(--primary-color);
        }
        .pagination svg {
            width: 20px;
            height: 20px;
        }
        .pagination p {
            margin-bottom: 0;
            font-size: 0.85rem;
            color: var(--secondary-color);
        }

        nav[role="navigation"] .flex.justify-between {
            display: none !important;
        }
        nav[role="navigation"] .hidden.sm\:flex-1 {
            display: flex !important;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }
        @media (min-width: 768px) {
            nav[role="navigation"] .hidden.sm\:flex-1 {
                flex-direction: row;
                justify-content: space-between;
            }
        }

        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            font-size: 1.2rem;
            line-height: 1;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-color);
            color: #334155;
            overflow-x: hidden;
        }

        .font-brand {
            font-family: 'Cinzel', serif;
        }

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
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
        }

        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-menu {
            flex: 1;
            padding: 20px 0;
            overflow-y: auto;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 14px 24px;
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.2s;
            border-left: 4px solid transparent;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .menu-item:hover, .menu-item.active {
            background-color: rgba(255,255,255,0.03);
            color: white;
            border-left-color: var(--primary-color);
        }

        .menu-item i {
            width: 25px;
            margin-right: 12px;
            font-size: 1.1rem;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        .top-bar {
            background: white;
            height: 75px;
            padding: 0 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .content-body {
            flex: 1;
            padding: 40px;
        }

        .footer {
            background: white;
            padding: 25px 40px;
            text-align: center;
            color: var(--secondary-color);
            font-size: 0.85rem;
            border-top: 1px solid #f1f5f9;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        }

        .form-control, .form-select {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 11px 16px;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            background-color: #fff;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

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

        .avatar-circle {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background-color: #eef2ff;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.875rem;
            }
            .table td[data-label]::before {
                content: attr(data-label) ": ";
                font-weight: 600;
                color: var(--primary-color);
                display: inline-block;
                margin-bottom: 0.25rem;
            }
            .table td {
                display: block;
                text-align: left !important;
                padding: 0.75rem 0.5rem !important;
                border: none !important;
                border-bottom: 1px solid #dee2e6 !important;
            }
            .table th {
                display: none;
            }
            .table tr {
                display: block;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                margin-bottom: 1rem;
                padding: 0.5rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-heartbeat fa-2x text-primary" style="color: var(--primary-color) !important;"></i>
            <div>
                <h5 class="mb-0 fw-bold font-brand" style="letter-spacing: 1px;">MediCare+</h5>
                <small class="text-white-50 small" style="letter-spacing: 0.5px;">EMR System</small>
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

    <div class="main-content">

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

        <div class="content-body">
            @include('components.alert')
            @yield('content')
        </div>

        <footer class="footer">
            <p class="mb-0">&copy; {{ date('Y') }} Secure EMR System. All rights reserved.</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

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