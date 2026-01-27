<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>LGU1 - Public Facility Reservation System</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo e(asset('assets/images/logo.png')); ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Fonts (Poppins - PROJECT_DESIGN_RULES.md) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Merriweather:wght@700&display=swap" rel="stylesheet">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --lgu-primary: #00473e;
            --lgu-secondary: #ffa8ba;
            --lgu-light: #f2f7f5;
            --lgu-white: #ffffff;
            --lgu-headline: #00473e;
            --lgu-paragraph: #475d5b;
            --lgu-button: #faae2b;
            --lgu-button-text: #00473e;
            --lgu-stroke: #00332c;
            --lgu-highlight: #faae2b;
            --lgu-tertiary: #fa5246;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--lgu-light);
            color: var(--lgu-paragraph);
            overflow-x: hidden;
        }
        
        /* Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 71, 62, 0.08);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }
        
        .navbar.scrolled {
            padding: 0.5rem 0;
            box-shadow: 0 4px 30px rgba(0, 71, 62, 0.12);
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 700;
            color: var(--lgu-primary) !important;
            font-size: 1.25rem;
        }
        
        .navbar-brand img {
            height: 45px;
            transition: transform 0.3s ease;
        }
        
        .navbar-brand:hover img {
            transform: scale(1.05);
        }
        
        .nav-link {
            color: var(--lgu-paragraph) !important;
            font-weight: 500;
            padding: 0.5rem 1.25rem !important;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--lgu-highlight);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover {
            color: var(--lgu-primary) !important;
        }
        
        .nav-link:hover::after {
            width: 60%;
        }
        
        .btn-login {
            background: transparent;
            border: 2px solid var(--lgu-primary);
            color: var(--lgu-primary) !important;
            padding: 0.5rem 1.5rem !important;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            background: var(--lgu-primary);
            color: var(--lgu-white) !important;
        }
        
        .btn-get-started {
            background: linear-gradient(135deg, var(--lgu-button) 0%, #f5a623 100%);
            border: none;
            color: var(--lgu-button-text) !important;
            padding: 0.6rem 1.75rem !important;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(250, 174, 43, 0.35);
            transition: all 0.3s ease;
        }
        
        .btn-get-started:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(250, 174, 43, 0.45);
        }
        
        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, var(--lgu-light) 0%, #e8f4f0 50%, #fff5f7 100%);
            position: relative;
            overflow: hidden;
            padding-top: 80px;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 70%;
            height: 140%;
            background: radial-gradient(circle, rgba(0, 71, 62, 0.03) 0%, transparent 70%);
            animation: pulse 8s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }
        
        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--lgu-headline);
            line-height: 1.15;
            margin-bottom: 1.5rem;
        }
        
        .hero-content h1 span {
            color: var(--lgu-highlight);
            position: relative;
        }
        
        .hero-content h1 span::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 0;
            width: 100%;
            height: 12px;
            background: rgba(250, 174, 43, 0.3);
            z-index: -1;
            border-radius: 4px;
        }
        
        .hero-content p {
            font-size: 1.15rem;
            color: var(--lgu-paragraph);
            margin-bottom: 2rem;
            line-height: 1.7;
            max-width: 500px;
        }
        
        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .btn-hero-primary {
            background: linear-gradient(135deg, var(--lgu-button) 0%, #f5a623 100%);
            border: none;
            color: var(--lgu-button-text);
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 8px 25px rgba(250, 174, 43, 0.4);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 35px rgba(250, 174, 43, 0.5);
            color: var(--lgu-button-text);
        }
        
        .btn-hero-secondary {
            background: transparent;
            border: 2px solid var(--lgu-primary);
            color: var(--lgu-primary);
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-hero-secondary:hover {
            background: var(--lgu-primary);
            color: var(--lgu-white);
            transform: translateY(-3px);
        }
        
        /* Hero Illustration */
        .hero-illustration {
            position: relative;
            z-index: 1;
        }
        
        .hero-card {
            background: var(--lgu-white);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 25px 60px rgba(0, 71, 62, 0.15);
            position: relative;
            overflow: hidden;
        }
        
        .hero-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: linear-gradient(90deg, var(--lgu-primary), var(--lgu-highlight), var(--lgu-secondary));
        }
        
        .calendar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        
        .calendar-header h4 {
            color: var(--lgu-primary);
            font-weight: 700;
            margin: 0;
        }
        
        .calendar-nav {
            display: flex;
            gap: 0.5rem;
        }
        
        .calendar-nav button {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: none;
            background: var(--lgu-light);
            color: var(--lgu-primary);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .calendar-nav button:hover {
            background: var(--lgu-primary);
            color: var(--lgu-white);
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            margin-bottom: 1.5rem;
        }
        
        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .calendar-day.header {
            color: var(--lgu-paragraph);
            font-weight: 600;
            font-size: 0.75rem;
        }
        
        .calendar-day:not(.header):hover {
            background: var(--lgu-light);
        }
        
        .calendar-day.selected {
            background: var(--lgu-highlight);
            color: var(--lgu-button-text);
            font-weight: 700;
        }
        
        .calendar-day.booked {
            background: rgba(255, 168, 186, 0.3);
            color: var(--lgu-tertiary);
        }
        
        .booking-preview {
            background: var(--lgu-light);
            border-radius: 12px;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .booking-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--lgu-primary), #006b5a);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--lgu-white);
            font-size: 1.25rem;
        }
        
        .booking-info h6 {
            color: var(--lgu-primary);
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .booking-info p {
            color: var(--lgu-paragraph);
            font-size: 0.85rem;
            margin: 0;
        }
        
        /* Floating Elements */
        .floating-badge {
            position: absolute;
            background: var(--lgu-white);
            border-radius: 16px;
            padding: 0.75rem 1.25rem;
            box-shadow: 0 10px 30px rgba(0, 71, 62, 0.12);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: float 3s ease-in-out infinite;
        }
        
        .floating-badge.badge-1 {
            top: 10%;
            right: -10%;
            animation-delay: 0s;
        }
        
        .floating-badge.badge-2 {
            bottom: 20%;
            left: -15%;
            animation-delay: 1s;
        }
        
        .floating-badge.badge-3 {
            bottom: 5%;
            right: 5%;
            animation-delay: 2s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .badge-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }
        
        .badge-icon.success {
            background: rgba(0, 71, 62, 0.1);
            color: var(--lgu-primary);
        }
        
        .badge-icon.warning {
            background: rgba(250, 174, 43, 0.2);
            color: #d4940a;
        }
        
        .badge-icon.pink {
            background: rgba(255, 168, 186, 0.3);
            color: var(--lgu-tertiary);
        }
        
        .floating-badge span {
            font-weight: 600;
            color: var(--lgu-primary);
            font-size: 0.9rem;
        }
        
        /* Features Section */
        .features-section {
            padding: 100px 0;
            background: var(--lgu-white);
        }
        
        .section-badge {
            display: inline-block;
            background: rgba(0, 71, 62, 0.1);
            color: var(--lgu-primary);
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }
        
        .section-title {
            font-size: 2.75rem;
            font-weight: 700;
            color: var(--lgu-headline);
            margin-bottom: 1rem;
        }
        
        .section-subtitle {
            font-size: 1.1rem;
            color: var(--lgu-paragraph);
            max-width: 600px;
            margin: 0 auto 3rem;
        }
        
        .feature-card {
            background: var(--lgu-light);
            border-radius: 20px;
            padding: 2rem;
            height: 100%;
            transition: all 0.4s ease;
            border: 2px solid transparent;
        }
        
        .feature-card:hover {
            background: var(--lgu-white);
            border-color: rgba(250, 174, 43, 0.3);
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 71, 62, 0.1);
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .feature-card:hover .feature-icon {
            transform: scale(1.1);
        }
        
        .feature-icon.primary {
            background: linear-gradient(135deg, var(--lgu-primary), #006b5a);
            color: var(--lgu-white);
        }
        
        .feature-icon.highlight {
            background: linear-gradient(135deg, var(--lgu-highlight), #f5a623);
            color: var(--lgu-button-text);
        }
        
        .feature-icon.secondary {
            background: linear-gradient(135deg, var(--lgu-secondary), #ff8fa8);
            color: var(--lgu-white);
        }
        
        .feature-icon.tertiary {
            background: linear-gradient(135deg, var(--lgu-tertiary), #ff6b5a);
            color: var(--lgu-white);
        }
        
        .feature-card h4 {
            color: var(--lgu-headline);
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        
        .feature-card p {
            color: var(--lgu-paragraph);
            font-size: 0.95rem;
            line-height: 1.6;
            margin: 0;
        }
        
        /* How It Works Section */
        .how-it-works {
            padding: 100px 0;
            background: linear-gradient(180deg, var(--lgu-light) 0%, var(--lgu-white) 100%);
        }
        
        .step-card {
            text-align: center;
            position: relative;
        }
        
        .step-number {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--lgu-white);
            border: 3px solid var(--lgu-highlight);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 700;
            color: var(--lgu-primary);
            margin: 0 auto 1.5rem;
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
        }
        
        .step-card:hover .step-number {
            background: var(--lgu-highlight);
            border-color: var(--lgu-highlight);
            transform: scale(1.1);
        }
        
        .step-connector {
            position: absolute;
            top: 40px;
            left: 60%;
            width: 80%;
            height: 3px;
            background: linear-gradient(90deg, var(--lgu-highlight), rgba(250, 174, 43, 0.2));
            z-index: 1;
        }
        
        .step-card h4 {
            color: var(--lgu-headline);
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        
        .step-card p {
            color: var(--lgu-paragraph);
            font-size: 0.95rem;
        }
        
        /* Facilities Preview Section */
        .facilities-section {
            padding: 100px 0;
            background: var(--lgu-primary);
            position: relative;
            overflow: hidden;
        }
        
        .facilities-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        
        .facilities-section .section-badge {
            background: rgba(255, 255, 255, 0.15);
            color: var(--lgu-white);
        }
        
        .facilities-section .section-title {
            color: var(--lgu-white);
        }
        
        .facilities-section .section-subtitle {
            color: rgba(255, 255, 255, 0.8);
        }
        
        .facility-card {
            background: var(--lgu-white);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s ease;
        }
        
        .facility-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
        }
        
        .facility-image {
            height: 180px;
            background: linear-gradient(135deg, var(--lgu-light), #e0ebe7);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
            color: var(--lgu-primary);
        }
        
        .facility-content {
            padding: 1.5rem;
        }
        
        .facility-content h5 {
            color: var(--lgu-headline);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .facility-content p {
            color: var(--lgu-paragraph);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        
        .facility-meta {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 0.85rem;
            color: var(--lgu-paragraph);
        }
        
        .facility-meta span {
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }
        
        /* Stats Section */
        .stats-section {
            padding: 80px 0;
            background: var(--lgu-white);
        }
        
        .stat-item {
            text-align: center;
            padding: 2rem;
        }
        
        .stat-number {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--lgu-primary);
            line-height: 1;
            margin-bottom: 0.5rem;
        }
        
        .stat-number span {
            color: var(--lgu-highlight);
        }
        
        .stat-label {
            font-size: 1rem;
            color: var(--lgu-paragraph);
            font-weight: 500;
        }
        
        /* CTA Section */
        .cta-section {
            padding: 100px 0;
            background: linear-gradient(135deg, var(--lgu-light) 0%, #fff5f7 50%, var(--lgu-light) 100%);
            text-align: center;
        }
        
        .cta-section h2 {
            font-size: 2.75rem;
            font-weight: 700;
            color: var(--lgu-headline);
            margin-bottom: 1rem;
        }
        
        .cta-section p {
            font-size: 1.15rem;
            color: var(--lgu-paragraph);
            max-width: 600px;
            margin: 0 auto 2rem;
        }
        
        /* Footer */
        .footer {
            background: var(--lgu-primary);
            color: var(--lgu-white);
            padding: 60px 0 30px;
        }
        
        .footer-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1rem;
        }
        
        .footer-brand img {
            height: 50px;
            filter: brightness(0) invert(1);
        }
        
        .footer-brand span {
            font-weight: 700;
            font-size: 1.25rem;
        }
        
        .footer-description {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.95rem;
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }
        
        .footer-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 1.25rem;
            color: var(--lgu-highlight);
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-links li {
            margin-bottom: 0.75rem;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }
        
        .footer-links a:hover {
            color: var(--lgu-highlight);
            padding-left: 5px;
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 3rem;
            padding-top: 1.5rem;
            text-align: center;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }
        
        .social-links {
            display: flex;
            gap: 1rem;
        }
        
        .social-links a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--lgu-white);
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: var(--lgu-highlight);
            color: var(--lgu-primary);
            transform: translateY(-3px);
        }
        
        /* Responsive */
        @media (max-width: 991px) {
            .hero-content h1 {
                font-size: 2.75rem;
            }
            
            .hero-illustration {
                margin-top: 3rem;
            }
            
            .floating-badge {
                display: none;
            }
            
            .step-connector {
                display: none;
            }
        }
        
        @media (max-width: 767px) {
            .hero-section {
                padding-top: 100px;
                min-height: auto;
                padding-bottom: 60px;
            }
            
            .hero-content h1 {
                font-size: 2rem;
            }
            
            .hero-content p {
                font-size: 1rem;
            }
            
            .hero-buttons {
                flex-direction: column;
            }
            
            .section-title {
                font-size: 2rem;
            }
            
            .stat-number {
                font-size: 2.5rem;
            }
            
            .cta-section h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="<?php echo e(url('/')); ?>">
                <img src="<?php echo e(asset('assets/images/logo.png')); ?>" alt="LGU1 Logo">
                <span>LGU1 PFRS</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#how-it-works">How It Works</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#facilities">Facilities</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="nav-link btn-login" href="<?php echo e(route('login')); ?>">Sign In</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link btn-get-started" href="<?php echo e(route('register')); ?>">Get Started</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                    <div class="hero-content">
                        <h1>
                            Book Public <br>
                            <span>Facilities</span> <br>
                            With Ease
                        </h1>
                        <p>
                            The official Public Facility Reservation System of Local Government Unit 1. 
                            Reserve courts, halls, and venues seamlessly — anytime, anywhere.
                        </p>
                        <div class="hero-buttons">
                            <a href="<?php echo e(route('register')); ?>" class="btn-hero-primary">
                                <i class="bi bi-calendar-check"></i> Book Now
                            </a>
                            <a href="<?php echo e(url('/facilities')); ?>" class="btn-hero-secondary">
                                <i class="bi bi-building"></i> View Facilities
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
                    <div class="hero-illustration">
                        <!-- Floating Badges -->
                        <div class="floating-badge badge-1">
                            <div class="badge-icon success">
                                <i class="bi bi-check-circle-fill"></i>
                            </div>
                            <span>Booking Confirmed!</span>
                        </div>
                        
                        <div class="floating-badge badge-2">
                            <div class="badge-icon warning">
                                <i class="bi bi-clock-fill"></i>
                            </div>
                            <span>24/7 Available</span>
                        </div>
                        
                        <div class="floating-badge badge-3">
                            <div class="badge-icon pink">
                                <i class="bi bi-heart-fill"></i>
                            </div>
                            <span>100% Free</span>
                        </div>
                        
                        <!-- Main Card -->
                        <div class="hero-card">
                            <div class="calendar-header">
                                <h4><i class="bi bi-calendar3"></i> January 2026</h4>
                                <div class="calendar-nav">
                                    <button><i class="bi bi-chevron-left"></i></button>
                                    <button><i class="bi bi-chevron-right"></i></button>
                                </div>
                            </div>
                            
                            <div class="calendar-grid">
                                <div class="calendar-day header">Sun</div>
                                <div class="calendar-day header">Mon</div>
                                <div class="calendar-day header">Tue</div>
                                <div class="calendar-day header">Wed</div>
                                <div class="calendar-day header">Thu</div>
                                <div class="calendar-day header">Fri</div>
                                <div class="calendar-day header">Sat</div>
                                
                                <div class="calendar-day"></div>
                                <div class="calendar-day"></div>
                                <div class="calendar-day"></div>
                                <div class="calendar-day">1</div>
                                <div class="calendar-day">2</div>
                                <div class="calendar-day">3</div>
                                <div class="calendar-day">4</div>
                                
                                <div class="calendar-day">5</div>
                                <div class="calendar-day">6</div>
                                <div class="calendar-day">7</div>
                                <div class="calendar-day">8</div>
                                <div class="calendar-day booked">9</div>
                                <div class="calendar-day booked">10</div>
                                <div class="calendar-day">11</div>
                                
                                <div class="calendar-day">12</div>
                                <div class="calendar-day">13</div>
                                <div class="calendar-day">14</div>
                                <div class="calendar-day">15</div>
                                <div class="calendar-day">16</div>
                                <div class="calendar-day">17</div>
                                <div class="calendar-day">18</div>
                                
                                <div class="calendar-day">19</div>
                                <div class="calendar-day">20</div>
                                <div class="calendar-day">21</div>
                                <div class="calendar-day booked">22</div>
                                <div class="calendar-day">23</div>
                                <div class="calendar-day">24</div>
                                <div class="calendar-day">25</div>
                                
                                <div class="calendar-day selected">26</div>
                                <div class="calendar-day">27</div>
                                <div class="calendar-day">28</div>
                                <div class="calendar-day">29</div>
                                <div class="calendar-day">30</div>
                                <div class="calendar-day">31</div>
                                <div class="calendar-day"></div>
                            </div>
                            
                            <div class="booking-preview">
                                <div class="booking-icon">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div class="booking-info">
                                    <h6>Multi-Purpose Hall A</h6>
                                    <p>Jan 26, 2026 • 9:00 AM - 12:00 PM</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="text-center" data-aos="fade-up">
                <span class="section-badge">WHY CHOOSE US</span>
                <h2 class="section-title">Powerful Features for Easy Booking</h2>
                <p class="section-subtitle">
                    Our system is designed to make facility reservations simple, transparent, and efficient for all citizens.
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon primary">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <h4>Easy Scheduling</h4>
                        <p>Book facilities with just a few clicks. View real-time availability and choose your preferred time slots.</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon highlight">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4>Secure & Verified</h4>
                        <p>AI-powered identity verification ensures legitimate bookings and protects facility access.</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon secondary">
                            <i class="bi bi-bell"></i>
                        </div>
                        <h4>Smart Notifications</h4>
                        <p>Receive email reminders for upcoming reservations and instant updates on booking status.</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="feature-card">
                        <div class="feature-icon tertiary">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h4>Track History</h4>
                        <p>Access your complete booking history, view past reservations, and manage upcoming events.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works" id="how-it-works">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-badge">SIMPLE PROCESS</span>
                <h2 class="section-title">How It Works</h2>
                <p class="section-subtitle">
                    Get started in minutes with our streamlined booking process.
                </p>
            </div>
            
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-card">
                        <div class="step-connector"></div>
                        <div class="step-number">1</div>
                        <h4>Create Account</h4>
                        <p>Register with your valid ID for secure verification</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-card">
                        <div class="step-connector"></div>
                        <div class="step-number">2</div>
                        <h4>Browse Facilities</h4>
                        <p>Explore available venues and check schedules</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="step-card">
                        <div class="step-connector"></div>
                        <div class="step-number">3</div>
                        <h4>Select & Book</h4>
                        <p>Choose your date, time, and submit your request</p>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <h4>Get Confirmed</h4>
                        <p>Receive confirmation and enjoy your booking</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Facilities Preview -->
    <section class="facilities-section" id="facilities">
        <div class="container position-relative">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-badge">OUR FACILITIES</span>
                <h2 class="section-title">Available Facilities</h2>
                <p class="section-subtitle">
                    Discover a wide range of public facilities ready for your events and activities.
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="facility-card">
                        <div class="facility-image">
                            <i class="bi bi-building"></i>
                        </div>
                        <div class="facility-content">
                            <h5>Multi-Purpose Halls</h5>
                            <p>Spacious venues perfect for community gatherings, seminars, and celebrations.</p>
                            <div class="facility-meta">
                                <span><i class="bi bi-people"></i> 100-500 pax</span>
                                <span><i class="bi bi-geo-alt"></i> Various Locations</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="facility-card">
                        <div class="facility-image">
                            <i class="bi bi-dribbble"></i>
                        </div>
                        <div class="facility-content">
                            <h5>Sports Courts</h5>
                            <p>Basketball, volleyball, and badminton courts for sports enthusiasts.</p>
                            <div class="facility-meta">
                                <span><i class="bi bi-people"></i> 10-50 pax</span>
                                <span><i class="bi bi-clock"></i> 6AM - 10PM</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="facility-card">
                        <div class="facility-image">
                            <i class="bi bi-tree"></i>
                        </div>
                        <div class="facility-content">
                            <h5>Parks & Open Spaces</h5>
                            <p>Beautiful outdoor areas for picnics, team buildings, and community events.</p>
                            <div class="facility-meta">
                                <span><i class="bi bi-people"></i> 50-200 pax</span>
                                <span><i class="bi bi-sun"></i> Daylight hours</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="<?php echo e(url('/facilities')); ?>" class="btn-hero-primary">
                    <i class="bi bi-grid"></i> View All Facilities
                </a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-item">
                        <div class="stat-number">50<span>+</span></div>
                        <div class="stat-label">Public Facilities</div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-item">
                        <div class="stat-number">10K<span>+</span></div>
                        <div class="stat-label">Registered Users</div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-item">
                        <div class="stat-number">25K<span>+</span></div>
                        <div class="stat-label">Bookings Made</div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-item">
                        <div class="stat-number">98<span>%</span></div>
                        <div class="stat-label">Satisfaction Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container" data-aos="fade-up">
            <h2>Ready to Book Your First Facility?</h2>
            <p>
                Join thousands of citizens who are already using our system to book public facilities 
                quickly and easily. Registration is free and takes only a few minutes.
            </p>
            <div class="hero-buttons justify-content-center">
                <a href="<?php echo e(route('register')); ?>" class="btn-hero-primary">
                    <i class="bi bi-person-plus"></i> Create Free Account
                </a>
                <a href="<?php echo e(route('login')); ?>" class="btn-hero-secondary">
                    <i class="bi bi-box-arrow-in-right"></i> Sign In
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="footer-brand">
                        <img src="<?php echo e(asset('assets/images/logo.png')); ?>" alt="LGU1 Logo">
                        <span>LGU1 PFRS</span>
                    </div>
                    <p class="footer-description">
                        The official Public Facility Reservation System of Local Government Unit 1. 
                        Making facility booking accessible, transparent, and efficient for all citizens.
                    </p>
                    <div class="social-links">
                        <a href="#"><i class="bi bi-facebook"></i></a>
                        <a href="#"><i class="bi bi-twitter-x"></i></a>
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-envelope"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4 mb-4">
                    <h5 class="footer-title">Quick Links</h5>
                    <ul class="footer-links">
                        <li><a href="#features">Features</a></li>
                        <li><a href="#how-it-works">How It Works</a></li>
                        <li><a href="#facilities">Facilities</a></li>
                        <li><a href="<?php echo e(url('/facilities')); ?>">Browse All</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-4 mb-4">
                    <h5 class="footer-title">Account</h5>
                    <ul class="footer-links">
                        <li><a href="<?php echo e(route('login')); ?>">Sign In</a></li>
                        <li><a href="<?php echo e(route('register')); ?>">Register</a></li>
                        <li><a href="<?php echo e(route('password.request')); ?>">Forgot Password</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 col-md-4 mb-4">
                    <h5 class="footer-title">Contact Us</h5>
                    <ul class="footer-links">
                        <li><i class="bi bi-geo-alt me-2"></i> Local Government Unit 1, Philippines</li>
                        <li><i class="bi bi-telephone me-2"></i> (02) 1234-5678</li>
                        <li><i class="bi bi-envelope me-2"></i> support@lgu1.gov.ph</li>
                        <li><i class="bi bi-clock me-2"></i> Mon - Fri: 8:00 AM - 5:00 PM</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo e(date('Y')); ?> Local Government Unit 1. All rights reserved. | Public Facility Reservation System v1.6</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            once: true,
            offset: 100
        });
        
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNav');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/landing.blade.php ENDPATH**/ ?>