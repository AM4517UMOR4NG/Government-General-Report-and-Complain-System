@extends('layouts.dashboard')

@section('title', 'Detail Keluhan')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1" style="font-weight: 700; color: var(--text);">
                <i class="fas fa-exclamation-triangle me-2" style="color: var(--warning);"></i>Detail Keluhan
            </h2>
            <p class="text-muted mb-0">Informasi lengkap keluhan Anda</p>
        </div>
        <a href="{{ route('citizen.complaints.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <!-- Main Content -->
    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Complaint Info Card -->
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-info-circle" style="color: var(--warning);"></i>
                    <h5 class="mb-0" style="font-weight: 600;">Informasi Keluhan</h5>
                </div>
                <div class="card-body">
                    <!-- Ticket Number Badge -->
                    <div class="mb-3 pb-3" style="border-bottom: 1px solid var(--border);">
                        <span class="badge" style="background: var(--warning); color: #111827; font-size: 0.9rem; padding: 0.5rem 1rem;">
                            <i class="fas fa-ticket-alt me-2"></i>{{ $complaint->ticket_no }}
                        </span>
                    </div>
                    
                    <!-- Title & Description -->
                    <h4 class="mb-3" style="font-weight: 700; color: var(--text);">{{ $complaint->title }}</h4>
                    <p class="mb-4" style="color: var(--muted); line-height: 1.7;">{{ $complaint->description }}</p>
                    
                    <!-- Details Grid -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label"><i class="fas fa-tag me-2"></i>Kategori</label>
                                <div class="detail-value">{{ ucfirst($complaint->category) }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label"><i class="fas fa-flag me-2"></i>Prioritas</label>
                                <div class="detail-value">
                                    <span class="badge badge-priority-{{ $complaint->priority }}">
                                        {{ ucfirst($complaint->priority) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if($complaint->location)
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label"><i class="fas fa-map-marker-alt me-2"></i>Lokasi Kejadian</label>
                                <div class="detail-value">{{ $complaint->location }}</div>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label"><i class="fas fa-building me-2"></i>Departemen</label>
                                <div class="detail-value">{{ $complaint->department->name }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label"><i class="fas fa-clock me-2"></i>Diajukan</label>
                                <div class="detail-value">{{ $complaint->created_at->format('d F Y, H:i') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label"><i class="fas fa-sync me-2"></i>Terakhir Update</label>
                                <div class="detail-value">{{ $complaint->updated_at->format('d F Y, H:i') }}</div>
                            </div>
                        </div>
                        @if($complaint->assignedUser)
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label"><i class="fas fa-user-check me-2"></i>Ditugaskan ke</label>
                                <div class="detail-value">{{ $complaint->assignedUser->name }}</div>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6">
                            <div class="detail-item">
                                <label class="detail-label"><i class="fas fa-circle-notch me-2"></i>Status</label>
                                <div class="detail-value">
                                    <span class="badge badge-status-{{ $complaint->status }}">
                                        {{ ucfirst($complaint->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- Investigation Notes -->
            @if($complaint->investigation_notes)
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-search" style="color: var(--info);"></i>
                    <h5 class="mb-0" style="font-weight: 600;">Catatan Investigasi</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-0" style="border-left: 4px solid var(--info);">
                        <p class="mb-0">{{ $complaint->investigation_notes }}</p>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Resolution Notes -->
            @if($complaint->resolution_notes)
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-check-circle" style="color: var(--success);"></i>
                    <h5 class="mb-0" style="font-weight: 600;">Catatan Resolusi</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-success mb-0" style="border-left: 4px solid var(--success);">
                        <p class="mb-2">{{ $complaint->resolution_notes }}</p>
                        @if($complaint->resolved_at)
                        <small class="text-muted">
                            <i class="fas fa-calendar-check me-1"></i>
                            Diselesaikan pada: {{ $complaint->resolved_at->format('d F Y, H:i') }}
                        </small>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Attachments -->
            @if($complaint->attachments && is_array($complaint->attachments) && count($complaint->attachments) > 0)
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-paperclip" style="color: var(--primary);"></i>
                    <h5 class="mb-0" style="font-weight: 600;">Lampiran</h5>
                </div>
                <div class="card-body">
                    <div class="attachment-list">
                        @foreach($complaint->attachments as $file)
                        <div class="attachment-item">
                            <div class="attachment-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="attachment-info">
                                <div class="attachment-name">{{ basename($file) }}</div>
                                <small class="text-muted">File lampiran</small>
                            </div>
                            <a class="btn btn-sm btn-primary" href="{{ asset('storage/' . $file) }}" target="_blank" title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Timeline -->
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-history" style="color: var(--primary);"></i>
                    <h5 class="mb-0" style="font-weight: 600;">Status Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="modern-timeline">
                        <div class="timeline-step completed">
                            <div class="timeline-icon">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div class="timeline-details">
                                <h6>Keluhan Diajukan</h6>
                                <small>{{ $complaint->created_at->format('d F Y, H:i') }}</small>
                            </div>
                        </div>
                        
                        @if($complaint->status == 'investigating')
                        <div class="timeline-step completed">
                            <div class="timeline-icon">
                                <i class="fas fa-search"></i>
                            </div>
                            <div class="timeline-details">
                                <h6>Sedang Diselidiki</h6>
                                <small>{{ $complaint->updated_at->format('d F Y, H:i') }}</small>
                            </div>
                        </div>
                        @endif
                        
                        @if($complaint->status == 'resolved')
                        <div class="timeline-step completed">
                            <div class="timeline-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="timeline-details">
                                <h6>Keluhan Diselesaikan</h6>
                                <small>{{ $complaint->resolved_at ? $complaint->resolved_at->format('d F Y, H:i') : $complaint->updated_at->format('d F Y, H:i') }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Departemen</h6>
            </div>
            <div class="card-body">
                <h6>{{ $complaint->department->name }}</h6>
                <p class="text-muted">{{ $complaint->department->description }}</p>
                <p><strong>Kode:</strong> {{ $complaint->department->code }}</p>
                <p><strong>Email:</strong> {{ $complaint->department->email }}</p>
                <p><strong>Telepon:</strong> {{ $complaint->department->phone }}</p>
                @if($complaint->department->address)
                <p><strong>Alamat:</strong> {{ $complaint->department->address }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e3e6f0;
}

.timeline-content {
    padding-left: 20px;
}

.timeline-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 5px;
}

.timeline-text {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 0;
}
</style>
@endsection

