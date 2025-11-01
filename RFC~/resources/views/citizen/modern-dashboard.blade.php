@extends('layouts.dashboard')

@section('title', 'Citizen Dashboard')

@section('content')
<!-- Page Header -->
<div class="page-header fade-in-up">
    <div class="page-header-content">
        <h1 class="page-title">Dashboard Warga</h1>
        <p class="page-subtitle">Selamat datang di sistem pelaporan dan keluhan pemerintah. Sampaikan aspirasi Anda dengan mudah.</p>
    </div>
</div>

<!-- Statistics Cards (Bootstrap row for clean horizontal layout) -->
<div class="row g-3 mb-3">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card fade-in-up h-100">
            <div class="stat-icon report-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <div class="stat-number">{{ $myReports->count() ?? 0 }}</div>
            <div class="stat-label">Laporan Saya</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card fade-in-up h-100">
            <div class="stat-icon complaint-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-number">{{ $myComplaints->count() ?? 0 }}</div>
            <div class="stat-label">Keluhan Saya</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card fade-in-up h-100">
            <div class="stat-icon gov-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-number">{{ $pendingCount ?? 0 }}</div>
            <div class="stat-label">Sedang Diproses</div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card fade-in-up h-100">
            <div class="stat-icon user-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number">{{ $resolvedCount ?? 0 }}</div>
            <div class="stat-label">Selesai</div>
        </div>
    </div>
</div>

<!-- Feature Cards -->
<div class="feature-grid">
    <!-- Buat Laporan Baru -->
    <div class="feature-card fade-in-up">
        <div class="feature-header">
            <div class="feature-title">
                <i class="fas fa-plus-circle text-primary"></i>
                Buat Laporan Baru
            </div>
            <div class="feature-description">
                Laporkan masalah, keluhan, atau saran Anda kepada pemerintah. Sistem kami akan memastikan laporan Anda ditangani dengan baik.
            </div>
        </div>
        <div class="feature-body">
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
            <div class="feature-actions">
                <a href="{{ route('citizen.reports.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Buat Laporan
                </a>
                <a href="{{ route('citizen.complaints.create') }}" class="btn btn-outline-primary">
                    <i class="fas fa-exclamation-triangle me-2"></i>Buat Keluhan
                </a>
            </div>
        </div>
    </div>

    <!-- Laporan Saya -->
    <div class="feature-card fade-in-up">
        <div class="feature-header">
            <div class="feature-title">
                <i class="fas fa-file-alt text-success"></i>
                Laporan Saya
            </div>
            <div class="feature-description">
                Pantau status dan progress laporan yang telah Anda kirimkan. Lihat tanggapan dan tindak lanjut dari pemerintah.
            </div>
        </div>
        <div class="feature-body">
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
            <div class="feature-actions">
                <a href="{{ route('citizen.reports.index') }}" class="btn btn-primary">
                    <i class="fas fa-eye me-2"></i>Lihat Semua
                </a>
            </div>
        </div>
    </div>

    <!-- Keluhan Saya -->
    <div class="feature-card fade-in-up">
        <div class="feature-header">
            <div class="feature-title">
                <i class="fas fa-exclamation-triangle text-warning"></i>
                Keluhan Saya
            </div>
            <div class="feature-description">
                Sampaikan keluhan Anda tentang pelayanan pemerintah dan dapatkan tanggapan yang konstruktif.
            </div>
        </div>
        <div class="feature-body">
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
            <div class="feature-actions">
                <a href="{{ route('citizen.complaints.index') }}" class="btn btn-primary">
                    <i class="fas fa-eye me-2"></i>Lihat Semua
                </a>
            </div>
        </div>
    </div>

    <!-- Panduan & Bantuan -->
    <div class="feature-card fade-in-up">
        <div class="feature-header">
            <div class="feature-title">
                <i class="fas fa-question-circle text-info"></i>
                Panduan & Bantuan
            </div>
            <div class="feature-description">
                Pelajari cara menggunakan sistem ini dengan efektif dan dapatkan bantuan jika mengalami kesulitan.
            </div>
        </div>
        <div class="feature-body">
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
            <div class="feature-actions">
                <a href="#" class="btn btn-primary">
                    <i class="fas fa-book me-2"></i>Baca Panduan
                </a>
                <a href="#" class="btn btn-outline-primary">
                    <i class="fas fa-headset me-2"></i>Hubungi Support
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Progress Tracking -->
@if($myReports && $myReports->count() > 0)
<div class="feature-card fade-in-up">
    <div class="feature-header">
        <div class="feature-title">
            <i class="fas fa-chart-line text-success"></i>
            Tracking Progress
        </div>
        <div class="feature-description">
            Pantau progress laporan Anda secara real-time dan lihat estimasi waktu penyelesaian.
        </div>
    </div>
    <div class="feature-body">
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
