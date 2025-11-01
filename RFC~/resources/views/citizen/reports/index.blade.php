@extends('layouts.dashboard')

@section('title', 'Laporan Saya')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="mb-1" style="font-weight: 700; font-size: 1.875rem; color: var(--text);">Laporan Saya</h2>
            <p class="text-muted mb-0">Lihat dan kelola semua laporan yang telah Anda buat.</p>
        </div>
        <a href="{{ route('citizen.reports.create') }}" class="btn btn-primary d-flex align-items-center gap-2" style="box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
            <i class="fas fa-plus"></i>
            <span>Buat Laporan</span>
        </a>
    </div>

    @if($reports->count() > 0)
        <!-- Table Header (Desktop) -->
        <div class="d-none d-md-grid report-grid-header mb-3 px-3">
            <div style="grid-column: span 3;">JUDUL</div>
            <div style="grid-column: span 2;">KATEGORI</div>
            <div style="grid-column: span 2;">DEPARTEMEN</div>
            <div style="grid-column: span 1; text-align: center;">STATUS</div>
            <div style="grid-column: span 2; text-align: center;">PRIORITAS</div>
            <div style="grid-column: span 1; text-align: center;">ANTRIAN</div>
            <div style="grid-column: span 1; text-align: right;">AKSI</div>
        </div>

        <!-- Report Items -->
        <div class="report-list">
            @foreach($reports as $report)
            <div class="report-item">
                <div class="report-grid">
                    <!-- Judul -->
                    <div class="report-title-col">
                        <p class="report-title">{{ $report->title }}</p>
                        <p class="report-desc">{{ Str::limit($report->description, 60) }}</p>
                    </div>
                    
                    <!-- Kategori -->
                    <div class="report-category-col">
                        <p class="report-text">{{ ucfirst($report->category) }}</p>
                    </div>
                    
                    <!-- Departemen -->
                    <div class="report-dept-col">
                        <p class="report-text">{{ $report->department->name }}</p>
                    </div>
                    
                    <!-- Status -->
                    <div class="report-status-col">
                        <span class="status-badge status-{{ $report->status }}">
                            {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                        </span>
                    </div>
                    
                    <!-- Prioritas -->
                    <div class="report-priority-col">
                        <span class="priority-badge priority-{{ $report->priority }}">
                            <i class="fas fa-{{ $report->priority == 'high' ? 'arrow-up' : ($report->priority == 'low' ? 'arrow-down' : 'equals') }} me-1" style="font-size: 0.75rem;"></i>
                            {{ ucfirst($report->priority) }}
                        </span>
                    </div>
                    
                    <!-- Antrian -->
                    <div class="report-date-col">
                        @if($report->queue_no)
                            <span class="badge bg-primary">{{ $report->queue_no }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </div>
                    
                    <!-- Aksi -->
                    <div class="report-action-col">
                        <a href="{{ route('citizen.reports.show', $report->id) }}" class="action-btn" title="Lihat Detail">
                            <i class="fas fa-ellipsis-h"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($reports->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $reports->links() }}
        </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-file-alt"></i>
            </div>
            <h5 class="empty-title">Belum Ada Laporan</h5>
            <p class="empty-text">Anda belum membuat laporan apapun.</p>
            <a href="{{ route('citizen.reports.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                <i class="fas fa-plus"></i>
                <span>Buat Laporan Pertama</span>
            </a>
        </div>
    @endif
</div>

<style>
/* Grid Layout */
.report-grid-header {
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    gap: 1rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--muted);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.report-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.report-item {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 0.5rem;
    transition: all 0.2s ease;
}

.report-item:hover {
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1), 0 2px 4px -1px rgba(0,0,0,0.06);
}

.report-grid {
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    gap: 1rem;
    align-items: center;
    padding: 1rem;
.report-title-col { grid-column: span 12; }
.report-category-col { grid-column: span 6; }
.report-dept-col { grid-column: span 6; }
.report-status-col { grid-column: span 4; text-align: center; }
.report-priority-col { grid-column: span 4; text-align: center; }
.report-queue-col { grid-column: span 4; text-align: center; }
.report-action-col { grid-column: span 12; text-align: right; }

@media (min-width: 768px) {
    .report-title-col { grid-column: span 3; }
    .report-category-col { grid-column: span 2; }
    .report-status-col { grid-column: span 1; }
    .report-priority-col { grid-column: span 2; }
    .report-date-col { grid-column: span 1; }
    .report-action-col { grid-column: span 1; }
}

.report-title {
    font-weight: 700;
    color: var(--text);
    margin-bottom: 0.25rem;
    font-size: 0.95rem;
}

.report-desc {
    font-size: 0.875rem;
    color: var(--muted);
    margin: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.report-text {
    font-size: 0.875rem;
    color: var(--text);
    margin: 0;
}

/* Status Badges */
.status-badge {
    display: inline-block;
    padding: 0.25rem 0.625rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 9999px;
}

.status-submitted, .status-pending {
    background: #dcfce7;
    color: #166534;
}

.status-verified, .status-in_progress, .status-assigned {
    background: #e0f2fe;
    color: #075985;
}

.status-resolved, .status-closed {
    background: #d1fae5;
    color: #065f46;
}

/* Priority Badges */
.priority-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.625rem;
    font-size: 0.75rem;
    font-weight: 500;
    border-radius: 9999px;
}

.priority-medium {
    background: #fef3c7;
    color: #92400e;
}

.priority-high {
    background: #fee2e2;
    color: #991b1b;
}

.priority-low {
    background: #dbeafe;
    color: #1e40af;
}

.priority-urgent {
    background: #fecaca;
    color: #7f1d1d;
}

/* Action Button */
.action-btn {
    padding: 0.5rem;
    color: var(--muted);
    border-radius: 0.375rem;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.action-btn:hover {
    background: rgba(37, 99, 235, 0.08);
    color: var(--text);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 5rem 2rem;
}

.empty-icon {
    width: 4rem;
    height: 4rem;
    margin: 0 auto 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(37, 99, 235, 0.08);
    border-radius: 9999px;
    color: var(--primary);
    font-size: 2rem;
}

.empty-title {
    font-weight: 600;
    color: var(--text);
    margin-bottom: 0.5rem;
}

.empty-text {
    color: var(--muted);
    margin-bottom: 1.5rem;
}
</style>
@endsection

