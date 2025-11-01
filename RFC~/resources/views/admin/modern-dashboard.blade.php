@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('content')
<style>
    /* Ensure navbar is visible */
    .navbar {
        display: flex !important;
        visibility: visible !important;
        opacity: 1 !important;
        z-index: 1030 !important;
    }

    body {
        padding-top: 80px !important;
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
    }

    .dashboard-header-desc {
        font-size: 0.95rem;
        opacity: 0.95;
        margin: 0;
    }

    .dashboard-header-time {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-top: 1rem;
    }

    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .quick-stat-box {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 1rem;
        border-radius: 8px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
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

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .dashboard-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #f0f0f0;
        overflow: hidden;
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

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .stat-item {
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 4px solid #0284c7;
        text-align: center;
    }

    .stat-item.pending {
        border-left-color: #0284c7;
        background: #f0f9ff;
    }

    .stat-item.resolved {
        border-left-color: #10b981;
        background: #f0fdf4;
    }

    .stat-item.warning {
        border-left-color: #f59e0b;
        background: #fef3c7;
    }

    .stat-item.danger {
        border-left-color: #ef4444;
        background: #fef2f2;
    }

    .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #666;
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

        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Dashboard Header -->
<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="dashboard-header-title">
            <i class="fas fa-shield-alt me-2"></i>Admin Dashboard
        </div>
        <div class="dashboard-header-desc">
            Kelola sistem laporan dan keluhan pemerintah secara terpusat
        </div>
        <div class="dashboard-header-time">
            <i class="fas fa-clock me-1"></i>{{ now()->format('d F Y, H:i') }}
        </div>
        <div class="quick-stats">
            <div class="quick-stat-box">
                <div class="quick-stat-icon"><i class="fas fa-file-alt"></i></div>
                <div class="quick-stat-number">{{ $stats['total_reports'] ?? 0 }}</div>
                <div class="quick-stat-label">Total Laporan</div>
            </div>
            <div class="quick-stat-box">
                <div class="quick-stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="quick-stat-number">{{ $stats['total_complaints'] ?? 0 }}</div>
                <div class="quick-stat-label">Total Keluhan</div>
            </div>
            <div class="quick-stat-box">
                <div class="quick-stat-icon"><i class="fas fa-users"></i></div>
                <div class="quick-stat-number">{{ $stats['total_users'] ?? 0 }}</div>
                <div class="quick-stat-label">Total Pengguna</div>
            </div>
            <div class="quick-stat-box">
                <div class="quick-stat-icon"><i class="fas fa-hourglass-half"></i></div>
                <div class="quick-stat-number">{{ $stats['pending_reports'] ?? 0 }}</div>
                <div class="quick-stat-label">Laporan Pending</div>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Grid -->
<div class="dashboard-container">
    <div class="dashboard-grid">
        <!-- Status Ringkas -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <i class="fas fa-chart-line"></i>
                <h3>Status Ringkas</h3>
            </div>
            <div class="dashboard-card-body">
                <div class="stats-grid">
                    <div class="stat-item pending">
                        <div class="stat-number">{{ $stats['pending_reports'] ?? 0 }}</div>
                        <div class="stat-label"><i class="fas fa-hourglass-half me-1"></i>Laporan Pending</div>
                    </div>
                    <div class="stat-item resolved">
                        <div class="stat-number">{{ $stats['resolved_reports'] ?? 0 }}</div>
                        <div class="stat-label"><i class="fas fa-check-circle me-1"></i>Laporan Selesai</div>
                    </div>
                    <div class="stat-item warning">
                        <div class="stat-number">{{ $stats['pending_complaints'] ?? 0 }}</div>
                        <div class="stat-label"><i class="fas fa-exclamation me-1"></i>Keluhan Pending</div>
                    </div>
                    <div class="stat-item danger">
                        <div class="stat-number">{{ $stats['resolved_complaints'] ?? 0 }}</div>
                        <div class="stat-label"><i class="fas fa-check me-1"></i>Keluhan Selesai</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aktivitas Terbaru -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <i class="fas fa-history"></i>
                <h3>Aktivitas Terbaru</h3>
            </div>
            <div class="dashboard-card-body">
                <div style="max-height: 300px; overflow-y: auto;">
                    @if(isset($recentReports) && $recentReports->count() > 0)
                        @foreach($recentReports->take(5) as $report)
                        <div style="padding: 0.75rem 0; border-bottom: 1px solid #f0f0f0; display: flex; gap: 0.75rem; align-items: flex-start;">
                            <div style="width: 6px; height: 6px; background: #0284c7; border-radius: 50%; margin-top: 0.5rem; flex-shrink: 0;"></div>
                            <div style="flex: 1; min-width: 0;">
                                <div style="font-weight: 600; color: #2c3e50; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $report->title }}</div>
                                <div style="font-size: 0.8rem; color: #999;">{{ $report->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div style="text-align: center; padding: 2rem; color: #999;">
                            <i class="fas fa-inbox" style="font-size: 2rem; margin-bottom: 1rem; display: block;"></i>
                            <p>Belum ada aktivitas terbaru</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Departemen -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <i class="fas fa-sitemap"></i>
                <h3>Departemen Aktif</h3>
            </div>
            <div class="dashboard-card-body">
                @if(isset($departments) && $departments->count() > 0)
                    <div style="display: grid; grid-template-columns: 1fr; gap: 0.75rem;">
                        @foreach($departments->take(5) as $dept)
                        <div style="padding: 0.75rem; background: #f8f9fa; border-radius: 8px; border-left: 3px solid #0284c7;">
                            <div style="font-weight: 600; color: #2c3e50;">{{ $dept->name }}</div>
                            <div style="font-size: 0.8rem; color: #999;">{{ $dept->code }}</div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 2rem; color: #999;">
                        <p>Belum ada departemen terdaftar</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Additional Feature Cards -->
<div class="dashboard-container">
    <div class="dashboard-grid">
        <!-- Quick Actions -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <i class="fas fa-bolt"></i>
                <h3>Aksi Cepat</h3>
            </div>
            <div class="dashboard-card-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <a href="{{ route('admin.reports') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f0f9ff; border-radius: 8px; border-left: 3px solid #0284c7; text-decoration: none; color: #2c3e50; transition: all 0.3s ease;" onmouseover="this.style.background='#e0f2fe'" onmouseout="this.style.background='#f0f9ff'">
                        <div style="width: 40px; height: 40px; background: #0284c7; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem;">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: 0.9rem;">Lihat Laporan</div>
                            <div style="font-size: 0.8rem; color: #999;">Kelola semua laporan</div>
                        </div>
                    </a>
                    <a href="{{ route('admin.monitoring') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #f0fdf4; border-radius: 8px; border-left: 3px solid #10b981; text-decoration: none; color: #2c3e50; transition: all 0.3s ease;" onmouseover="this.style.background='#dcfce7'" onmouseout="this.style.background='#f0fdf4'">
                        <div style="width: 40px; height: 40px; background: #10b981; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem;">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: 0.9rem;">Monitoring</div>
                            <div style="font-size: 0.8rem; color: #999;">Pantau sistem</div>
                        </div>
                    </a>
                    <a href="{{ route('admin.departments') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #fef3c7; border-radius: 8px; border-left: 3px solid #f59e0b; text-decoration: none; color: #2c3e50; transition: all 0.3s ease;" onmouseover="this.style.background='#fde68a'" onmouseout="this.style.background='#fef3c7'">
                        <div style="width: 40px; height: 40px; background: #f59e0b; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem;">
                            <i class="fas fa-building"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: 0.9rem;">Departemen</div>
                            <div style="font-size: 0.8rem; color: #999;">Kelola departemen</div>
                        </div>
                    </a>
                    <a href="{{ route('admin.users') }}" style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: #fef2f2; border-radius: 8px; border-left: 3px solid #ef4444; text-decoration: none; color: #2c3e50; transition: all 0.3s ease;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                        <div style="width: 40px; height: 40px; background: #ef4444; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.2rem;">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <div style="font-weight: 600; font-size: 0.9rem;">Pengguna</div>
                            <div style="font-size: 0.8rem; color: #999;">Kelola pengguna</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Real-time System Monitoring -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <i class="fas fa-heartbeat"></i>
                <h3>Monitoring Real-Time</h3>
            </div>
            <div class="dashboard-card-body">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <!-- SLA Monitoring -->
                    @php
                        $slaStatus = ($stats['sla_breached'] ?? 0) > 0 ? 'danger' : 'success';
                        $slaColor = $slaStatus === 'danger' ? '#ef4444' : '#10b981';
                        $slaBackground = $slaStatus === 'danger' ? '#fef2f2' : '#f0fdf4';
                    @endphp
                    <div style="padding: 1rem; background: {{ $slaBackground }}; border-radius: 8px; border-left: 3px solid {{ $slaColor }};">
                        <div style="font-size: 0.9rem; color: #999; margin-bottom: 0.5rem;">
                            <i class="fas fa-clock me-1"></i>SLA Status
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <div style="width: 10px; height: 10px; background: {{ $slaColor }}; border-radius: 50%;"></div>
                            <span style="font-weight: 600; color: {{ $slaColor }};">{{ $stats['sla_breached'] ?? 0 }} Breached</span>
                        </div>
                        <div style="font-size: 0.8rem; color: #666;">Due Soon: {{ $stats['due_soon'] ?? 0 }}</div>
                    </div>

                    <!-- Assignment Status -->
                    @php
                        $assignmentStatus = ($stats['pending_assignments'] ?? 0) > 0 ? 'warning' : 'success';
                        $assignmentColor = $assignmentStatus === 'warning' ? '#f59e0b' : '#10b981';
                        $assignmentBackground = $assignmentStatus === 'warning' ? '#fef3c7' : '#f0fdf4';
                    @endphp
                    <div style="padding: 1rem; background: {{ $assignmentBackground }}; border-radius: 8px; border-left: 3px solid {{ $assignmentColor }};">
                        <div style="font-size: 0.9rem; color: #999; margin-bottom: 0.5rem;">
                            <i class="fas fa-tasks me-1"></i>Pending Assignment
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <div style="width: 10px; height: 10px; background: {{ $assignmentColor }}; border-radius: 50%;"></div>
                            <span style="font-weight: 600; color: {{ $assignmentColor }};">{{ $stats['pending_assignments'] ?? 0 }} Tasks</span>
                        </div>
                        <div style="font-size: 0.8rem; color: #666;">Requires Action</div>
                    </div>

                    <!-- In Progress Monitoring -->
                    <div style="padding: 1rem; background: #f0f9ff; border-radius: 8px; border-left: 3px solid #0284c7;">
                        <div style="font-size: 0.9rem; color: #999; margin-bottom: 0.5rem;">
                            <i class="fas fa-spinner me-1"></i>In Progress
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <div style="width: 10px; height: 10px; background: #0284c7; border-radius: 50%;"></div>
                            <span style="font-weight: 600; color: #0284c7;">{{ $stats['in_progress_reports'] ?? 0 }} Reports</span>
                        </div>
                        <div style="font-size: 0.8rem; color: #666;">Being Processed</div>
                    </div>

                    <!-- Completion Rate -->
                    @php
                        $totalReports = ($stats['total_reports'] ?? 0);
                        $resolvedReports = ($stats['resolved_reports'] ?? 0);
                        $completionRate = $totalReports > 0 ? round(($resolvedReports / $totalReports) * 100, 1) : 0;
                        $rateColor = $completionRate >= 80 ? '#10b981' : ($completionRate >= 50 ? '#f59e0b' : '#ef4444');
                        $rateBackground = $completionRate >= 80 ? '#f0fdf4' : ($completionRate >= 50 ? '#fef3c7' : '#fef2f2');
                    @endphp
                    <div style="padding: 1rem; background: {{ $rateBackground }}; border-radius: 8px; border-left: 3px solid {{ $rateColor }};">
                        <div style="font-size: 0.9rem; color: #999; margin-bottom: 0.5rem;">
                            <i class="fas fa-chart-pie me-1"></i>Completion Rate
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <div style="width: 10px; height: 10px; background: {{ $rateColor }}; border-radius: 50%;"></div>
                            <span style="font-weight: 600; color: {{ $rateColor }};">{{ $completionRate }}%</span>
                        </div>
                        <div style="font-size: 0.8rem; color: #666;">{{ $resolvedReports }}/{{ $totalReports }} Resolved</div>
                    </div>
                </div>

                <!-- Real-time Alerts -->
                <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e9ecef;">
                    <div style="font-weight: 600; color: #2c3e50; margin-bottom: 0.75rem;">
                        <i class="fas fa-exclamation-triangle me-1" style="color: #ef4444;"></i>Active Alerts
                    </div>
                    @if(($stats['sla_breached'] ?? 0) > 0)
                        <div style="padding: 0.75rem; background: #fef2f2; border-left: 3px solid #ef4444; border-radius: 4px; margin-bottom: 0.5rem; font-size: 0.9rem;">
                            <i class="fas fa-times-circle" style="color: #ef4444;"></i> {{ $stats['sla_breached'] }} laporan telah melampaui SLA
                        </div>
                    @endif
                    @if(($stats['pending_assignments'] ?? 0) > 0)
                        <div style="padding: 0.75rem; background: #fef3c7; border-left: 3px solid #f59e0b; border-radius: 4px; margin-bottom: 0.5rem; font-size: 0.9rem;">
                            <i class="fas fa-exclamation-circle" style="color: #f59e0b;"></i> {{ $stats['pending_assignments'] }} tugas menunggu assignment
                        </div>
                    @endif
                    @if(($stats['due_soon'] ?? 0) > 0)
                        <div style="padding: 0.75rem; background: #fef3c7; border-left: 3px solid #f59e0b; border-radius: 4px; font-size: 0.9rem;">
                            <i class="fas fa-info-circle" style="color: #f59e0b;"></i> {{ $stats['due_soon'] }} laporan akan jatuh tempo dalam 24 jam
                        </div>
                    @endif
                    @if(($stats['sla_breached'] ?? 0) == 0 && ($stats['pending_assignments'] ?? 0) == 0 && ($stats['due_soon'] ?? 0) == 0)
                        <div style="padding: 0.75rem; background: #f0fdf4; border-left: 3px solid #10b981; border-radius: 4px; font-size: 0.9rem;">
                            <i class="fas fa-check-circle" style="color: #10b981;"></i> Semua sistem berjalan normal
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
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

.alert-sm {
    font-size: 0.875rem;
}
</style>
<!-- Feature Cards -->
<div class="feature-grid" style="display: none;">
    <!-- All Reports and Publications -->
    <div class="feature-card fade-in-up">
        <div class="feature-header">
            <div class="feature-title">
                <i class="fas fa-file-alt text-primary"></i>
                Semua Laporan & Publikasi
            </div>
            <div class="feature-description">
                Lihat semua laporan dari masyarakat, termasuk laporan manajemen dan tantangan kinerja, laporan semi-tahunan ke Kongres, dan program kontrol penipuan dan penyalahgunaan perawatan kesehatan.
            </div>
        </div>
        <div class="feature-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <h6 class="text-primary">Laporan Terbaru</h6>
                        <ul class="list-unstyled">
                            @if(isset($recentReports) && $recentReports->count() > 0)
                                @foreach($recentReports->take(3) as $report)
                                <li class="mb-2">
                                    <i class="fas fa-circle text-primary me-2" style="font-size: 0.5rem;"></i>
                                    <small>{{ $report->title }}</small>
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
                            <small>Pending</small>
                            <span class="badge bg-warning">{{ $stats['pending_reports'] ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <small>In Progress</small>
                            <span class="badge bg-info">{{ $stats['in_progress_reports'] ?? 0 }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small>Resolved</small>
                            <span class="badge bg-success">{{ $stats['resolved_reports'] ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="feature-actions">
                <a href="{{ route('admin.reports') }}" class="btn btn-primary">
                    <i class="fas fa-eye me-2"></i>Lihat Semua Laporan
                </a>
                <a href="{{ route('admin.monitoring') }}" class="btn btn-outline-primary">
                    <i class="fas fa-chart-line me-2"></i>Monitoring
                </a>
            </div>
        </div>
    </div>

    <!-- Work Plan -->
    <div class="feature-card fade-in-up">
        <div class="feature-header">
            <div class="feature-title">
                <i class="fas fa-tasks text-success"></i>
                Rencana Kerja
            </div>
            <div class="feature-description">
                Rencana Kerja FRC menetapkan berbagai proyek termasuk audit dan evaluasi yang sedang berlangsung atau direncanakan untuk ditangani selama tahun fiskal dan seterusnya.
            </div>
        </div>
        <div class="feature-body">
            <div class="row">
                <div class="col-12">
                    <div class="mb-3">
                        <h6 class="text-success">Departemen Aktif</h6>
                        @if(isset($departments) && $departments->count() > 0)
                            <div class="row">
                                @foreach($departments->take(4) as $dept)
                                <div class="col-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-building text-primary me-2"></i>
                                        <small>{{ $dept->name }}</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">Belum ada departemen terdaftar</p>
                        @endif
                    </div>
                    <div class="mb-3">
                        <h6 class="text-info">Aktivitas Terbaru</h6>
                        <div class="timeline">
                            <div class="timeline-item">
                                <i class="fas fa-circle text-primary"></i>
                                <small>{{ $stats['today_reports'] ?? 0 }} laporan baru hari ini</small>
                            </div>
                            <div class="timeline-item">
                                <i class="fas fa-circle text-warning"></i>
                                <small>{{ $stats['pending_assignments'] ?? 0 }} tugas menunggu assignment</small>
                            </div>
                            <div class="timeline-item">
                                <i class="fas fa-circle text-success"></i>
                                <small>{{ $stats['completed_today'] ?? 0 }} laporan diselesaikan hari ini</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="feature-actions">
                <a href="{{ route('admin.departments') }}" class="btn btn-primary">
                    <i class="fas fa-building me-2"></i>Kelola Departemen
                </a>
                <a href="{{ route('admin.users') }}" class="btn btn-outline-primary">
                    <i class="fas fa-users me-2"></i>Kelola Pengguna
                </a>
            </div>
        </div>
    </div>

    <!-- System Management -->
    <div class="feature-card fade-in-up">
        <div class="feature-header">
            <div class="feature-title">
                <i class="fas fa-cogs text-info"></i>
                Manajemen Sistem
            </div>
            <div class="feature-description">
                Kelola pengaturan sistem, konfigurasi, dan pemeliharaan platform FRC untuk memastikan operasional yang optimal.
            </div>
        </div>
        <div class="feature-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <h6 class="text-info">Status Sistem</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <small>Server Status</small>
                            <span class="badge bg-success">Online</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <small>Database</small>
                            <span class="badge bg-success">Connected</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <small>Storage</small>
                            <span class="badge bg-warning">75% Used</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <h6 class="text-danger">Alerts & Notifications</h6>
                        <div class="alert alert-warning alert-sm p-2 mb-2">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <small>{{ $stats['sla_breached'] ?? 0 }} laporan melewati SLA</small>
                        </div>
                        <div class="alert alert-info alert-sm p-2">
                            <i class="fas fa-info-circle me-1"></i>
                            <small>{{ $stats['due_soon'] ?? 0 }} laporan akan jatuh tempo</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="feature-actions">
                <a href="{{ route('admin.monitoring') }}" class="btn btn-primary">
                    <i class="fas fa-chart-line me-2"></i>System Monitoring
                </a>
                <a href="#" class="btn btn-outline-primary">
                    <i class="fas fa-cog me-2"></i>Settings
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="feature-card fade-in-up">
        <div class="feature-header">
            <div class="feature-title">
                <i class="fas fa-bolt text-warning"></i>
                Aksi Cepat
            </div>
            <div class="feature-description">
                Akses cepat ke fungsi-fungsi penting untuk mengelola laporan dan keluhan secara efisien.
            </div>
        </div>
        <div class="feature-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="quick-action-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="quick-action-icon bg-primary">
                                <i class="fas fa-plus text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">Buat Laporan Manual</h6>
                                <small class="text-muted">Tambah laporan atas nama warga</small>
                            </div>
                        </div>
                    </div>
                    <div class="quick-action-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="quick-action-icon bg-success">
                                <i class="fas fa-user-plus text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">Tambah Pengguna</h6>
                                <small class="text-muted">Daftarkan staff atau department head</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="quick-action-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="quick-action-icon bg-warning">
                                <i class="fas fa-download text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">Export Data</h6>
                                <small class="text-muted">Download laporan dalam format Excel/PDF</small>
                            </div>
                        </div>
                    </div>
                    <div class="quick-action-item mb-3">
                        <div class="d-flex align-items-center">
                            <div class="quick-action-icon bg-info">
                                <i class="fas fa-bell text-white"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1">Broadcast Notification</h6>
                                <small class="text-muted">Kirim pemberitahuan ke semua user</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="feature-actions">
                <a href="{{ route('admin.reports') }}" class="btn btn-primary">
                    <i class="fas fa-rocket me-2"></i>Mulai Sekarang
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

.alert-sm {
    font-size: 0.875rem;
}
</style>
@endsection
