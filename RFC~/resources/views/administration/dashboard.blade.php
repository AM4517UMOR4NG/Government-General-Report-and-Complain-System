@extends('layouts.dashboard')

@section('title', 'Dashboard Administrasi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-building me-2"></i>Dashboard {{ $department->name }}
            </h1>
            <div class="text-muted">
                <i class="fas fa-calendar me-1"></i>
                {{ now()->format('d F Y, H:i') }}
            </div>
        </div>
    </div>
</div>

<!-- Department Info -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Departemen</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>{{ $department->name }}</h5>
                        <p class="text-muted">{{ $department->description }}</p>
                        <p><strong>Kode:</strong> {{ $department->code }}</p>
                        <p><strong>Email:</strong> {{ $department->email }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Telepon:</strong> {{ $department->phone }}</p>
                        <p><strong>Alamat:</strong> {{ $department->address }}</p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-{{ $department->is_active ? 'success' : 'danger' }}">
                                {{ $department->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </p>
                    </div>
                </div>
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
                            Selesai
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
                            Total Keluhan
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_complaints'] }}</div>
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
                <h6 class="m-0 font-weight-bold text-primary">Laporan Departemen</h6>
                <a href="{{ route('administration.reports') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-eye me-1"></i>Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($departmentReports->count() > 0)
                    @foreach($departmentReports as $report)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-file-alt text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold">{{ $report->title }}</div>
                            <div class="text-muted small">
                                {{ $report->user->name }} • {{ $report->created_at->diffForHumans() }}
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-{{ $report->status == 'pending' ? 'warning' : ($report->status == 'resolved' ? 'success' : 'info') }} me-2">
                                    {{ ucfirst($report->status) }}
                                </span>
                                @if($report->assignedUser)
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>{{ $report->assignedUser->name }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted">Tidak ada laporan untuk departemen ini</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Keluhan Departemen</h6>
                <a href="{{ route('administration.complaints') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-eye me-1"></i>Lihat Semua
                </a>
            </div>
            <div class="card-body">
                @if($departmentComplaints->count() > 0)
                    @foreach($departmentComplaints as $complaint)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-warning"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold">{{ $complaint->title }}</div>
                            <div class="text-muted small">
                                {{ $complaint->user->name }} • {{ $complaint->created_at->diffForHumans() }}
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-{{ $complaint->status == 'pending' ? 'warning' : ($complaint->status == 'resolved' ? 'success' : 'info') }} me-2">
                                    {{ ucfirst($complaint->status) }}
                                </span>
                                @if($complaint->assignedUser)
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>{{ $complaint->assignedUser->name }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted">Tidak ada keluhan untuk departemen ini</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Department Staff -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Staff Departemen</h6>
                <a href="{{ route('administration.staff') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-eye me-1"></i>Lihat Semua
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($departmentStaff->count() > 0)
                        @foreach($departmentStaff as $staff)
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="fw-bold">{{ $staff->name }}</div>
                                    <div class="text-muted small">{{ ucfirst($staff->role) }}</div>
                                    <div class="text-muted small">{{ $staff->email }}</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="col-12">
                            <p class="text-muted">Tidak ada staff di departemen ini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

