<!DOCTYPE html>
@php
    $appLang = auth()->check() ? (auth()->user()->getSettings('language', 'id') ?? 'id') : 'id';
    $isDark = auth()->check() ? ((auth()->user()->getSettings('theme', 'light') ?? 'light') === 'dark') : false;
@endphp
<html lang="{{ $appLang }}" class="{{ $isDark ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'FRC System') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --primary-blue: #1e40af;
            --secondary-blue: #3b82f6;
            --light-blue: #dbeafe;
            --dark-navy: #1e293b;
            --text-dark: #334155;
            --text-light: #64748b;
            --bg-light: #f8fafc;
            --white: #ffffff;
            --border-light: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
        }

        /* Dark mode variable overrides */
        .dark {
            --primary-blue: #60a5fa;
            --secondary-blue: #1e293b;
            --light-blue: #0f172a;
            --dark-navy: #0b1220;
            --text-dark: #e6eef8;
            --text-light: #94a3b8;
            --bg-light: #061022;
            --white: #0b1220;
            --border-light: #1f2937;
        }

        /* Header Banner */
        .header-banner {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 50%, #0369a1 100%);
            color: white;
            padding: 1rem 0;
            box-shadow: var(--shadow-md);
            position: relative;
            /* allow dropdowns to overflow the header area */
            overflow: visible;
        }

        .header-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://infodiklatkeuangan.com/wp-content/uploads/2022/02/pemerintahan.png') no-repeat right center;
            background-size: contain;
            opacity: 0.1;
            z-index: 0;
        }

        .header-content {
            position: relative;
            z-index: 1;
        }

        /* Ensure dropdown menus appear above header graphics */
        .dropdown-menu {
            z-index: 3000;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: white !important;
            text-decoration: none;
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white !important;
        }

        /* Main Layout */
        .main-wrapper {
            display: flex;
            min-height: calc(100vh - 80px);
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: var(--white);
            box-shadow: var(--shadow-md);
            border-right: 1px solid var(--border-light);
            position: fixed;
            height: calc(100vh - 80px);
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-light);
            background: linear-gradient(135deg, var(--light-blue) 0%, rgba(59, 130, 246, 0.1) 100%);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .user-details h6 {
            margin: 0;
            font-weight: 600;
            color: var(--text-dark);
        }

        .user-role {
            font-size: 0.875rem;
            color: var(--text-light);
            background: var(--primary-blue);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-weight: 500;
        }

        /* Navigation Menu */
        .nav-menu {
            padding: 1rem 0;
        }

        .nav-item {
            margin: 0.25rem 1rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: var(--text-dark);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link:hover {
            background: var(--light-blue);
            color: var(--primary-blue);
            transform: translateX(4px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            color: white;
            box-shadow: var(--shadow-md);
        }

        .nav-link i {
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 2rem;
            background: var(--bg-light);
        }

        /* Page Header */
        .page-header {
            background: var(--white);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 2rem;
            border: 1px solid var(--border-light);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 200px;
            height: 100%;
            background: url('https://infodiklatkeuangan.com/wp-content/uploads/2022/02/pemerintahan.png') no-repeat center;
            background-size: contain;
            opacity: 0.05;
            z-index: 0;
        }

        .page-header-content {
            position: relative;
            z-index: 1;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: var(--text-light);
            font-size: 1.1rem;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--text-light);
            font-weight: 500;
        }

        /* Feature Cards */
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .feature-card {
            background: var(--white);
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-light);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .feature-header {
            background: linear-gradient(135deg, var(--light-blue) 0%, rgba(59, 130, 246, 0.1) 100%);
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-light);
        }

        .feature-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .feature-description {
            color: var(--text-light);
            font-size: 0.95rem;
        }

        .feature-body {
            padding: 1.5rem;
        }

        .feature-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-sm);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-blue);
            color: var(--primary-blue);
            background: transparent;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: var(--primary-blue);
            color: white;
            transform: translateY(-2px);
        }

        /* Responsive */
        /* Hover-to-expand sidebar (desktop) */
        @media (min-width: 1025px) {
            .sidebar { width: 64px; transition: width .2s ease; overflow-x: hidden; }
            .sidebar:hover { width: 280px; }
            /* Adjust content area based on sidebar hover */
            .main-wrapper .main-content { margin-left: 64px; transition: margin-left .2s ease; }
            .main-wrapper #sidebar:hover ~ .main-content { margin-left: 280px; }
            /* Collapse header/info */
            #sidebar:not(:hover) .sidebar-header { display: none; }
            /* Compact nav when collapsed */
            #sidebar:not(:hover) .nav-link { justify-content: center; padding: 0.75rem; overflow: hidden; white-space: nowrap; color: transparent; }
            #sidebar:not(:hover) .nav-link i { margin: 0; color: var(--text-dark); }
        }
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            }

            .feature-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }

            .page-header {
                padding: 1.5rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Government Building Icons */
        .gov-icon {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        }

        .report-icon {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .complaint-icon {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .user-icon {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        }

        .alert-icon {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
    </style>
</head>
<body>
    <!-- Header Banner -->
    <div class="header-banner">
        <div class="container-fluid header-content">
            <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand" href="{{ url('/') }}" title="{{ config('app.name', 'Government FRC System') }}">
                    <i class="fas fa-building"></i>
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-bell me-1"></i>Notifications</a>
                        </li>
                        <li class="nav-item me-2 d-flex align-items-center">
                            <button id="themeToggle" class="btn btn-sm btn-outline-light me-2" type="button">
                                <i class="fas fa-moon"></i>
                                <span class="ms-1 d-none d-md-inline">Theme</span>
                            </button>
                        </li>

                        @auth
                        @php
                            $avatarUrl = auth()->user()->getAvatarUrl();
                            $initials = auth()->user()->getAvatarInitials();
                        @endphp
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center px-2 py-1" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius:9999px;">
                                @if($avatarUrl)
                                    <img src="{{ $avatarUrl }}" alt="{{ auth()->user()->name }}" class="rounded-circle me-2" style="width:32px;height:32px;object-fit:cover;">
                                @else
                                    <span class="user-avatar me-2" style="width:32px;height:32px;font-size:0.8rem;display:inline-flex;align-items:center;justify-content:center;">{{ $initials }}</span>
                                @endif
                                <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                                <span class="badge text-bg-secondary ms-2">{{ ucfirst(auth()->user()->role) }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user-edit me-2"></i>Edit Profil</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.settings') }}"><i class="fas fa-cog me-2"></i>Pengaturan Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-1"></i>Login</a></li>
                        @endauth
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="user-details">
                        <h6>{{ auth()->user()->name }}</h6>
                        <span class="user-role">{{ ucfirst(auth()->user()->role) }}</span>
                    </div>
                </div>
            </div>

            <nav class="nav-menu">
                @if(auth()->user()->isAdmin())
                    <div class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>Dashboard
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
                            <i class="fas fa-file-alt"></i>Laporan
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.complaints') }}" class="nav-link {{ request()->routeIs('admin.complaints*') ? 'active' : '' }}">
                            <i class="fas fa-exclamation-triangle"></i>Keluhan
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>Pengguna
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.departments') }}" class="nav-link {{ request()->routeIs('admin.departments*') ? 'active' : '' }}">
                            <i class="fas fa-building"></i>Departemen
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('admin.monitoring') }}" class="nav-link {{ request()->routeIs('admin.monitoring*') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i>Monitoring
                        </a>
                    </div>
                @elseif(auth()->user()->isDepartmentHead() || auth()->user()->isStaff())
                    <div class="nav-item">
                        <a href="{{ route('administration.dashboard') }}" class="nav-link {{ request()->routeIs('administration.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>Dashboard
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('administration.reports') }}" class="nav-link {{ request()->routeIs('administration.reports*') ? 'active' : '' }}">
                            <i class="fas fa-file-alt"></i>Laporan Masyarakat
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('administration.complaints') }}" class="nav-link {{ request()->routeIs('administration.complaints*') ? 'active' : '' }}">
                            <i class="fas fa-exclamation-triangle"></i>Keluhan
                        </a>
                    </div>
                    @if(auth()->user()->isDepartmentHead())
                    <div class="nav-item">
                        <a href="{{ route('administration.staff') }}" class="nav-link {{ request()->routeIs('administration.staff*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i>Staff
                        </a>
                    </div>
                    @endif
                @else
                    <div class="nav-item">
                        <a href="{{ route('citizen.dashboard') }}" class="nav-link {{ request()->routeIs('citizen.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>Dashboard
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('citizen.reports.create') }}" class="nav-link {{ request()->routeIs('citizen.reports.create') ? 'active' : '' }}">
                            <i class="fas fa-plus-circle"></i>Buat Laporan
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('citizen.reports.index') }}" class="nav-link {{ request()->routeIs('citizen.reports*') ? 'active' : '' }}">
                            <i class="fas fa-file-alt"></i>Laporan Saya
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('citizen.complaints.create') }}" class="nav-link {{ request()->routeIs('citizen.complaints.create') ? 'active' : '' }}">
                            <i class="fas fa-plus-circle"></i>Buat Keluhan
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="{{ route('citizen.complaints.index') }}" class="nav-link {{ request()->routeIs('citizen.complaints*') ? 'active' : '' }}">
                            <i class="fas fa-exclamation-triangle"></i>Keluhan Saya
                        </a>
                    </div>
                @endif
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Mobile sidebar toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        }
        
        // Theme toggle: persist local + server
        (function() {
            const root = document.documentElement || document.querySelector('html');
            const themeToggle = document.getElementById('themeToggle');
            const metaCsrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const initialFromServer = root.classList.contains('dark') ? 'dark' : 'light';

            const applyTheme = (t) => {
                if (t === 'dark') root.classList.add('dark'); else root.classList.remove('dark');
            };

            let saved = localStorage.getItem('theme') || initialFromServer || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            applyTheme(saved);

            if (themeToggle) {
                const icon = themeToggle.querySelector('i');
                if (icon) {
                    if (saved === 'dark') { icon.classList.remove('fa-moon'); icon.classList.add('fa-sun'); }
                    else { icon.classList.remove('fa-sun'); icon.classList.add('fa-moon'); }
                }

                themeToggle.addEventListener('click', function() {
                    saved = (saved === 'dark') ? 'light' : 'dark';
                    localStorage.setItem('theme', saved);
                    applyTheme(saved);
                    const icon = this.querySelector('i');
                    if (icon) icon.classList.toggle('fa-moon') || icon.classList.toggle('fa-sun');

                    // persist to server if authenticated
                    const isAuth = {{ auth()->check() ? 'true' : 'false' }};
                    if (!isAuth) return;
                    fetch('{{ route('profile.settings.update') }}', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': metaCsrf,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ theme: saved, language: '{{ auth()->check() ? auth()->user()->getSettings("language","id") : "id" }}' })
                    }).catch(e => console.warn('Could not persist theme', e));
                });
            }
        })();

        // Add fade-in animation to cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.stat-card, .feature-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('fade-in-up');
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
