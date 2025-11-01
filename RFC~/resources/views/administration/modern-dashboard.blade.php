@extends('layouts.dashboard')

@section('title', auth()->user()->isDepartmentHead() ? 'Department Head Dashboard' : 'Staff Dashboard')

@section('content')
<style>
    .dept-header {
        background: linear-gradient(135deg, #003d6b 0%, #004a7f 50%, #00527a 100%);
        color: white;
        padding: 2.5rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 61, 107, 0.5);
        position: relative;
        overflow: hidden;
    }

    .dept-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .dept-header::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 2rem;
        position: relative;
        z-index: 1;
    }

    .header-left {
        flex: 1;
    }

    .user-profile-section {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .user-avatar-large {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 700;
        color: white;
        border: 4px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        flex-shrink: 0;
        object-fit: cover;
    }

    .user-info h1 {
        margin: 0 0 0.5rem 0;
        font-size: 1.8rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        background: rgba(255, 255, 255, 0.2);
        padding: 0.35rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin: 0.5rem 0;
        backdrop-filter: blur(10px);
    }

    .user-info .description {
        margin: 0.75rem 0 0 0;
        opacity: 0.95;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .header-right {
        flex-shrink: 0;
    }

    .info-cards {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .info-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 1rem;
        min-width: 200px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .info-card i {
        font-size: 1.5rem;
        opacity: 0.9;
    }

    .info-card small {
        display: block;
        font-size: 0.75rem;
        opacity: 0.8;
        margin-bottom: 0.25rem;
    }

    .info-card strong {
        display: block;
        font-size: 0.95rem;
        font-weight: 600;
    }

    @media (max-width: 992px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start;
        }

        .header-right {
            width: 100%;
        }

        .info-cards {
            flex-direction: row;
            flex-wrap: wrap;
        }

        .info-card {
            flex: 1;
            min-width: 150px;
        }
    }

    @media (max-width: 768px) {
        .user-profile-section {
            flex-direction: column;
            text-align: center;
        }

        .user-info h1 {
            font-size: 1.5rem;
        }

        .info-cards {
            flex-direction: column;
        }

        .info-card {
            width: 100%;
        }
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        border-left: 4px solid #004a7f;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .report-icon {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .complaint-icon {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .gov-icon {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
    }

    .user-icon {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #004a7f;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        color: #6b7280;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .feature-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .feature-header {
        background: linear-gradient(135deg, #004a7f 0%, #00527a 100%);
        padding: 1.5rem;
        color: white;
    }

    .feature-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: white !important;
    }

    .feature-title i {
        color: white !important;
    }

    .feature-description {
        font-size: 0.9rem;
        opacity: 0.95;
        color: white !important;
    }
    
    .feature-header * {
        color: white !important;
    }

    .feature-body {
        padding: 1.5rem;
    }

    .feature-actions {
        padding: 0 1.5rem 1.5rem;
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
        gap: 1.5rem;
    }

    @media (max-width: 768px) {
        .feature-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Page Header -->
<div class="dept-header">
    <div class="header-content">
        <div class="header-left">
            <div class="user-profile-section">
                @php
                    $user = auth()->user();
                    $avatarColor = '#' . substr(md5($user->email), 0, 6);
                    $initials = strtoupper(substr($user->name, 0, 1));
                    if (str_word_count($user->name) > 1) {
                        $words = explode(' ', $user->name);
                        $initials = strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
                    }
                @endphp
                
                @if($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" class="user-avatar-large">
                @else
                    <div class="user-avatar-large" style="background: linear-gradient(135deg, {{ $avatarColor }} 0%, {{ $avatarColor }}dd 100%);">
                        {{ $initials }}
                    </div>
                @endif
                
                <div class="user-info">
                    <h1>
                        Selamat Datang, {{ $user->name }}! 👋
                    </h1>
                    <p class="role-badge">
                        <i class="fas fa-shield-alt me-2"></i>
                        @if($user->isDepartmentHead())
                            Department Head
                        @else
                            Staff
                        @endif
                    </p>
                    <p class="description">
                        @if($user->isDepartmentHead())
                            Kelola laporan dan staff di departemen {{ $department->name ?? 'Anda' }}
                        @else
                            Tangani laporan masyarakat dan keluhan di departemen {{ $department->name ?? 'Anda' }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <div class="header-right">
            <div class="info-cards">
                <div class="info-card">
                    <i class="fas fa-building"></i>
                    <div>
                        <small>Departemen</small>
                        <strong>{{ $department->name ?? 'N/A' }}</strong>
                    </div>
                </div>
                <div class="info-card">
                    <i class="fas fa-calendar-alt"></i>
                    <div>
                        <small>Tanggal</small>
                        <strong>{{ now()->format('d M Y') }}</strong>
                    </div>
                </div>
                <div class="info-card">
                    <i class="fas fa-clock"></i>
                    <div>
                        <small>Waktu</small>
                        <strong id="currentTime">{{ now()->format('H:i') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards (Bootstrap row for horizontal alignment) -->
<div class="row g-3 mb-3">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card fade-in-up h-100">
            <div class="stat-icon report-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-number">{{ $stats['total_reports'] ?? 0 }}</div>
            <div class="stat-label">
                @if(auth()->user()->isDepartmentHead())
                    Laporan Departemen
                @else
                    Laporan Saya
                @endif
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card fade-in-up h-100">
            <div class="stat-icon complaint-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-number">{{ $stats['total_complaints'] ?? 0 }}</div>
            <div class="stat-label">
                @if(auth()->user()->isDepartmentHead())
                    Keluhan Departemen
                @else
                    Keluhan Saya
                @endif
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card fade-in-up h-100">
            <div class="stat-icon gov-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-number">{{ $stats['pending_reports'] ?? 0 }}</div>
            <div class="stat-label">Menunggu Tindakan</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card fade-in-up h-100">
            <div class="stat-icon user-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number">{{ $stats['resolved_reports'] ?? 0 }}</div>
            <div class="stat-label">Diselesaikan</div>
        </div>
    </div>
</div>

<!-- Feature Cards -->
<div class="feature-grid">
    <!-- Laporan Masyarakat -->
    <div class="feature-card fade-in-up">
        <div class="feature-header">
            <div class="feature-title">
                <i class="fas fa-file-alt text-primary"></i>
                Laporan Masyarakat
            </div>
            <div class="feature-description">
                @if(auth()->user()->isDepartmentHead())
                    Kelola semua laporan yang masuk ke departemen Anda, assign ke staff, dan monitor progress penyelesaian.
                @else
                    Tangani laporan masyarakat yang ditugaskan kepada Anda, lakukan konfirmasi, dan teruskan ke atasan.
                @endif
            </div>
        </div>
        <div class="feature-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <h6 class="text-primary">Laporan Terbaru</h6>
                        <ul class="list-unstyled">
                            @if(isset($departmentReports) && $departmentReports->count() > 0)
                                @foreach($departmentReports->take(3) as $report)
                                <li class="mb-2">
                                    <i class="fas fa-circle text-primary me-2" style="font-size: 0.5rem;"></i>
                                    <small>{{ Str::limit($report->title, 30) }}</small>
                                    <span class="badge bg-{{ 
                                        in_array($report->status, ['submitted', 'pending']) ? 'warning' : 
                                        ($report->status == 'verified' ? 'primary' : 
                                        ($report->status == 'resolved' ? 'success' : 'info')) 
                                    }} ms-2" style="font-size: 0.7rem;">
                                        {{ $report->status == 'verified' ? 'Dikonfirmasi' : ucfirst($report->status) }}
                                    </span>
                                </li>
                                @endforeach
                            @else
                                <li><small class="text-muted">Belum ada laporan terbaru</small></li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <h6 class="text-warning">Status Overview</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <small>Baru Masuk</small>
                            <span class="badge bg-warning">{{ $stats['pending_reports'] ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <small>Sedang Diproses</small>
                            <span class="badge bg-info">{{ $stats['in_progress_reports'] ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small>Selesai</small>
                            <span class="badge bg-success">{{ $stats['resolved_reports'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="feature-actions">
                <a href="{{ route('administration.reports') }}" class="btn btn-primary">
                    <i class="fas fa-eye me-2"></i>Lihat Semua Laporan
                </a>
                @if(auth()->user()->isStaff())
                <a href="{{ route('administration.reports') }}" class="btn btn-outline-primary">
                    <i class="fas fa-tasks me-2"></i>Tugas Saya
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Workflow Management -->
    <div class="feature-card fade-in-up">
        <div class="feature-header">
            <div class="feature-title">
                <i class="fas fa-tasks text-success"></i>
                @if(auth()->user()->isDepartmentHead())
                    Manajemen Tim
                @else
                    Alur Kerja
                @endif
            </div>
            <div class="feature-description">
                @if(auth()->user()->isDepartmentHead())
                    Kelola staff departemen, assign tugas, dan monitor kinerja tim dalam menangani laporan masyarakat.
                @else
                    Ikuti alur kerja yang telah ditetapkan: terima laporan → konfirmasi → teruskan ke atasan sesuai prosedur.
                @endif
            </div>
        </div>
        <div class="feature-body">
            <div class="row">
                <div class="col-12">
                    @if(auth()->user()->isDepartmentHead())
                        <div class="mb-3">
                            <h6 class="text-success">Staff Departemen</h6>
                            @if(isset($departmentStaff) && $departmentStaff->count() > 0)
                                <div class="row">
                                    @foreach($departmentStaff->take(4) as $staff)
                                    <div class="col-6 mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar-sm me-2">
                                                {{ strtoupper(substr($staff->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <small class="fw-bold">{{ $staff->name }}</small>
                                                <br>
                                                <small class="text-muted">{{ ucfirst($staff->role) }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">Belum ada staff terdaftar</p>
                            @endif
                        </div>
                    @else
                        <div class="mb-3">
                            <h6 class="text-success">Alur Kerja Staff</h6>
                            <div class="workflow-steps">
                                <div class="workflow-step">
                                    <div class="step-number">1</div>
                                    <div class="step-content">
                                        <strong>Terima Laporan</strong>
                                        <small class="d-block text-muted">Laporan masuk dari admin atau masyarakat</small>
                                    </div>
                                </div>
                                <div class="workflow-step">
                                    <div class="step-number">2</div>
                                    <div class="step-content">
                                        <strong>Konfirmasi</strong>
                                        <small class="d-block text-muted">Verifikasi dan konfirmasi laporan</small>
                                    </div>
                                </div>
                                <div class="workflow-step">
                                    <div class="step-number">3</div>
                                    <div class="step-content">
                                        <strong>Teruskan</strong>
                                        <small class="d-block text-muted">Kirim ke Department Head</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <h6 class="text-info">Aktivitas Hari Ini</h6>
                        <div class="timeline">
                            <div class="timeline-item">
                                <i class="fas fa-circle text-primary"></i>
                                <small>{{ $stats['today_reports'] ?? 0 }} laporan baru diterima</small>
                            </div>
                            <div class="timeline-item">
                                <i class="fas fa-circle text-success"></i>
                                <small>{{ $stats['completed_today'] ?? 0 }} laporan diselesaikan</small>
                            </div>
                            <div class="timeline-item">
                                <i class="fas fa-circle text-warning"></i>
                                <small>{{ $stats['pending_action'] ?? 0 }} menunggu tindakan</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="feature-actions">
                @if(auth()->user()->isDepartmentHead())
                <a href="{{ route('administration.staff') }}" class="btn btn-primary">
                    <i class="fas fa-users me-2"></i>Kelola Staff
                </a>
                @endif
                <a href="{{ route('administration.reports') }}" class="btn btn-outline-primary">
                    <i class="fas fa-chart-line me-2"></i>Lihat Progress
                </a>
            </div>
        </div>
    </div>

    <!-- Keluhan & Feedback -->
    <div class="feature-card fade-in-up">
        <div class="feature-header">
            <div class="feature-title">
                <i class="fas fa-exclamation-triangle text-warning"></i>
                Keluhan & Feedback
            </div>
            <div class="feature-description">
                Tangani keluhan masyarakat dan feedback terkait pelayanan departemen untuk meningkatkan kualitas layanan.
            </div>
        </div>
        <div class="feature-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <h6 class="text-warning">Keluhan Terbaru</h6>
                        <ul class="list-unstyled">
                            @if(isset($departmentComplaints) && $departmentComplaints->count() > 0)
                                @foreach($departmentComplaints->take(3) as $complaint)
                                <li class="mb-2">
                                    <i class="fas fa-circle text-warning me-2" style="font-size: 0.5rem;"></i>
                                    <small>{{ Str::limit($complaint->title, 30) }}</small>
                                    <span class="badge bg-{{ 
                                        in_array($complaint->status, ['submitted', 'pending']) ? 'warning' : 
                                        ($complaint->status == 'resolved' ? 'success' : 'info') 
                                    }} ms-2" style="font-size: 0.7rem;">
                                        {{ ucfirst($complaint->status) }}
                                    </span>
                                </li>
                                @endforeach
                            @else
                                <li><small class="text-muted">Belum ada keluhan terbaru</small></li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <h6 class="text-info">Status Keluhan</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <small>Baru</small>
                            <span class="badge bg-warning">{{ $stats['pending_complaints'] ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <small>Investigasi</small>
                            <span class="badge bg-info">{{ $stats['investigating_complaints'] ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small>Selesai</small>
                            <span class="badge bg-success">{{ $stats['resolved_complaints'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="feature-actions">
                <a href="{{ route('administration.complaints') }}" class="btn btn-primary">
                    <i class="fas fa-eye me-2"></i>Lihat Semua Keluhan
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="feature-card fade-in-up">
        <div class="feature-header">
            <div class="feature-title">
                <i class="fas fa-bolt text-info"></i>
                Aksi Cepat
            </div>
            <div class="feature-description">
                Akses cepat ke fungsi-fungsi penting untuk mengelola tugas harian Anda dengan efisien.
            </div>
        </div>
        <div class="feature-body">
            <div class="row">
                <div class="col-md-6">
                    @if(auth()->user()->isDepartmentHead())
                    <div class="quick-action-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="quick-action-icon bg-primary">
                                <i class="fas fa-user-plus text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">Assign Laporan</h6>
                                <small class="text-muted">Tugaskan laporan ke staff</small>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="quick-action-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="quick-action-icon bg-success">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">Konfirmasi Laporan</h6>
                                <small class="text-muted">Verifikasi laporan baru</small>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="quick-action-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="quick-action-icon bg-warning">
                                <i class="fas fa-arrow-up text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">Teruskan ke Atasan</h6>
                                <small class="text-muted">Kirim laporan ke level atas</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="quick-action-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="quick-action-icon bg-info">
                                <i class="fas fa-download text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">Export Laporan</h6>
                                <small class="text-muted">Download data dalam Excel/PDF</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="quick-action-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="quick-action-icon bg-danger">
                                <i class="fas fa-bell text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">Notifikasi Urgent</h6>
                                <small class="text-muted">Laporan yang memerlukan perhatian</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="feature-actions">
                <a href="{{ route('administration.reports') }}" class="btn btn-primary">
                    <i class="fas fa-rocket me-2"></i>Mulai Bekerja
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    padding-left: 1rem;
}

.timeline-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
}

.timeline-item i {
    font-size: 0.5rem;
    margin-right: 0.75rem;
}

.quick-action-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.user-avatar-sm {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.8rem;
    font-weight: 600;
}

.workflow-steps {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.workflow-step {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.step-number {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--secondary-blue) 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.step-content {
    flex: 1;
}

/* Fade in animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}
</style>

<script>
    // Update time every second
    function updateTime() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const timeElement = document.getElementById('currentTime');
        if (timeElement) {
            timeElement.textContent = `${hours}:${minutes}`;
        }
    }
    
    // Update immediately and then every minute
    updateTime();
    setInterval(updateTime, 60000);
    
    // Add stagger animation to cards
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.fade-in-up');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    });
</script>
@endsection
