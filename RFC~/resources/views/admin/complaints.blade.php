@extends('layouts.dashboard')

@section('title', 'Keluhan Admin')

@section('content')
<style>
    .complaints-header {
        background: linear-gradient(135deg, #003d6b 0%, #004a7f 50%, #00527a 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 61, 107, 0.5);
    }

    .complaints-header h1 {
        margin: 0 0 0.5rem 0;
        font-size: 2rem;
        font-weight: 700;
    }

    .complaints-header p {
        margin: 0;
        opacity: 0.95;
    }

    .complaints-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #f0f0f0;
        overflow: hidden;
    }

    .complaints-card-header {
        background: linear-gradient(135deg, #004a7f 0%, #00527a 100%);
        padding: 1.5rem;
        border-bottom: none;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: white;
    }

    .complaints-card-header i {
        color: white;
        font-size: 1.3rem;
    }

    .complaints-card-header h3 {
        margin: 0;
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .complaints-card-body {
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
<div class="complaints-header">
    <h1>
        <i class="fas fa-exclamation-triangle me-2"></i>Manajemen Keluhan
    </h1>
    <p>Kelola semua keluhan dari masyarakat</p>
    <div style="margin-top: 1rem; font-size: 0.9rem; opacity: 0.9;">
        <i class="fas fa-calendar me-1"></i>
        {{ now()->format('d F Y, H:i') }}
    </div>
</div>

<!-- Complaints Table -->
<div class="complaints-card">
    <div class="complaints-card-header">
        <i class="fas fa-list"></i>
        <h3>Daftar Keluhan ({{ $complaints->total() }} Total)</h3>
    </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
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
                            @forelse($complaints as $complaint)
                            <tr>
                                <td>{{ $complaint->id }}</td>
                                <td>
                                    <strong>{{ $complaint->title }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($complaint->description, 50) }}</small>
                                </td>
                                <td>{{ $complaint->user->name }}</td>
                                <td>{{ $complaint->department->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $complaint->status == 'pending' ? 'warning' : ($complaint->status == 'resolved' ? 'success' : 'info') }}">
                                        {{ ucfirst($complaint->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $complaint->priority == 'urgent' ? 'danger' : ($complaint->priority == 'high' ? 'warning' : ($complaint->priority == 'medium' ? 'info' : 'secondary')) }}">
                                        {{ ucfirst($complaint->priority) }}
                                    </span>
                                </td>
                                <td>{{ $complaint->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewComplaintModal{{ $complaint->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($complaint->status == 'pending')
                                        <form action="{{ route('admin.complaints.confirm', $complaint->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Konfirmasi">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#assignComplaintModal{{ $complaint->id }}" title="Assign ke Staff">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
                                        @endif
                                        <a href="{{ route('admin.complaints.edit', $complaint->id) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.complaints.delete', $complaint->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus keluhan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada keluhan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $complaints->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Complaint Modals -->
@foreach($complaints as $complaint)
<div class="modal fade" id="viewComplaintModal{{ $complaint->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Keluhan #{{ $complaint->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Keluhan</h6>
                        <p><strong>Judul:</strong> {{ $complaint->title }}</p>
                        <p><strong>Kategori:</strong> {{ $complaint->category }}</p>
                        <p><strong>Prioritas:</strong> 
                            <span class="badge bg-{{ $complaint->priority == 'urgent' ? 'danger' : ($complaint->priority == 'high' ? 'warning' : ($complaint->priority == 'medium' ? 'info' : 'secondary')) }}">
                                {{ ucfirst($complaint->priority) }}
                            </span>
                        </p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-{{ $complaint->status == 'pending' ? 'warning' : ($complaint->status == 'resolved' ? 'success' : 'info') }}">
                                {{ ucfirst($complaint->status) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Informasi Pengguna</h6>
                        <p><strong>Nama:</strong> {{ $complaint->user->name }}</p>
                        <p><strong>Email:</strong> {{ $complaint->user->email }}</p>
                        <p><strong>Departemen:</strong> {{ $complaint->department->name }}</p>
                        <p><strong>Lokasi:</strong> {{ $complaint->location ?? 'Tidak disebutkan' }}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Deskripsi</h6>
                        <p>{{ $complaint->description }}</p>
                    </div>
                </div>
                @if($complaint->assignedUser)
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Ditugaskan ke:</h6>
                        <p>{{ $complaint->assignedUser->name }} ({{ $complaint->assignedUser->email }})</p>
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

<!-- Assign Complaint Modal -->
@foreach($complaints as $complaint)
<div class="modal fade" id="assignComplaintModal{{ $complaint->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tugaskan Keluhan #{{ $complaint->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.complaints.assign', $complaint->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="assigned_to_{{ $complaint->id }}" class="form-label">Pilih Staff</label>
                        <select class="form-select" id="assigned_to_{{ $complaint->id }}" name="assigned_to" required>
                            <option value="">-- Pilih Staff --</option>
                            @foreach(\App\Models\User::where('role', 'staff')->get() as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->email }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Tugaskan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection
