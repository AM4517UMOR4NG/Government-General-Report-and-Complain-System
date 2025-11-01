@extends('layouts.dashboard')

@section('title', 'Staff Administrasi')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-users me-2"></i>Staff Departemen
            </h1>
            <div class="text-muted">
                <i class="fas fa-calendar me-1"></i>
                {{ now()->format('d F Y, H:i') }}
            </div>
        </div>
    </div>
</div>

<!-- Staff Table -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Staff Departemen</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Tanggal Bergabung</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staff as $member)
                            <tr>
                                <td>{{ $member->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                        {{ $member->name }}
                                    </div>
                                </td>
                                <td>{{ $member->email }}</td>
                                <td>
                                    <span class="badge bg-{{ $member->role == 'department_head' ? 'warning' : 'info' }}">
                                        {{ ucfirst($member->role) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success">Aktif</span>
                                </td>
                                <td>{{ $member->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewStaffModal{{ $member->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada staff</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $staff->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Staff Modals -->
@foreach($staff as $member)
<div class="modal fade" id="viewStaffModal{{ $member->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Staff #{{ $member->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Pribadi</h6>
                        <p><strong>Nama:</strong> {{ $member->name }}</p>
                        <p><strong>Email:</strong> {{ $member->email }}</p>
                        <p><strong>Role:</strong> 
                            <span class="badge bg-{{ $member->role == 'department_head' ? 'warning' : 'info' }}">
                                {{ ucfirst($member->role) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Informasi Departemen</h6>
                        <p><strong>Departemen:</strong> {{ $member->department->name }}</p>
                        <p><strong>Kode:</strong> {{ $member->department->code }}</p>
                        <p><strong>Email Departemen:</strong> {{ $member->department->email }}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Informasi Akun</h6>
                        <p><strong>Tanggal Bergabung:</strong> {{ $member->created_at->format('d F Y, H:i') }}</p>
                        <p><strong>Terakhir Update:</strong> {{ $member->updated_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
