<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCare+ | Serving Your Health Needs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Cinzel:wght@600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --secondary-color: #64748B;
            --dark-color: #0f172a;
            --light-color: #f8fafc;
            --bg-color: #f1f5f9;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-color);
            color: #334155;
            overflow-x: hidden;
        }

        .font-brand {
            font-family: 'Cinzel', serif;
        }

        .navbar {
            padding: 25px 0;
            background: transparent;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            color: var(--dark-color);
        }

        .btn-portal {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            padding: 12px 28px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-portal:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.2);
            color: white;
        }

        .nav-link {
            font-weight: 500;
            color: var(--secondary-color);
            margin: 0 15px;
            transition: color 0.2s;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-color);
        }

        .hero {
            position: relative;
            padding: 100px 0 0;
            min-height: 90vh;
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 25px;
            color: var(--dark-color);
            max-width: 550px;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            color: var(--secondary-color);
            max-width: 480px;
            margin-bottom: 40px;
            line-height: 1.6;
        }

        .hero-img-container {
            position: absolute;
            top: 0;
            right: 0;
            width: 55%;
            height: 100%;
            z-index: -1;
        }

        .doctor-img {
            height: 100%;
            width: 100%;
            object-fit: contain;
            object-position: bottom right;
        }

        .bg-shape {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 85%;
            height: 85%;
            background: radial-gradient(circle at center, rgba(99, 102, 241, 0.5) 0%, rgba(99, 102, 241, 0.05) 70%);
            clip-path: ellipse(70% 80% at 90% 90%);
            z-index: -1;
        }

        .floating-icon {
            position: absolute;
            padding: 15px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.08);
            background: white;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-capsule {
            bottom: 35%;
            right: 42%;
            background: #fff8f0;
            transform: rotate(-15deg);
        }

        .icon-kit {
            top: 25%;
            right: 38%;
            background: #eef2ff;
        }

        .info-bar {
            background: white;
            border-radius: 30px 0 0 0;
            padding: 35px 50px;
            position: absolute;
            bottom: 0;
            right: 0;
            width: 60%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: -15px -15px 40px rgba(0,0,0,0.03);
            z-index: 20;
            border-top: 1px solid rgba(0,0,0,0.02);
            border-left: 1px solid rgba(0,0,0,0.02);
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .info-icon {
            width: 50px;
            height: 50px;
            background-color: #eef2ff;
            color: var(--primary-color);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .info-text h6 {
            margin: 0;
            font-weight: 700;
            font-size: 1rem;
            color: var(--dark-color);
        }

        .info-text p {
            margin: 0;
            color: var(--secondary-color);
            font-size: 0.85rem;
        }

        .admin-badge {
            position: absolute;
            left: -25px;
            top: 50%;
            transform: translateY(-50%);
            width: 50px;
            height: 50px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 15px rgba(99, 102, 241, 0.3);
            transition: transform 0.2s;
        }

        .admin-badge:hover {
            transform: translateY(-50%) scale(1.1);
        }

        .mcp-credit {
            position: absolute;
            bottom: 25px;
            left: 40px;
            opacity: 0.6;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: var(--secondary-color);
        }

        .medical-images-container {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .medical-image {
            position: absolute;
            max-width: 45%;
            height: auto;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .medical-image-1 {
            filter: none;
            animation: float-rotate 8s ease-in-out infinite;
            z-index: 3;
            transform: translateX(-80px) translateY(-30px) rotate(-5deg);
        }

        .medical-image-2 {
            filter: hue-rotate(280deg) saturate(1.3) brightness(1.05);
            animation: float-rotate 8s ease-in-out infinite 2s;
            z-index: 2;
            transform: translateX(80px) translateY(20px) rotate(5deg);
        }

        .medical-image-3 {
            filter: hue-rotate(120deg) saturate(1.4) brightness(1.1);
            animation: float-rotate 8s ease-in-out infinite 4s;
            z-index: 1;
            transform: translateX(0px) translateY(60px) rotate(0deg) scale(0.9);
        }

        .medical-image:hover {
            transform: scale(1.05) rotate(0deg) !important;
            box-shadow: 0 30px 60px rgba(0,0,0,0.15);
            z-index: 10 !important;
        }

        @keyframes float-rotate {
            0%, 100% {
                transform: translateX(var(--tx, 0px)) translateY(var(--ty, 0px)) rotate(var(--r, 0deg)) scale(var(--s, 1));
            }
            25% {
                transform: translateX(calc(var(--tx, 0px) + 10px)) translateY(calc(var(--ty, 0px) - 15px)) rotate(calc(var(--r, 0deg) + 2deg)) scale(var(--s, 1));
            }
            50% {
                transform: translateX(calc(var(--tx, 0px) - 5px)) translateY(calc(var(--ty, 0px) - 25px)) rotate(calc(var(--r, 0deg) - 1deg)) scale(var(--s, 1));
            }
            75% {
                transform: translateX(calc(var(--tx, 0px) + 15px)) translateY(calc(var(--ty, 0px) - 10px)) rotate(calc(var(--r, 0deg) + 1deg)) scale(var(--s, 1));
            }
        }

        .medical-image-1 { --tx: -80px; --ty: -30px; --r: -5deg; --s: 1; }
        .medical-image-2 { --tx: 80px; --ty: 20px; --r: 5deg; --s: 1; }
        .medical-image-3 { --tx: 0px; --ty: 60px; --r: 0deg; --s: 0.9; }

        @media (max-width: 991px) {
            .medical-image {
                max-width: 70%;
                position: relative;
                margin: 10px;
            }

            .medical-image-1, .medical-image-2, .medical-image-3 {
                transform: none !important;
                animation: none !important;
                filter: brightness(1.1) !important;
            }

            .medical-images-container {
                flex-wrap: wrap;
                justify-content: center;
                gap: 15px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-heartbeat fa-2x text-primary" style="color: var(--primary-color) !important;"></i>
                <div class="ms-1">
                    <span class="fw-bold font-brand fs-4" style="letter-spacing: 1px;">MediCare+</span>
                    <div style="font-size: 0.65rem; color: var(--secondary-color); margin-top: -5px; letter-spacing: 0.5px;">SECURE EMR SYSTEM</div>
                </div>
            </a>
            <div class="collapse navbar-collapse justify-content-center">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link active" href="#">Secure EMR</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Lab Reports</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Prescriptions</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Audit Logs</a></li>
                </ul>
            </div>
            <div class="d-flex align-items-center">
                <a href="{{ route('login') }}" class="btn-portal">
                    <i class="fas fa-sign-in-alt"></i> Access Portal
                </a>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="hero-title">Serving Your Health Needs Is Our Priority.</h1>
                    <p class="hero-subtitle">Experience the next generation of healthcare management with our secure, integrated, and intelligent Electronic Medical Record system.</p>
                    <a href="{{ route('login') }}" class="btn-portal" style="padding: 16px 36px; font-size: 1.1rem; border-radius: 14px;">
                        Get Started <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="hero-img-container d-flex align-items-center justify-content-center">
            <div class="bg-shape"></div>
            <div class="medical-images-container">
                <img src="{{ asset('images/lab-reports.png') }}"
                     alt="Medical Technology"
                     class="medical-image medical-image-1">
                <img src="{{ asset('images/doctor-interface.png') }}"
                     alt="Healthcare Innovation"
                     class="medical-image medical-image-2">
                <img src="{{ asset('images/patient-portal.png') }}"
                     alt="Digital Health"
                     class="medical-image medical-image-3">
            </div>
        </div>

        <div class="info-bar">
            <a href="{{ route('login') }}" class="admin-badge">
                <i class="fas fa-lock"></i>
            </a>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-file-medical"></i></div>
                <div class="info-text">
                    <h6>Secure EMR</h6>
                    <p>Electronic Health Records</p>
                </div>
            </div>
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-flask"></i></div>
                <div class="info-text">
                    <h6>Lab Integration</h6>
                    <p>Digital Report Delivery</p>
                </div>
            </div>
            <div class="info-item d-none d-md-flex">
                <div class="info-icon"><i class="fas fa-shield-check"></i></div>
                <div class="info-text">
                    <h6>Audit Trail</h6>
                    <p>Real-time Activity Logs</p>
                </div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>