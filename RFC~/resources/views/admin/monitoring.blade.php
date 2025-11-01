@extends('layouts.dashboard')

@section('title', 'System Monitoring')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-chart-line me-2"></i>System Monitoring
            </h1>
            <div class="text-muted">
                <i class="fas fa-calendar me-1"></i>
                {{ now()->format('d F Y, H:i') }}
            </div>
        </div>
    </div>
</div>

<!-- Real-time Statistics -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Laporan
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_reports'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pending
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_reports'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            In Progress
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['in_progress_reports'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-cogs fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Resolved
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['resolved_reports'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SLA Monitoring -->
<div class="row mb-4">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            SLA Breached
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $slaStats['sla_breached'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            SLA Due Soon
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $slaStats['sla_due_soon'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-hourglass-half fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total with SLA
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $slaStats['total_with_sla'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Department Performance -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Department Performance</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th class="text-center">Total Reports</th>
                                <th class="text-center">Resolved</th>
                                <th class="text-center">Resolution Rate</th>
                                <th class="text-center">Staff Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($departmentPerformance as $dept)
                            <tr>
                                <td><strong>{{ $dept['name'] }}</strong></td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $dept['total_reports'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $dept['resolved_reports'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $dept['resolution_rate'] >= 80 ? 'success' : ($dept['resolution_rate'] >= 60 ? 'warning' : 'danger') }}">
                                        {{ $dept['resolution_rate'] }}%
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $dept['staff_count'] }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
            </div>
            <div class="card-body">
                @if($recentActivity->count() > 0)
                    @foreach($recentActivity as $activity)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            @if($activity['type'] == 'report')
                                <i class="fas fa-file-alt text-primary"></i>
                            @else
                                <i class="fas fa-exclamation-triangle text-warning"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold">{{ $activity['title'] }}</div>
                            <div class="text-muted small">
                                {{ $activity['user'] }} • {{ $activity['department'] }} • {{ $activity['created_at']->diffForHumans() }}
                            </div>
                            <span class="badge bg-{{ $activity['status'] == 'pending' ? 'warning' : ($activity['status'] == 'resolved' ? 'success' : 'info') }}">
                                {{ ucfirst(str_replace('_', ' ', $activity['status'])) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted">No recent activity</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Monthly Trends (30 Days)</h6>
            </div>
            <div class="card-body">
                <canvas id="monthlyTrendsChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Monthly Trends Chart
const ctx = document.getElementById('monthlyTrendsChart').getContext('2d');
const monthlyTrendsChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($monthlyTrends['reports']->pluck('date')) !!},
        datasets: [{
            label: 'Reports',
            data: {!! json_encode($monthlyTrends['reports']->pluck('count')) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }, {
            label: 'Complaints',
            data: {!! json_encode($monthlyTrends['complaints']->pluck('count')) !!},
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endsection
