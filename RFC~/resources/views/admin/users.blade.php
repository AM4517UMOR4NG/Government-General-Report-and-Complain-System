@extends('layouts.dashboard')

@section('title', 'Pengguna Admin')

@section('content')
<style>
    .users-header {
        background: linear-gradient(135deg, #003d6b 0%, #004a7f 50%, #00527a 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 32px rgba(0, 61, 107, 0.5);
    }

    .users-header h1 {
        margin: 0 0 0.5rem 0;
        font-size: 2rem;
        font-weight: 700;
    }

    .users-header p {
        margin: 0;
        opacity: 0.95;
    }

    .users-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #f0f0f0;
        overflow: hidden;
    }

    .users-card-header {
        background: linear-gradient(135deg, #004a7f 0%, #00527a 100%);
        padding: 1.5rem;
        border-bottom: none;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: white;
    }

    .users-card-header i {
        color: white;
        font-size: 1.3rem;
    }

    .users-card-header h3 {
        margin: 0;
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .users-card-body {
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

    .pagination {
        justify-content: center;
        margin-top: 1.5rem;
        gap: 0.5rem;
    }

    .pagination .page-link {
        color: #0284c7;
        border-color: #e9ecef;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        min-width: auto;
    }

    .pagination .page-link:hover {
        color: #0369a1;
        background: #f0f9ff;
        border-color: #0284c7;
    }

    .pagination .page-item.active .page-link {
        background: #0284c7;
        border-color: #0284c7;
        color: white;
    }

    /* Kecilkan SVG arrow di pagination - SANGAT KECIL */
    .pagination svg {
        width: 0.25rem !important;
        height: 0.25rem !important;
        display: inline-block !important;
        transform: scale(0.3) !important;
    }

    .pagination .page-link svg {
        width: 0.25rem !important;
        height: 0.25rem !important;
        transform: scale(0.3) !important;
    }

    .pagination .page-item svg {
        width: 0.25rem !important;
        height: 0.25rem !important;
        max-width: 0.25rem !important;
        max-height: 0.25rem !important;
        transform: scale(0.3) !important;
    }

    /* Styling untuk previous dan next */
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        font-size: 0.7rem;
        padding: 0.35rem 0.5rem;
        line-height: 1;
    }

    /* Kecilkan text di pagination */
    .pagination .page-link {
        line-height: 1.2;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }

    .empty-state i {
        font-size: 3rem;
        color: #ddd;
        margin-bottom: 1rem;
        display: block;
    }

    .empty-state p {
        color: #999;
        font-size: 1.1rem;
    }
</style>

<!-- Error Messages -->
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Error!</strong>
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Header -->
<div class="users-header">
    <h1>
        <i class="fas fa-users me-2"></i>Manajemen Pengguna
    </h1>
    <p>Kelola semua pengguna sistem FRC</p>
    <div style="margin-top: 1rem; font-size: 0.9rem; opacity: 0.9;">
        <i class="fas fa-calendar me-1"></i>
        {{ now()->format('d F Y, H:i') }}
    </div>
</div>

<!-- Users Table -->
<div class="users-card">
    <div class="users-card-header">
        <i class="fas fa-list"></i>
        <h3>Daftar Pengguna ({{ $users->total() }} Total)</h3>
    </div>
    <div class="users-card-body">
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">ID</th>
                            <th style="width: 20%;">Nama</th>
                            <th style="width: 20%;">Email</th>
                            <th style="width: 12%;">Role</th>
                            <th style="width: 18%;">Departemen</th>
                            <th style="width: 10%;">Status</th>
                            <th style="width: 15%;">Tanggal Daftar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <span style="background: #f0f9ff; color: #0284c7; padding: 0.25rem 0.75rem; border-radius: 12px; font-weight: 600; font-size: 0.85rem;">
                                    #{{ $user->id }}
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 36px; height: 36px; background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.9rem;">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: #2c3e50;">{{ $user->name }}</div>
                                        <div style="font-size: 0.8rem; color: #999;">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span style="color: #666; font-size: 0.9rem;">{{ $user->email }}</span>
                            </td>
                            <td>
                                @php
                                    $roleColors = [
                                        'admin' => ['bg' => '#fef2f2', 'color' => '#ef4444', 'label' => 'Admin'],
                                        'department_head' => ['bg' => '#fef3c7', 'color' => '#f59e0b', 'label' => 'Kepala Dept'],
                                        'staff' => ['bg' => '#f0f9ff', 'color' => '#0284c7', 'label' => 'Staff'],
                                        'citizen' => ['bg' => '#f0fdf4', 'color' => '#10b981', 'label' => 'Warga']
                                    ];
                                    $roleInfo = $roleColors[$user->role] ?? ['bg' => '#f8f9fa', 'color' => '#666', 'label' => ucfirst($user->role)];
                                @endphp
                                <span style="background: {{ $roleInfo['bg'] }}; color: {{ $roleInfo['color'] }}; padding: 0.35rem 0.75rem; border-radius: 12px; font-size: 0.85rem; font-weight: 600;">
                                    {{ $roleInfo['label'] }}
                                </span>
                            </td>
                            <td>
                                <span style="color: #666; font-size: 0.9rem;">
                                    {{ $user->department ? $user->department->name : '-' }}
                                </span>
                            </td>
                            <td>
                                <span style="background: #f0fdf4; color: #10b981; padding: 0.35rem 0.75rem; border-radius: 12px; font-size: 0.85rem; font-weight: 600;">
                                    Aktif
                                </span>
                            </td>
                            <td>
                                <span style="color: #999; font-size: 0.9rem;">{{ $user->created_at->format('d M Y') }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Custom Pagination - Kecil -->
            @if($users->hasPages())
                <div style="display: flex; justify-content: center; align-items: center; gap: 0.5rem; margin-top: 1.5rem; flex-wrap: wrap;">
                    {{-- Previous Page Link --}}
                    @if ($users->onFirstPage())
                        <span style="color: #ccc; font-size: 0.75rem; padding: 0.25rem 0.5rem; border: 1px solid #e9ecef; border-radius: 4px; cursor: not-allowed;">
                            ‹ Prev
                        </span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" style="color: #0284c7; font-size: 0.75rem; padding: 0.25rem 0.5rem; border: 1px solid #e9ecef; border-radius: 4px; text-decoration: none; transition: all 0.2s;">
                            ‹ Prev
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                        @if ($page == $users->currentPage())
                            <span style="background: #0284c7; color: white; font-size: 0.75rem; padding: 0.25rem 0.5rem; border: 1px solid #0284c7; border-radius: 4px; font-weight: 600;">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" style="color: #0284c7; font-size: 0.75rem; padding: 0.25rem 0.5rem; border: 1px solid #e9ecef; border-radius: 4px; text-decoration: none; transition: all 0.2s;">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" style="color: #0284c7; font-size: 0.75rem; padding: 0.25rem 0.5rem; border: 1px solid #e9ecef; border-radius: 4px; text-decoration: none; transition: all 0.2s;">
                            Next ›
                        </a>
                    @else
                        <span style="color: #ccc; font-size: 0.75rem; padding: 0.25rem 0.5rem; border: 1px solid #e9ecef; border-radius: 4px; cursor: not-allowed;">
                            Next ›
                        </span>
                    @endif
                </div>
            @endif
        @else
            <div class="empty-state">
                <i class="fas fa-users"></i>
                <p>Belum ada pengguna terdaftar</p>
                <p style="font-size: 0.9rem; color: #bbb; margin-top: 0.5rem;">Mulai dengan menambahkan pengguna baru</p>
            </div>
        @endif
    </div>
</div>

<!-- View User Modals -->
@foreach($users as $user)
<div class="modal fade" id="viewUserModal{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pengguna #{{ $user->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Pribadi</h6>
                        <p><strong>Nama:</strong> {{ $user->name }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Role:</strong> 
                            <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'department_head' ? 'warning' : ($user->role == 'staff' ? 'info' : 'secondary')) }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Informasi Departemen</h6>
                        @if($user->department)
                        <p><strong>Departemen:</strong> {{ $user->department->name }}</p>
                        <p><strong>Kode:</strong> {{ $user->department->code }}</p>
                        <p><strong>Email Departemen:</strong> {{ $user->department->email }}</p>
                        @else
                        <p class="text-muted">Tidak terdaftar di departemen</p>
                        @endif
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Informasi Akun</h6>
                        <p><strong>Tanggal Daftar:</strong> {{ $user->created_at->format('d F Y, H:i') }}</p>
                        <p><strong>Terakhir Update:</strong> {{ $user->updated_at->format('d F Y, H:i') }}</p>
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
