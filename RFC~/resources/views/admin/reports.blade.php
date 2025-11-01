@extends('layouts.dashboard')

@section('title', 'Laporan Admin')

@section('content')
<style>
    .reports-header {
        background: linear-gradient(135deg, #003d6b 0%, #004a7f 50%, #00527a 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 61, 107, 0.5);
    }

    .reports-header h1 {
        margin: 0 0 0.5rem 0;
        font-size: 2rem;
        font-weight: 700;
    }

    .reports-header p {
        margin: 0;
        opacity: 0.95;
    }

    .reports-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #f0f0f0;
        overflow: hidden;
    }

    .reports-card-header {
        background: linear-gradient(135deg, #004a7f 0%, #00527a 100%);
        padding: 1.5rem;
        border-bottom: none;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: white;
    }

    .reports-card-header i {
        color: white;
        font-size: 1.3rem;
    }

    .reports-card-header h3 {
        margin: 0;
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .reports-card-body {
        padding: 1.5rem;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table {
        margin: 0;
    }

    .table thead th {
        background: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        padding: 1rem;
        font-weight: 600;
        color: #004a7f;
        font-size: 0.9rem;
    }

    .table tbody td {
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: #f8f9fa;
    }

    .badge-status {
        padding: 0.35rem 0.75rem;
        border-radius: 12px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .badge-pending {
        background: #fef3c7;
        color: #d97706;
    }

    .badge-resolved {
        background: #dcfce7;
        color: #16a34a;
    }

    .badge-urgent {
        background: #fecaca;
        color: #dc2626;
    }

    .badge-high {
        background: #fef3c7;
        color: #d97706;
    }

    .badge-medium {
        background: #e0e7ff;
        color: #4f46e5;
    }

    .btn-action {
        padding: 0.35rem 0.5rem;
        font-size: 0.8rem;
        margin: 0.1rem;
    }
</style>

<!-- Header -->
<div class="reports-header">
    <h1>
        <i class="fas fa-file-alt me-2"></i>Manajemen Laporan
    </h1>
    <p>Kelola semua laporan dari masyarakat</p>
    <div style="margin-top: 1rem; font-size: 0.9rem; opacity: 0.9;">
        <i class="fas fa-calendar me-1"></i>
        {{ now()->format('d F Y, H:i') }}
    </div>
</div>

<!-- Reports Table -->
<div class="reports-card">
    <div class="reports-card-header">
        <i class="fas fa-list"></i>
        <h3>Daftar Laporan ({{ $reports->total() }} Total)</h3>
    </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>No. Tiket</th>
                                <th>Judul</th>
                                <th>Pengguna</th>
                                <th>Departemen</th>
                                <th>Status</th>
                                <th>Prioritas</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reports as $report)
                            <tr>
                                <td>{{ $report->id }}</td>
                                <td>
                                    <code>{{ $report->ticket_no }}</code>
                                </td>
                                <td>
                                    <strong>{{ $report->title }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($report->description, 50) }}</small>
                                </td>
                                <td>{{ $report->user->name }}</td>
                                <td>{{ $report->department->name }}</td>
                                <td>
                                    <span class="badge bg-{{ in_array($report->status, ['submitted', 'pending']) ? 'warning' : ($report->status == 'resolved' ? 'success' : 'info') }}">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $report->priority == 'urgent' ? 'danger' : ($report->priority == 'high' ? 'warning' : ($report->priority == 'medium' ? 'info' : 'secondary')) }}">
                                        {{ ucfirst($report->priority) }}
                                    </span>
                                </td>
                                <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewReportModal{{ $report->id }}" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($report->attachments && count($report->attachments) > 0)
                                        <a href="{{ route('files.view', ['report', $report->id]) }}" class="btn btn-sm btn-outline-info" title="Lihat File">
                                            <i class="fas fa-paperclip"></i>
                                        </a>
                                        @endif
                                        <a href="{{ route('admin.reports.download', $report->id) }}" class="btn btn-sm btn-outline-success" title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <a href="{{ route('admin.reports.edit', $report->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.reports.delete', $report->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    
                                    {{-- Workflow Buttons --}}
                                    <x-workflow-buttons :report="$report" :user="auth()->user()" :staffList="$staffList" />
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada laporan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $reports->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Report Modals -->
@foreach($reports as $report)
<div class="modal fade" id="viewReportModal{{ $report->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Laporan #{{ $report->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Laporan</h6>
                        <p><strong>Judul:</strong> {{ $report->title }}</p>
                        <p><strong>Kategori:</strong> {{ $report->category }}</p>
                        <p><strong>Prioritas:</strong> 
                            <span class="badge bg-{{ $report->priority == 'urgent' ? 'danger' : ($report->priority == 'high' ? 'warning' : ($report->priority == 'medium' ? 'info' : 'secondary')) }}">
                                {{ ucfirst($report->priority) }}
                            </span>
                        </p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-{{ $report->status == 'pending' ? 'warning' : ($report->status == 'resolved' ? 'success' : 'info') }}">
                                {{ ucfirst($report->status) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Informasi Pengguna</h6>
                        <p><strong>Nama:</strong> {{ $report->user->name }}</p>
                        <p><strong>Email:</strong> {{ $report->user->email }}</p>
                        <p><strong>Departemen:</strong> {{ $report->department->name }}</p>
                        <p><strong>Lokasi:</strong> {{ $report->location ?? 'Tidak disebutkan' }}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Deskripsi</h6>
                        <p>{{ $report->description }}</p>
                    </div>
                </div>
                @if($report->assignedUser)
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Ditugaskan ke:</h6>
                        <p>{{ $report->assignedUser->name }} ({{ $report->assignedUser->email }})</p>
                    </div>
                </div>
                @endif
                @if($report->attachments && count($report->attachments) > 0)
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Lampiran ({{ count($report->attachments) }} file):</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($report->attachments as $attachment)
                            <span class="badge bg-light text-dark border">
                                <i class="fas fa-paperclip me-1"></i>
                                {{ basename($attachment) }}
                            </span>
                            @endforeach
                        </div>
                        <div class="mt-2">
                            <a href="{{ route('files.view', ['report', $report->id]) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>Lihat Semua File
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
