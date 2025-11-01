@extends('layouts.dashboard')

@section('title', 'Keluhan Administrasi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-exclamation-triangle me-2"></i>Keluhan Departemen
            </h1>
            <div class="text-muted">
                <i class="fas fa-calendar me-1"></i>
                {{ now()->format('d F Y, H:i') }}
            </div>
        </div>
    </div>
</div>

<!-- Complaints Table -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Keluhan Departemen</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Judul</th>
                                <th>Pengguna</th>
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
                                        <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#assignComplaintModal{{ $complaint->id }}">
                                            <i class="fas fa-user-plus"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada keluhan</td>
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

<!-- Assign Complaint Modal -->
<div class="modal fade" id="assignComplaintModal{{ $complaint->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tugaskan Keluhan #{{ $complaint->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('administration.complaints.assign', $complaint->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="assigned_to" class="form-label">Tugaskan ke:</label>
                        <select class="form-select" id="assigned_to" name="assigned_to" required>
                            <option value="">Pilih Staff</option>
                            <!-- Staff options will be populated here -->
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
