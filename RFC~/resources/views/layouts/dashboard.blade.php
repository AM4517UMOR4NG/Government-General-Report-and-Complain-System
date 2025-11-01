<!DOCTYPE html>
@php
    $appLang = auth()->check() ? (auth()->user()->getSettings('language', 'id') ?? 'id') : 'id';
    $isDark = auth()->check() ? ((auth()->user()->getSettings('theme', 'light') ?? 'light') === 'dark') : false;
    $t = function(string $key) use ($appLang) {
        $id = [
            'brand' => 'FRC Dashboard',
            'navigation' => 'Navigation',
            'theme' => 'Tema',
            'view_profile' => 'Lihat Profil',
            'edit_profile' => 'Edit Profil',
            'settings' => 'Pengaturan',
            'logout' => 'Logout',
            'dashboard' => 'Dashboard',
            'reports' => 'Laporan',
            'complaints' => 'Keluhan',
            'users' => 'Pengguna',
            'departments' => 'Departemen',
            'monitoring' => 'Monitoring',
            'citizen_reports' => 'Laporan Saya',
            'citizen_complaints' => 'Keluhan Saya',
        ];
        $en = [
            'brand' => 'FRC Dashboard',
            'navigation' => 'Navigation',
            'theme' => 'Theme',
            'view_profile' => 'View Profile',
            'edit_profile' => 'Edit Profile',
            'settings' => 'Settings',
            'logout' => 'Logout',
            'dashboard' => 'Dashboard',
            'reports' => 'Reports',
            'complaints' => 'Complaints',
            'users' => 'Users',
            'departments' => 'Departments',
            'monitoring' => 'Monitoring',
            'citizen_reports' => 'My Reports',
            'citizen_complaints' => 'My Complaints',
        ];
        return $appLang === 'en' ? ($en[$key] ?? $key) : ($id[$key] ?? $key);
    };
