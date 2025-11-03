@extends('layouts.dashboard')

@section('title', 'Citizen Dashboard')

@section('content')
<style>
    .navbar {
        display: flex !important;
        visibility: visible !important;
        opacity: 1 !important;
        z-index: 1030 !important;
    }

    body {
        padding-top: 80px !important;
        background: #f8f9fa;
    }

    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #003d6b 0%, #004a7f 50%, #00527a 100%);
        color: white;
        padding: 2.5rem 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 61, 107, 0.5);
        position: relative;
        overflow: hidden;
    }

    .dashboard-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    .dashboard-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 50%;
    }

    .dashboard-header-title {
        font-size: 2rem;
        font-weight: 700;
        margin: 0 0 0.5rem 0;
        position: relative;
        z-index: 1;
    }

    .dashboard-header-desc {
        font-size: 0.95rem;
        opacity: 0.95;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .dashboard-header-time {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-top: 1rem;
        position: relative;
        z-index: 1;
    }

    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
        position: relative;
        z-index: 1;
    }

    .quick-stat-box {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 1rem;
        border-radius: 8px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        text-align: center;
        transition: all 0.3s ease;
    }

    .quick-stat-box:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-4px);
        border-color: rgba(255, 255, 255, 0.5);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .quick-stat-icon {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .quick-stat-number {
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .quick-stat-label {
        font-size: 0.8rem;
        opacity: 0.9;
    }

    .dashboard-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #f0f0f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .dashboard-card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .dashboard-card-header {
        background: linear-gradient(135deg, #004a7f 0%, #00527a 100%);
        padding: 1.25rem;
        border-bottom: none;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: white;
    }

    .dashboard-card-header i {
        color: white;
        font-size: 1.3rem;
    }

    .dashboard-card-header h3 {
        margin: 0;
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .dashboard-card-body {
        padding: 1.5rem;
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: linear-gradient(135deg, #003d6b 0%, #00527a 100%);
        color: white;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .action-btn:hover {
        background: linear-gradient(135deg, #002d4f 0%, #003d5a 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 61, 107, 0.3);
        color: white;
    }

    .action-btn.outline {
        background: white;
        color: #003d6b;
        border: 2px solid #003d6b;
    }

    .action-btn.outline:hover {
        background: #003d6b;
        color: white;
    }

    @media (max-width: 768px) {
        .dashboard-header {
            padding: 1.5rem;
        }
        .dashboard-header-title {
            font-size: 1.5rem;
        }
        .quick-stats {
            grid-template-columns: repeat(2, 1fr);
        }
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Dashboard Header -->
<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="dashboard-header-title">
            <i class="fas fa-user-circle me-2"></i>Dashboard Warga
        </div>
        <div class="dashboard-header-desc">
            Selamat datang di sistem pelaporan dan keluhan pemerintah. Sampaikan aspirasi Anda dengan mudah.
        </div>
        <div class="dashboard-header-time">
            <i class="fas fa-clock me-1"></i>{{ now()->format('d F Y, H:i') }}
        </div>
        <div class="quick-stats">
            <div class="quick-stat-box">
                <div class="quick-stat-icon"><i class="fas fa-file-alt"></i></div>
                <div class="quick-stat-number">{{ $myReports->count() ?? 0 }}</div>
                <div class="quick-stat-label">Laporan Saya</div>
            </div>
            <div class="quick-stat-box">
                <div class="quick-stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="quick-stat-number">{{ $myComplaints->count() ?? 0 }}</div>
                <div class="quick-stat-label">Keluhan Saya</div>
            </div>
            <div class="quick-stat-box">
                <div class="quick-stat-icon"><i class="fas fa-clock"></i></div>
                <div class="quick-stat-number">{{ $pendingCount ?? 0 }}</div>
                <div class="quick-stat-label">Sedang Diproses</div>
            </div>
            <div class="quick-stat-box">
                <div class="quick-stat-icon"><i class="fas fa-check-circle"></i></div>
                <div class="quick-stat-number">{{ $resolvedCount ?? 0 }}</div>
                <div class="quick-stat-label">Selesai</div>
            </div>
        </div>
    </div>
</div>

<!-- Feature Cards -->
<div class="dashboard-container">
    <div class="dashboard-grid">
        <!-- Buat Laporan Baru -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <i class="fas fa-plus-circle"></i>
                <h3>Buat Laporan Baru</h3>
            </div>
            <div class="dashboard-card-body">
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <h6 class="text-primary">Jenis Laporan</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="report-type-item mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="report-type-icon bg-primary">
                                            <i class="fas fa-road text-white"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-1">Infrastruktur</h6>
                                            <small class="text-muted">Jalan, jembatan, fasilitas umum</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="report-type-item mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="report-type-icon bg-success">
                                            <i class="fas fa-leaf text-white"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-1">Lingkungan</h6>
                                            <small class="text-muted">Kebersihan, polusi, sampah</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="report-type-item mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="report-type-icon bg-warning">
                                            <i class="fas fa-shield-alt text-white"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-1">Keamanan</h6>
                                            <small class="text-muted">Ketertiban, kriminalitas</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="report-type-item mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="report-type-icon bg-info">
                                            <i class="fas fa-users text-white"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="mb-1">Pelayanan</h6>
                                            <small class="text-muted">Administrasi, birokrasi</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="margin-top: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="{{ route('citizen.reports.create') }}" class="action-btn">
                    <i class="fas fa-plus"></i>Buat Laporan
                </a>
                <a href="{{ route('citizen.complaints.create') }}" class="action-btn outline">
                    <i class="fas fa-exclamation-triangle"></i>Buat Keluhan
                </a>
            </div>
        </div>
    </div>

    <!-- Laporan Saya -->
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <i class="fas fa-file-alt"></i>
            <h3>Laporan Saya</h3>
        </div>
        <div class="dashboard-card-body">
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <h6 class="text-success">Laporan Terbaru</h6>
                        <ul class="list-unstyled">
                            @if($myReports && $myReports->count() > 0)
                                @foreach($myReports->take(4) as $report)
                                <li class="mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-circle text-primary me-2" style="font-size: 0.5rem;"></i>
                                            <small>{{ Str::limit($report->title, 25) }}</small>
                                        </div>
                                        <span class="badge bg-{{ 
                                            in_array($report->status, ['submitted', 'pending']) ? 'warning' : 
                                            ($report->status == 'verified' ? 'primary' : 
                                            ($report->status == 'resolved' ? 'success' : 'info')) 
                                        }}" style="font-size: 0.7rem;">
                                            {{ $report->status == 'verified' ? 'Dikonfirmasi' : ucfirst($report->status) }}
                                        </span>
                                    </div>
                                    <small class="text-muted d-block ms-3">
                                        {{ $report->department->name ?? 'Belum ditentukan' }} • {{ $report->created_at->diffForHumans() }}
                                    </small>
                                </li>
                                @endforeach
                            @else
                                <li><small class="text-muted">Belum ada laporan. <a href="{{ route('citizen.reports.create') }}" class="text-primary">Buat laporan pertama Anda</a></small></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div style="margin-top: 1.5rem;">
                <a href="{{ route('citizen.reports.index') }}" class="action-btn">
                    <i class="fas fa-eye"></i>Lihat Semua
                </a>
            </div>
        </div>
    </div>

    <!-- Keluhan Saya -->
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <i class="fas fa-exclamation-triangle"></i>
            <h3>Keluhan Saya</h3>
        </div>
        <div class="dashboard-card-body">
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <h6 class="text-warning">Keluhan Terbaru</h6>
                        <ul class="list-unstyled">
                            @if($myComplaints && $myComplaints->count() > 0)
                                @foreach($myComplaints->take(4) as $complaint)
                                <li class="mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-circle text-warning me-2" style="font-size: 0.5rem;"></i>
                                            <small>{{ Str::limit($complaint->title, 25) }}</small>
                                        </div>
                                        <span class="badge bg-{{ 
                                            in_array($complaint->status, ['submitted', 'pending']) ? 'warning' : 
                                            ($complaint->status == 'resolved' ? 'success' : 'info') 
                                        }}" style="font-size: 0.7rem;">
                                            {{ ucfirst($complaint->status) }}
                                        </span>
                                    </div>
                                    <small class="text-muted d-block ms-3">
                                        {{ $complaint->department->name ?? 'Belum ditentukan' }} • {{ $complaint->created_at->diffForHumans() }}
                                    </small>
                                </li>
                                @endforeach
                            @else
                                <li><small class="text-muted">Belum ada keluhan. <a href="{{ route('citizen.complaints.create') }}" class="text-primary">Sampaikan keluhan Anda</a></small></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div style="margin-top: 1.5rem;">
                <a href="{{ route('citizen.complaints.index') }}" class="action-btn">
                    <i class="fas fa-eye"></i>Lihat Semua
                </a>
            </div>
        </div>
    </div>

    <!-- Panduan & Bantuan -->
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <i class="fas fa-question-circle"></i>
            <h3>Panduan & Bantuan</h3>
        </div>
        <div class="dashboard-card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="help-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="help-icon bg-primary">
                                <i class="fas fa-book text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">Panduan Penggunaan</h6>
                                <small class="text-muted">Cara membuat laporan dan keluhan</small>
                            </div>
                        </div>
                    </div>
                    <div class="help-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="help-icon bg-success">
                                <i class="fas fa-lightbulb text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">Tips & Trik</h6>
                                <small class="text-muted">Cara menulis laporan yang efektif</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="help-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="help-icon bg-warning">
                                <i class="fas fa-phone text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">Kontak Bantuan</h6>
                                <small class="text-muted">Hubungi kami jika butuh bantuan</small>
                            </div>
                        </div>
                    </div>
                    <div class="help-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="help-icon bg-info">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">Status & Timeline</h6>
                                <small class="text-muted">Memahami proses penanganan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="margin-top: 1.5rem; display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="#" class="action-btn">
                    <i class="fas fa-book"></i>Baca Panduan
                </a>
                <a href="#" class="action-btn outline">
                    <i class="fas fa-headset"></i>Hubungi Support
                </a>
            </div>
        </div>
    </div>
    </div>
</div>

<!-- Progress Tracking -->
@if($myReports && $myReports->count() > 0)
<div class="dashboard-container">
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <i class="fas fa-chart-line"></i>
            <h3>Tracking Progress</h3>
        </div>
        <div class="dashboard-card-body">
        <div class="row">
            @foreach($myReports->take(2) as $report)
            <div class="col-md-6">
                <div class="progress-item mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">{{ Str::limit($report->title, 30) }}</h6>
                        <span class="badge bg-{{ 
                            in_array($report->status, ['submitted', 'pending']) ? 'warning' : 
                            ($report->status == 'verified' ? 'primary' : 
                            ($report->status == 'resolved' ? 'success' : 'info')) 
                        }}">
                            {{ $report->status == 'verified' ? 'Dikonfirmasi' : ucfirst($report->status) }}
                        </span>
                    </div>
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar bg-{{ 
                            in_array($report->status, ['submitted', 'pending']) ? 'warning' : 
                            ($report->status == 'verified' ? 'primary' : 
                            ($report->status == 'resolved' ? 'success' : 'info')) 
                        }}" style="width: {{ 
                            in_array($report->status, ['submitted', 'pending']) ? '25' : 
                            ($report->status == 'verified' ? '50' : 
                            ($report->status == 'assigned' ? '75' : 
                            ($report->status == 'resolved' ? '100' : '60'))) 
                        }}%"></div>
                    </div>
                    <small class="text-muted">
                        Dibuat {{ $report->created_at->diffForHumans() }}
                        @if($report->assignedUser)
                            • Ditangani oleh {{ $report->assignedUser->name }}
                        @endif
                    </small>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<style>
.report-type-icon, .help-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.progress-item {
    padding: 1rem;
    border: 1px solid var(--border-light);
    border-radius: 8px;
    background: rgba(59, 130, 246, 0.02);
}
</style>
@endsection
