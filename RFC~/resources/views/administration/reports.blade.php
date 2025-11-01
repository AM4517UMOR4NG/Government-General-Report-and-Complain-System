@extends('layouts.dashboard')

@section('title', 'Laporan Masyarakat')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-file-alt me-2"></i>Laporan Masyarakat
            </h1>
            <div class="text-muted">
                <i class="fas fa-calendar me-1"></i>
                {{ now()->format('d F Y, H:i') }}
            </div>
        </div>
    </div>
</div>

<!-- Reports Table -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Laporan Masyarakat</h6>
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
                                <td>
                                    <span class="badge bg-{{ 
                                        in_array($report->status, ['submitted', 'pending']) ? 'warning' : 
                                        ($report->status == 'verified' ? 'primary' : 
                                        ($report->status == 'resolved' ? 'success' : 
                                        ($report->status == 'assigned' ? 'info' : 'secondary'))) 
                                    }}">
                                        {{ $report->status == 'verified' ? 'Dikonfirmasi' : ucfirst($report->status) }}
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
                                        <a href="{{ route('administration.reports.download', $report->id) }}" class="btn btn-sm btn-outline-success" title="Download">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                    
                                    {{-- Workflow Buttons --}}
                                    <x-workflow-buttons :report="$report" :user="auth()->user()" />
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada laporan</td>
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
                            <span class="badge bg-{{ 
                                in_array($report->status, ['submitted', 'pending']) ? 'warning' : 
                                ($report->status == 'verified' ? 'primary' : 
                                ($report->status == 'resolved' ? 'success' : 
                                ($report->status == 'assigned' ? 'info' : 'secondary'))) 
                            }}">
                                {{ $report->status == 'verified' ? 'Dikonfirmasi' : ucfirst($report->status) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Informasi Pengguna</h6>
                        <p><strong>Nama:</strong> {{ $report->user->name }}</p>
                        <p><strong>Email:</strong> {{ $report->user->email }}</p>
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

<!-- Assign Report Modal -->
<div class="modal fade" id="assignReportModal{{ $report->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tugaskan Laporan #{{ $report->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('administration.reports.assign', $report->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">Tugaskan ke:</label>
                        <select class="form-select" id="assigned_to" name="assigned_to" required>
                            <option value="">Pilih Staff</option>
                            @foreach($staffList as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Tugaskan</button>
                    <form action="{{ route('administration.reports.send_to_head', $report->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-warning" onclick="return confirm('Kirim langsung ke Kepala Departemen?')">
                            <i class="fas fa-arrow-up me-1"></i>Kirim ke Kepala
                        </button>
                    </form>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
