@extends('layouts.dashboard')

@section('title', 'Dashboard Masyarakat')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-home me-2"></i>Dashboard Masyarakat
            </h1>
            <div class="text-muted">
                <i class="fas fa-calendar me-1"></i>
                {{ now()->format('d F Y, H:i') }}
            </div>
        </div>
    </div>
</div>

<!-- Welcome Message -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-left-primary shadow">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="text-primary">Selamat datang, {{ auth()->user()->name }}!</h4>
                        <p class="mb-0">Gunakan sistem ini untuk melaporkan masalah atau mengajukan keluhan kepada pemerintah. 
                        Laporan dan keluhan Anda akan diproses oleh departemen yang sesuai.</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <i class="fas fa-handshake fa-3x text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card shadow h-100">
            <div class="card-body text-center">
                <i class="fas fa-file-alt fa-3x text-primary mb-3"></i>
                <h5 class="card-title">Buat Laporan</h5>
                <p class="card-text">Laporkan masalah atau kondisi yang perlu diperbaiki</p>
                <a href="{{ route('citizen.reports.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Buat Laporan
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow h-100">
            <div class="card-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <h5 class="card-title">Ajukan Keluhan</h5>
                <p class="card-text">Sampaikan keluhan atau ketidakpuasan terhadap pelayanan</p>
                <a href="{{ route('citizen.complaints.create') }}" class="btn btn-warning">
                    <i class="fas fa-plus me-1"></i>Ajukan Keluhan
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Laporan Saya
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['my_reports'] }}</div>
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
                            Laporan Pending
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
                            Sedang Diproses
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['in_progress_reports'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-spinner fa-2x text-gray-300"></i>
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
                            Laporan Selesai
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

<!-- Complaints Statistics -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Keluhan Saya
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['my_complaints'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
                            Keluhan Pending
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_complaints'] }}</div>
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
                            Sedang Diselidiki
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['investigating_complaints'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-search fa-2x text-gray-300"></i>
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
                            Keluhan Selesai
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['resolved_complaints'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Laporan Terbaru</h6>
                <a href="{{ route('citizen.reports.index') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-eye me-1"></i>Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($myReports->count() > 0)
                    @foreach($myReports as $report)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-file-alt text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold">{{ $report->title }}</div>
                            <div class="text-muted small">
                                {{ $report->department->name }} • {{ $report->created_at->diffForHumans() }}
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-{{ $report->status == 'pending' ? 'warning' : ($report->status == 'resolved' ? 'success' : 'info') }} me-2">
                                    {{ ucfirst($report->status) }}
                                </span>
                                @if($report->assignedUser)
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>Ditugaskan ke: {{ $report->assignedUser->name }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted">Anda belum membuat laporan</p>
                    <a href="{{ route('citizen.reports.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i>Buat Laporan Pertama
                    </a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Keluhan Terbaru</h6>
                <a href="{{ route('citizen.complaints.index') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-eye me-1"></i>Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($myComplaints->count() > 0)
                    @foreach($myComplaints as $complaint)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold">{{ $complaint->title }}</div>
                            <div class="text-muted small">
                                {{ $complaint->department->name }} • {{ $complaint->created_at->diffForHumans() }}
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-{{ $complaint->status == 'pending' ? 'warning' : ($complaint->status == 'resolved' ? 'success' : 'info') }} me-2">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                                @if($complaint->assignedUser)
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>Ditugaskan ke: {{ $complaint->assignedUser->name }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted">Anda belum mengajukan keluhan</p>
                    <a href="{{ route('citizen.complaints.create') }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-plus me-1"></i>Ajukan Keluhan Pertama
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Available Departments -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Departemen Tersedia</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($departments->count() > 0)
                        @foreach($departments as $department)
                        <div class="col-md-4 mb-3">
                            <div class="card border-left-info">
                                <div class="card-body">
                                    <h6 class="card-title">{{ $department->name }}</h6>
                                    <p class="card-text small text-muted">{{ $department->description }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">{{ $department->code }}</small>
                                        <small class="text-muted">{{ $department->email }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <p class="text-muted">Tidak ada departemen yang tersedia</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

