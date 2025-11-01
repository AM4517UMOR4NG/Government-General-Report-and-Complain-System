@extends('layouts.dashboard')

@section('title', 'Departemen Admin')

@section('content')
<style>
    .departments-header {
        background: linear-gradient(135deg, #003d6b 0%, #004a7f 50%, #00527a 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 61, 107, 0.5);
    }

    .departments-header h1 {
        margin: 0 0 0.5rem 0;
        font-size: 2rem;
        font-weight: 700;
    }

    .departments-header p {
        margin: 0;
        opacity: 0.95;
    }

    .departments-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #f0f0f0;
        overflow: hidden;
    }

    .departments-card-header {
        background: linear-gradient(135deg, #004a7f 0%, #00527a 100%);
        padding: 1.5rem;
        border-bottom: none;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: white;
    }

    .departments-card-header i {
        color: white;
        font-size: 1.3rem;
    }

    .departments-card-header h3 {
        margin: 0;
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .departments-card-body {
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

    .badge-active {
        background: #dcfce7;
        color: #16a34a;
    }

    .badge-inactive {
        background: #fee2e2;
        color: #dc2626;
    }

    .btn-action {
        padding: 0.35rem 0.5rem;
        font-size: 0.8rem;
        margin: 0.1rem;
    }
</style>

<!-- Header -->
<div class="departments-header">
    <h1>
        <i class="fas fa-sitemap me-2"></i>Manajemen Departemen
    </h1>
    <p>Kelola semua departemen dalam sistem</p>
    <div style="margin-top: 1rem; font-size: 0.9rem; opacity: 0.9;">
        <i class="fas fa-calendar me-1"></i>
        {{ now()->format('d F Y, H:i') }}
    </div>
</div>

<!-- Departments Table -->
<div class="departments-card">
    <div class="departments-card-header">
        <i class="fas fa-list"></i>
        <h3>Daftar Departemen ({{ $departments->count() }} Total)</h3>
    </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Kode</th>
                                <th>Email</th>
                                <th>Telepon</th>
                                <th>Status</th>
                                <th>Staff</th>
                                <th>Laporan</th>
                                <th>Keluhan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($departments as $department)
                            <tr>
                                <td>{{ $department->id }}</td>
                                <td>
                                    <strong>{{ $department->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($department->description, 50) }}</small>
                                </td>
                                <td>{{ $department->code }}</td>
                                <td>{{ $department->email }}</td>
                                <td>{{ $department->phone }}</td>
                                <td>
                                    <span class="badge bg-{{ $department->is_active ? 'success' : 'danger' }}">
                                        {{ $department->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $department->users_count }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $department->reports_count }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-warning">{{ $department->complaints_count }}</span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewDepartmentModal{{ $department->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editDepartmentModal{{ $department->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada departemen</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Department Modals -->
@foreach($departments as $department)
<div class="modal fade" id="viewDepartmentModal{{ $department->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Departemen #{{ $department->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Departemen</h6>
                        <p><strong>Nama:</strong> {{ $department->name }}</p>
                        <p><strong>Kode:</strong> {{ $department->code }}</p>
                        <p><strong>Email:</strong> {{ $department->email }}</p>
                        <p><strong>Telepon:</strong> {{ $department->phone }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Alamat & Status</h6>
                        <p><strong>Alamat:</strong> {{ $department->address }}</p>
                        <p><strong>Status:</strong> 
                            <span class="badge bg-{{ $department->is_active ? 'success' : 'danger' }}">
                                {{ $department->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Deskripsi</h6>
                        <p>{{ $department->description }}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <h6>Statistik</h6>
                        <p><strong>Staff:</strong> {{ $department->users_count }}</p>
                        <p><strong>Laporan:</strong> {{ $department->reports_count }}</p>
                        <p><strong>Keluhan:</strong> {{ $department->complaints_count }}</p>
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