@endphp
<html lang="{{ $appLang }}" class="{{ $isDark ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'FRC System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Modern Futuristic Dashboard Styles -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #34495e;
            --accent-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --info-color: #17a2b8;
            --light-bg: rgba(255, 255, 255, 0.1);
            --glass-bg: rgba(255, 255, 255, 0.05);
            --text-primary: #2c3e50;
            --text-secondary: #7f8c8d;
            --border-color: rgba(255, 255, 255, 0.1);
            --shadow-light: 0 2px 10px rgba(0, 0, 0, 0.1);
            --shadow-medium: 0 5px 20px rgba(0, 0, 0, 0.15);
            --shadow-heavy: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            background-attachment: fixed;
            min-height: 100vh;
            color: var(--text-primary);
            padding-top: 80px;
        }

        /* Modern Navigation */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--shadow-medium);
            padding: 0.75rem 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            overflow: visible !important;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand i {
            background: linear-gradient(135deg, var(--accent-color) 0%, #5dade2 100%);
            padding: 0.5rem;
            border-radius: 50%;
            font-size: 1.2rem;
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.75rem 1rem !important;
            border-radius: 8px;
            margin: 0 0.25rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-nav .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white !important;
            transform: translateY(-2px);
        }

        .navbar-nav .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white !important;
        }

        .navbar-toggler {
            border: none;
            color: white;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        /* User Dropdown */
        .dropdown-menu {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            box-shadow: var(--shadow-heavy);
            padding: 0.5rem 0;
            min-width: 220px;
            margin-top: 0.5rem;
            z-index: 9999 !important;
            position: absolute;
            display: none;
        }

        .dropdown-menu.show {
            display: block !important;
        }

        .dropdown-menu-end {
            right: 0;
            left: auto;
        }

        .dropdown-header {
            padding: 0.75rem 1.5rem 0.5rem;
            color: var(--text-primary);
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dropdown-item {
            padding: 0.75rem 1.5rem;
            color: var(--text-primary);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.9rem;
        }

        .dropdown-item:hover {
            background: var(--light-bg);
            color: var(--accent-color);
        }

        .dropdown-item.text-danger:hover {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
        }

        .navbar-nav .nav-link.dropdown-toggle {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link.dropdown-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .navbar-nav .nav-link.dropdown-toggle::after {
            margin-left: 0.5rem;
        }

        /* Navbar Container Fix */
        .navbar .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .navbar-nav.ms-auto {
            margin-left: auto !important;
        }

        /* Prevent navbar overflow */
        .navbar-collapse {
            flex-grow: 1;
            align-items: center;
        }

        .navbar-nav {
            flex-direction: row;
            align-items: center;
        }

        @media (max-width: 991px) {
            .navbar-nav {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .navbar-nav.ms-auto {
                margin-left: 0 !important;
                margin-top: 1rem;
            }
        }

        /* Modern Cards */
        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: var(--shadow-light);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-heavy);
        }

        .card-header {
            background: linear-gradient(135deg, var(--light-bg) 0%, rgba(255, 255, 255, 0.1) 100%);
            border-bottom: 1px solid var(--border-color);
            border-radius: 16px 16px 0 0 !important;
            padding: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Modern Buttons */
        .btn {
            border-radius: 10px;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent-color) 0%, #5dade2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
            background: linear-gradient(135deg, #2980b9 0%, #3498db 100%);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #2ecc71 100%);
            color: white;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(39, 174, 96, 0.4);
        }

        .btn-warning {
            background: linear-gradient(135deg, var(--warning-color) 0%, #f1c40f 100%);
            color: white;
        }

        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(243, 156, 18, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #c0392b 100%);
            color: white;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 76, 60, 0.4);
        }

        .btn-info {
            background: linear-gradient(135deg, var(--info-color) 0%, #138496 100%);
            color: white;
        }

        .btn-info:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(23, 162, 184, 0.4);
        }

        .btn-outline-light {
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            background: transparent;
        }

        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
            color: white;
        }

        /* Modern Form Elements */
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            background: white;
        }

        /* Modern Badges */
        .badge {
            border-radius: 20px;
            padding: 0.5em 0.75em;
            font-weight: 500;
            font-size: 0.75rem;
        }

        .badge.bg-secondary {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #5d6d7e 100%) !important;
        }

        /* Modern Alerts */
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(39, 174, 96, 0.1) 0%, rgba(46, 204, 113, 0.1) 100%);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(231, 76, 60, 0.1) 0%, rgba(192, 57, 43, 0.1) 100%);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(243, 156, 18, 0.1) 0%, rgba(241, 196, 15, 0.1) 100%);
            color: var(--warning-color);
            border-left: 4px solid var(--warning-color);
        }

        .alert-info {
            background: linear-gradient(135deg, rgba(23, 162, 184, 0.1) 0%, rgba(19, 132, 150, 0.1) 100%);
            color: var(--info-color);
            border-left: 4px solid var(--info-color);
        }

        /* Stats Cards */
        .stats-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-light);
        }

        .stats-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-heavy);
        }

        .stats-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, var(--accent-color) 0%, #5dade2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .stats-label {
            color: var(--text-secondary);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
        }

        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(180deg, var(--accent-color) 0%, var(--secondary-color) 100%);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 2rem;
        }

        .timeline-marker {
            position: absolute;
            left: -30px;
            top: 0;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--accent-color);
            border: 3px solid white;
            box-shadow: var(--shadow-light);
        }

        .timeline-content {
            background: rgba(255, 255, 255, 0.9);
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow-light);
            border-left: 4px solid var(--accent-color);
        }

        .timeline-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .timeline-text {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 0;
        }

        /* Modal */
        .modal-content {
            border-radius: 16px;
            border: 1px solid var(--border-color);
            backdrop-filter: blur(20px);
            box-shadow: var(--shadow-heavy);
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color);
            border-radius: 16px 16px 0 0;
            background: var(--light-bg);
        }

        /* Table */
        .table {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-light);
        }

        .table thead th {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border: none;
            font-weight: 600;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            border-color: var(--border-color);
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: rgba(52, 152, 219, 0.05);
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding-top: 70px;
            }
            
            .navbar {
                padding: 0.5rem 0;
            }
            
            .navbar-brand {
                font-size: 1.1rem;
            }
            
            .navbar-nav .nav-link {
                padding: 0.5rem 0.75rem !important;
                font-size: 0.9rem;
            }
            
            .navbar-nav .nav-link.dropdown-toggle {
                padding: 0.5rem 0.75rem !important;
            }
            
            .dropdown-menu {
                min-width: 200px;
                margin-top: 0.25rem;
            }
            
            .dropdown-menu-end {
                right: 1rem;
                left: auto;
            }
            
            .navbar-collapse {
                background: rgba(44, 62, 80, 0.95);
                backdrop-filter: blur(20px);
                border-radius: 12px;
                margin-top: 0.5rem;
                padding: 1rem;
            }
            
            .card {
                margin-bottom: 1rem;
            }
            
            .stats-card {
                margin-bottom: 1rem;
                padding: 1.5rem;
            }
            
            .stats-icon {
                font-size: 2.5rem;
            }
            
            .stats-number {
                font-size: 2rem;
            }
            
            main {
                padding-top: 1rem !important;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Smooth transitions */
        html {
            scroll-behavior: smooth;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--accent-color) 0%, var(--secondary-color) 100%);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
        }

        /* Fix for content overlap */
        .content-wrapper {
            padding-top: 1rem;
        }

        /* Ensure Bootstrap modal is always clickable and layered correctly */
        .modal-backdrop {
            z-index: 1050 !important; /* below modal */
        }
        .modal, .modal-dialog, .modal-content {
            z-index: 1060 !important; /* above backdrop */
        }
        
        /* --- Appended: Modern Design System & Sidebar Layout --- */
        :root {
            --bg: #f6f7fb;
            --card: #ffffff;
            --text: #1f2937;
            --muted: #6b7280;
            --primary: #2563eb;
            --primary-600: #1d4ed8;
            --secondary: #0ea5e9;
            --success: #16a34a;
            --warning: #f59e0b;
            --danger: #dc2626;
            --info: #0891b2;
            --border: #e5e7eb;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 10px rgba(0,0,0,0.07);
            --shadow-lg: 0 12px 24px rgba(0,0,0,0.12);
        }
        .dark {
            --bg: #0b1220;
            --card: #0f172a;
            --text: #e5e7eb;
            --muted: #94a3b8;
            --primary: #60a5fa;
            --primary-600: #3b82f6;
            --secondary: #38bdf8;
            --success: #22c55e;
            --warning: #fbbf24;
            --danger: #ef4444;
            --info: #22d3ee;
            --border: #1f2937;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.4);
            --shadow-md: 0 4px 10px rgba(0,0,0,0.45);
            --shadow-lg: 0 12px 24px rgba(0,0,0,0.5);
        }

        /* Top Navbar overrides to light/dark neutral card style */
        .navbar {
            background: var(--card) !important;
            border-bottom: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
        }
        .navbar-brand { font-weight: 700; color: var(--text) !important; display: flex; align-items: center; gap: .5rem; }
        .navbar-brand i { color: var(--primary); }
        .navbar-nav .nav-link { color: var(--muted) !important; border-radius: 8px; padding: .5rem .75rem !important; transition: .2s ease; }
        .navbar-nav .nav-link:hover { color: var(--text) !important; background: rgba(37,99,235,.08); }
        /* Ensure toggler icon visible even without navbar-light/dark */
        .navbar-toggler { border: 1px solid var(--border); }
        .navbar-toggler-icon { background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(107,114,128,1)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e"); }

        /* Sidebar layout */
        .layout { display: grid; grid-template-columns: 260px 1fr; gap: 0; background: var(--bg); }
        .sidebar { background: var(--card); border-right: 1px solid var(--border); min-height: calc(100vh - 56px); position: sticky; top: 56px; box-shadow: var(--shadow-sm); }
        .sidebar .section-title { padding: 1rem 1.25rem; font-size: .75rem; text-transform: uppercase; color: var(--muted); letter-spacing: .08em; }
        .side-link { display: flex; align-items: center; gap: .6rem; padding: .6rem 1rem; color: var(--text); text-decoration: none; border-radius: 8px; margin: .2rem .5rem; transition: .2s ease; }
        .side-link i { width: 18px; text-align: center; color: var(--muted); }
        .side-link:hover { background: rgba(37,99,235,.08); }
        .side-link.active { background: var(--primary); color: #fff; box-shadow: var(--shadow-md); }
        .side-link.active i { color: #fff; }
        .content { padding: 1.25rem; }

        /* Sidebar hover-to-expand (desktop) */
        @media (min-width: 992px) {
            /* switch grid to auto so sidebar width controls column */
            .layout { grid-template-columns: auto 1fr; }
            /* collapsed rail by default */
            .sidebar { width: 64px; overflow-x: hidden; transition: width .2s ease; }
            /* expand on hover */
            .sidebar:hover { width: 260px; }
            /* hide labels when collapsed (not hovered) */
            .sidebar:not(:hover) .side-link span { display: none; }
            .sidebar:not(:hover) .side-link { justify-content: center; }
            .sidebar:not(:hover) .side-link i { color: var(--muted); }
            /* keep section titles hidden when collapsed */
            .sidebar:not(:hover) .section-title { display: none; }
        }

        /* Avatar */
        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.2);
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }
        .avatar:hover {
            transform: scale(1.1);
        }
        .avatar-fallback {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #fff;
            background: var(--primary);
            border: 2px solid rgba(255, 255, 255, 0.2);
            flex-shrink: 0;
        }

        /* Buttons, Forms, Cards tweaks for new variables */
        .card { background: var(--card) !important; border: 1px solid var(--border) !important; }
        .card-header { border-color: var(--border) !important; color: var(--text); }
        .btn-primary { background: var(--primary); }
        .btn-primary:hover { background: var(--primary-600); }
        .form-control, .form-select { background: var(--card); color: var(--text); border-color: var(--border); }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 .15rem rgba(37,99,235,.2); }

        /* Mobile sidebar */
        @media (max-width: 991px) {
            .layout { grid-template-columns: 1fr; }
            .sidebar { position: fixed; top: 56px; left: 0; right: 0; height: auto; max-height: 60vh; overflow-y: auto; display: none; z-index: 1029; border-bottom: 1px solid var(--border); }
            .sidebar.show { display: block; }
        }
        
        /* Feature Grid/Cards (shared across dashboards) */
        .feature-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1rem; margin-bottom: 1rem; }
        .feature-card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; box-shadow: var(--shadow-sm); overflow: hidden; transition: transform .2s ease, box-shadow .2s ease; }
        .feature-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-lg); }
        .feature-header { background: rgba(37,99,235,.04); padding: 1rem 1.25rem; border-bottom: 1px solid var(--border); }
        .feature-title { display: flex; align-items: center; gap: .5rem; font-weight: 600; color: var(--text); }
        .feature-description { color: var(--muted); font-size: .9rem; margin-top: .25rem; }
        .feature-body { padding: 1rem 1.25rem; }
        .feature-actions { padding: 0 1.25rem 1.25rem; display: flex; gap: .5rem; }
    </style>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div id="app">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('home') }}" title="{{ $t('brand') }}">
                    <i class="fas fa-shield-alt"></i>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="topbarNav">
                    <ul class="navbar-nav me-auto">
                        <!-- kept empty: main navigation lives in sidebar -->
                    </ul>
                    <ul class="navbar-nav ms-auto align-items-center gap-2">
                        <li class="nav-item">
                            <button class="btn btn-sm btn-outline-secondary" id="themeToggle" type="button">
                                <i class="fas fa-moon"></i>
                                <span class="ms-1 d-none d-md-inline">Theme</span>
                            </button>
                        </li>
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center px-2 py-1" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius:9999px;">
                                    @php
                                        $initials = auth()->user()->getAvatarInitials();
                                        $avatarColor = auth()->user()->getAvatarColor();
                                        $avatarPath = auth()->user()->avatar;
                                        $avatarUrl = $avatarPath ? route('avatar.show', basename($avatarPath)) : null;
                                    @endphp
                                    @if($avatarUrl)
                                        <img src="{{ $avatarUrl }}" alt="{{ auth()->user()->name }}" class="avatar me-2"
                                             onerror="console.log('Avatar failed to load:', this.src); this.style.display='none'; this.nextElementSibling.style.display='inline-flex';"
                                             onload="console.log('Avatar loaded successfully:', this.src);"
                                             style="display: inline-block; vertical-align: middle;">
                                        <span class="avatar-fallback me-2"
                                              style="display: none; background-color: {{ $avatarColor }}; width: 32px; height: 32px; border-radius: 50%; align-items: center; justify-content: center; font-weight: 700; color: #fff; font-size: 0.75rem; vertical-align: middle;">{{ $initials }}</span>
                                    @else
                                        <span class="avatar-fallback me-2"
                                              style="background-color: {{ $avatarColor }}; width: 32px; height: 32px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; color: #fff; font-size: 0.75rem; vertical-align: middle;">{{ $initials }}</span>
                                    @endif
                                    <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                                    <span class="badge text-bg-secondary ms-2">{{ ucfirst(auth()->user()->role) }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li class="dropdown-header"><i class="fas fa-user me-2"></i>{{ auth()->user()->name }}</li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2"></i>{{ $t('view_profile') }}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user-edit me-2"></i>{{ $t('edit_profile') }}</a></li>
                                    <li><a class="dropdown-item" href="{{ route('profile.settings') }}"><i class="fas fa-cog me-2"></i>{{ $t('settings') }}</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="#" id="themeToggle">
                                            <i class="fas fa-moon me-2" id="themeIcon"></i>
                                            <span id="themeText">{{ $t('theme') }}: <span id="currentTheme">{{ $isDark ? ($appLang === 'en' ? 'Dark' : 'Gelap') : ($appLang === 'en' ? 'Light' : 'Terang') }}</span></span>
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>{{ $t('logout') }}</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-1"></i>Login</a></li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Sidebar + Content Layout -->
        <div class="layout">
            <!-- Sidebar -->
            <aside class="sidebar" id="sidebar">
                <div class="section-title">{{ $t('navigation') }}</div>
                <nav class="mb-3">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a class="side-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="fas fa-gauge"></i><span>{{ $t('dashboard') }}</span></a>
                            <a class="side-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}" href="{{ route('admin.reports') }}"><i class="fas fa-file-alt"></i><span>{{ $t('reports') }}</span></a>
                            <a class="side-link {{ request()->routeIs('admin.complaints*') ? 'active' : '' }}" href="{{ route('admin.complaints') }}"><i class="fas fa-triangle-exclamation"></i><span>{{ $t('complaints') }}</span></a>
                            <a class="side-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}" href="{{ route('admin.users') }}"><i class="fas fa-users"></i><span>{{ $t('users') }}</span></a>
                            <a class="side-link {{ request()->routeIs('admin.departments*') ? 'active' : '' }}" href="{{ route('admin.departments') }}"><i class="fas fa-sitemap"></i><span>{{ $t('departments') }}</span></a>
                            <a class="side-link {{ request()->routeIs('admin.monitoring*') ? 'active' : '' }}" href="{{ route('admin.monitoring') }}"><i class="fas fa-chart-line"></i><span>{{ $t('monitoring') }}</span></a>
                        @elseif(auth()->user()->isDepartmentHead() || auth()->user()->isStaff())
                            <a class="side-link {{ request()->routeIs('administration.dashboard') ? 'active' : '' }}" href="{{ route('administration.dashboard') }}"><i class="fas fa-gauge"></i><span>{{ $t('dashboard') }}</span></a>
                            <a class="side-link {{ request()->routeIs('administration.reports*') ? 'active' : '' }}" href="{{ route('administration.reports') }}"><i class="fas fa-file-alt"></i><span>{{ $t('reports') }}</span></a>
                            <a class="side-link {{ request()->routeIs('administration.complaints*') ? 'active' : '' }}" href="{{ route('administration.complaints') }}"><i class="fas fa-triangle-exclamation"></i><span>{{ $t('complaints') }}</span></a>
                            @if(auth()->user()->isDepartmentHead())
                                <a class="side-link {{ request()->routeIs('administration.staff*') ? 'active' : '' }}" href="{{ route('administration.staff') }}"><i class="fas fa-user-tie"></i><span>Staff</span></a>
                            @endif
                        @else
                            <a class="side-link {{ request()->routeIs('citizen.dashboard') ? 'active' : '' }}" href="{{ route('citizen.dashboard') }}"><i class="fas fa-gauge"></i><span>{{ $t('dashboard') }}</span></a>
                            <a class="side-link {{ request()->routeIs('citizen.reports.create') ? 'active' : '' }}" href="{{ route('citizen.reports.create') }}"><i class="fas fa-plus-circle"></i><span>{{ $appLang==='en' ? 'Create Report' : 'Buat Laporan' }}</span></a>
                            <a class="side-link {{ request()->routeIs('citizen.reports*') ? 'active' : '' }}" href="{{ route('citizen.reports.index') }}"><i class="fas fa-file-alt"></i><span>{{ $t('citizen_reports') }}</span></a>
                            <a class="side-link {{ request()->routeIs('citizen.complaints.create') ? 'active' : '' }}" href="{{ route('citizen.complaints.create') }}"><i class="fas fa-plus-circle"></i><span>{{ $appLang==='en' ? 'Create Complaint' : 'Buat Keluhan' }}</span></a>
                            <a class="side-link {{ request()->routeIs('citizen.complaints*') ? 'active' : '' }}" href="{{ route('citizen.complaints.index') }}"><i class="fas fa-triangle-exclamation"></i><span>{{ $t('citizen_complaints') }}</span></a>
                        @endif
                    @endauth
                </nav>
                
                @if(auth()->check() && auth()->user()->role === 'citizen')
                <div class="section-title">Quick Actions</div>
                <div class="px-3 pb-3">
                    <a href="{{ route('citizen.reports.create') }}" class="btn btn-primary w-100 mb-2"><i class="fas fa-plus me-2"></i>Laporan Baru</a>
                    <a href="{{ route('citizen.complaints.create') }}" class="btn btn-warning w-100"><i class="fas fa-plus me-2"></i>Keluhan Baru</a>
                </div>
                @endif
            </aside>

            <!-- Main Content -->
            <main class="content py-3">
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
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // Smooth scrolling for anchors
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });

        // Loading state for forms
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memproses...';
                    submitBtn.disabled = true;
                }
            });
        });

        // Debug avatar loading
        document.querySelectorAll('.avatar').forEach(function(img) {
            img.addEventListener('error', function() {
                console.log('Avatar failed to load:', this.src);
                // Hide the image and show fallback
                this.style.display = 'none';
                this.nextElementSibling.style.display = 'inline-flex';
            });
            img.addEventListener('load', function() {
                console.log('Avatar loaded successfully:', this.src);
            });
        });
        // Theme toggle and persistence
        (function() {
            const root = document.documentElement || document.querySelector('html');
            const themeToggle = document.getElementById('themeToggle');
            const metaCsrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const initialFromServer = document.documentElement.classList.contains('dark') ? 'dark' : 'light';

            const applyTheme = (t) => {
                if (t === 'dark') {
                    root.classList.add('dark');
                } else {
                    root.classList.remove('dark');
                }
            };

            // Determine saved preference: localStorage > server-rendered > system
            let saved = localStorage.getItem('theme') || initialFromServer || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            applyTheme(saved);

            // Set correct icon on the button
            if (themeToggle) {
                const icon = themeToggle.querySelector('i');
                if (icon) {
                    if (saved === 'dark') {
                        icon.classList.remove('fa-moon');
                        icon.classList.add('fa-sun');
                    } else {
                        icon.classList.remove('fa-sun');
                        icon.classList.add('fa-moon');
                    }
                }
            }

            // Helper to persist to server (if authenticated)
            const persistTheme = async (theme) => {
                try {
                    // Only attempt if user is authenticated (server-side rendered check)
                    const isAuth = {{ auth()->check() ? 'true' : 'false' }};
                    if (!isAuth) return;

                    // Send minimal payload (theme + language) to profile.settings.update route
                    const res = await fetch('{{ route('profile.settings.update') }}', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': metaCsrf,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ theme: theme, language: '{{ $appLang ?? 'id' }}' })
                    });

                    // If server returns 422 (validation), ignore silently
                    if (!res.ok) {
                        // Optionally we could show a toast; keep silent to avoid interrupting UX
                        console.warn('Failed to persist theme to server', res.status);
                    }
                } catch (e) {
                    console.warn('Error persisting theme', e);
                }
            };

            if (themeToggle) {
                themeToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    saved = (saved === 'dark') ? 'light' : 'dark';
                    localStorage.setItem('theme', saved);
                    applyTheme(saved);

                    const icon = document.getElementById('themeIcon');
                    const currentThemeText = document.getElementById('currentTheme');
                    const lang = '{{ $appLang }}';
                    
                    if (icon) {
                        if (saved === 'dark') {
                            icon.classList.remove('fa-moon');
                            icon.classList.add('fa-sun');
                            if (currentThemeText) {
                                currentThemeText.textContent = lang === 'en' ? 'Dark' : 'Gelap';
                            }
                        } else {
                            icon.classList.remove('fa-sun');
                            icon.classList.add('fa-moon');
                            if (currentThemeText) {
                                currentThemeText.textContent = lang === 'en' ? 'Light' : 'Terang';
                            }
                        }
                    }

                    // persist to server (fire-and-forget)
                    persistTheme(saved);
                });
                
                // Initialize icon based on current theme
                const icon = document.getElementById('themeIcon');
                if (icon && saved === 'dark') {
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                }
            }
        })();

        // Mobile: toggle sidebar from navbar toggler
        const navbarToggler = document.querySelector('.navbar-toggler');
        const sidebar = document.getElementById('sidebar');
        if (navbarToggler && sidebar) {
            navbarToggler.addEventListener('click', function() {
                if (window.matchMedia('(max-width: 991px)').matches) {
                    sidebar.classList.toggle('show');
                }
            });
        }

        // Ensure Bootstrap dropdowns work properly
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
            dropdownToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    const menu = this.nextElementSibling;
                    if (menu && menu.classList.contains('dropdown-menu')) {
                        // Close all other dropdowns
                        document.querySelectorAll('.dropdown-menu.show').forEach(m => {
                            if (m !== menu) m.classList.remove('show');
                        });
                        // Toggle current dropdown
                        menu.classList.toggle('show');
                    }
                });
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown')) {
                    document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                        menu.classList.remove('show');
                    });
                }
            });
        });
    </script>

    <!-- Deferred modals from views -->
    @stack('modals')

    @yield('scripts')
</body>
</html>

