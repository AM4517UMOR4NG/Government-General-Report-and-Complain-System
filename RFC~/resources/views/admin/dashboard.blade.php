@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@section('content')
<style>
    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 50%, #0369a1 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(14, 165, 233, 0.2);
    }

    .dashboard-header-title {
        font-size: 1.8rem;
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
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .quick-stat-box {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        text-align: center;
        transition: all 0.3s ease;
    }

    .quick-stat-box:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-2px);
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
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        padding: 1.25rem;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .dashboard-card-header i {
        color: #0284c7;
        font-size: 1.2rem;
    }

    .dashboard-card-header h3 {
        margin: 0;
        color: #0369a1;
        font-weight: 600;
        font-size: 1rem;
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

    .table-compact {
        margin: 0;
        font-size: 0.9rem;
    }

    .table-compact thead {
        background: #f8f9fa;
    }

    .table-compact th {
        border-bottom: 2px solid #e9ecef;
        padding: 0.75rem;
        font-weight: 600;
        color: #0369a1;
    }

    .table-compact td {
        padding: 0.75rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .badge-custom {
        padding: 0.35rem 0.75rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .badge-blue {
        background: #dbeafe;
        color: #0284c7;
    }

    .badge-green {
        background: #dcfce7;
        color: #16a34a;
    }

    .badge-amber {
        background: #fef3c7;
        color: #d97706;
    }

    @media (max-width: 768px) {
        .dashboard-header {
            padding: 1.5rem;
        }

        .dashboard-header-title {
            font-size: 1.3rem;
        }

        .quick-stats {
            grid-template-columns: repeat(3, 1fr);
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
            <i class="fas fa-shield-alt me-2"></i>Dashboard Admin
        </div>
        <div class="dashboard-header-desc">
            Kelola sistem laporan dan keluhan pemerintah secara terpusat
        </div>
        <div class="dashboard-header-time">
            <i class="fas fa-clock me-1"></i>{{ now()->format('d F Y, H:i') }}
        </div>
        <div class="quick-stats">
            <div class="quick-stat-box">
                <div class="quick-stat-icon"><i class="fas fa-users"></i></div>
                <div class="quick-stat-number">{{ $stats['total_users'] }}</div>
                <div class="quick-stat-label">Total Pengguna</div>
            </div>
            <div class="quick-stat-box">
                <div class="quick-stat-icon"><i class="fas fa-file-alt"></i></div>
                <div class="quick-stat-number">{{ $stats['total_reports'] }}</div>
                <div class="quick-stat-label">Total Laporan</div>
            </div>
            <div class="quick-stat-box">
                <div class="quick-stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="quick-stat-number">{{ $stats['total_complaints'] }}</div>
                <div class="quick-stat-label">Total Keluhan</div>
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
                        <div class="stat-number">{{ $stats['pending_reports'] }}</div>
                        <div class="stat-label"><i class="fas fa-hourglass-half me-1"></i>Laporan Pending</div>
                    </div>
                    <div class="stat-item resolved">
                        <div class="stat-number">{{ $stats['resolved_reports'] }}</div>
                        <div class="stat-label"><i class="fas fa-check-circle me-1"></i>Laporan Selesai</div>
                    </div>
                    <div class="stat-item warning">
                        <div class="stat-number">{{ $stats['pending_complaints'] }}</div>
                        <div class="stat-label"><i class="fas fa-exclamation me-1"></i>Keluhan Pending</div>
                    </div>
                    <div class="stat-item danger">
                        <div class="stat-number">{{ $stats['resolved_complaints'] }}</div>
                        <div class="stat-label"><i class="fas fa-check me-1"></i>Keluhan Selesai</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Departemen -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <i class="fas fa-sitemap"></i>
                <h3>Departemen</h3>
            </div>
            <div class="dashboard-card-body">
                <div class="table-responsive">
                    <table class="table table-compact">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th class="text-end">Laporan</th>
                                <th class="text-end">Staff</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departmentStats->take(5) as $dept)
                            <tr>
                                <td>
                                    <strong>{{ $dept->name }}</strong>
                                    <div style="font-size: 0.8rem; color: #999;">{{ $dept->code }}</div>
                                </td>
                                <td class="text-end"><span class="badge-custom badge-blue">{{ $dept->reports_count }}</span></td>
                                <td class="text-end"><span class="badge-custom badge-green">{{ $dept->users_count }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Statistik Bulanan -->
        <div class="dashboard-card">
            <div class="dashboard-card-header">
                <i class="fas fa-chart-area"></i>
                <h3>Statistik Bulanan</h3>
            </div>
            <div class="dashboard-card-body">
                <canvas id="monthlyChart" height="160"></canvas>
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
                    @foreach($recentReports->take(4) as $report)
                    <div style="padding: 0.75rem 0; border-bottom: 1px solid #f0f0f0; display: flex; gap: 0.75rem; align-items: flex-start;">
                        <div style="width: 6px; height: 6px; background: #0284c7; border-radius: 50%; margin-top: 0.5rem; flex-shrink: 0;"></div>
                        <div style="flex: 1; min-width: 0;">
                            <div style="font-weight: 600; color: #2c3e50; font-size: 0.9rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $report->title }}</div>
                            <div style="font-size: 0.8rem; color: #999;">{{ $report->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Mini sparkline chart for hero
const miniCtx = document.getElementById('miniChart')?.getContext('2d');
if(miniCtx) {
    new Chart(miniCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyStats['reports']->pluck('date')) !!},
            datasets: [{
                data: {!! json_encode($monthlyStats['reports']->pluck('count')) !!},
                borderColor: getComputedStyle(document.documentElement).getPropertyValue('--primary') || '#2563eb',
                backgroundColor: 'rgba(37,99,235,0.08)',
                tension: 0.3,
                fill: true,
                pointRadius: 0
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { display: false }, y: { display: false } } }
    });
}

// Monthly Statistics Chart (bigger)
const ctx = document.getElementById('monthlyChart')?.getContext('2d');
if(ctx) {
    const monthlyChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyStats['reports']->pluck('date')) !!},
            datasets: [{
                label: 'Laporan',
                data: {!! json_encode($monthlyStats['reports']->pluck('count')) !!},
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37,99,235,0.12)',
                tension: 0.2,
                fill: true
            }, {
                label: 'Keluhan',
                data: {!! json_encode($monthlyStats['complaints']->pluck('count')) !!},
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239,68,68,0.08)',
                tension: 0.2,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: { legend: { labels: { usePointStyle: true } } }
        }
    });
}
</script>
@endsection

