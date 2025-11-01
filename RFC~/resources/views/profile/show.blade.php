@extends('layouts.dashboard')

@section('title', 'Profil Saya')

@section('content')
<style>
    .profile-header {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 50%, #0369a1 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
    }

    .profile-header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 2rem;
    }

    .profile-header-left {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .profile-avatar-large {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.3);
        object-fit: cover;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .profile-header-info h1 {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0;
    }

    .profile-header-info p {
        margin: 0.25rem 0;
        opacity: 0.95;
    }

    .profile-header-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .profile-header-actions .btn {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .profile-header-actions .btn:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.5);
        color: white;
        transform: translateY(-2px);
    }

    .profile-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #f0f0f0;
        overflow: hidden;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .profile-card:hover {
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }

    .profile-card-header {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        padding: 1.25rem;
        border-bottom: 1px solid #e9ecef;
    }

    .profile-card-header h5 {
        margin: 0;
        color: #0369a1;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .profile-card-header i {
        color: #0284c7;
        font-size: 1.1rem;
    }

    .profile-card-body {
        padding: 1.5rem;
    }

    .profile-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .profile-item:last-child {
        border-bottom: none;
    }

    .profile-item-icon {
        color: #0284c7;
        font-size: 1.1rem;
        min-width: 24px;
        text-align: center;
        margin-top: 0.25rem;
    }

    .profile-item-content {
        flex: 1;
    }

    .profile-item-label {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .profile-item-value {
        color: #555;
        font-size: 0.95rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .stat-box {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(14, 165, 233, 0.2);
        transition: all 0.3s ease;
    }

    .stat-box:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 20px rgba(14, 165, 233, 0.3);
    }

    .stat-box.stat-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .stat-box.stat-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .stat-box.stat-info {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
    }

    .stat-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.85rem;
        opacity: 0.9;
    }

    @media (max-width: 768px) {
        .profile-header-content {
            flex-direction: column;
            gap: 1rem;
        }

        .profile-header-left {
            width: 100%;
        }

        .profile-header-actions {
            width: 100%;
        }

        .profile-header-actions .btn {
            flex: 1;
        }
    }
</style>

<div class="container-fluid">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-header-content">
            <div class="profile-header-left">
                @php
                    $avatarPath = $user->avatar;
                    $avatarUrl = $avatarPath ? route('avatar.show', basename($avatarPath)) : null;
                    $initials = $user->getAvatarInitials();
                    $avatarColor = $user->getAvatarColor();
                @endphp
                @if($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="profile-avatar-large"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="profile-avatar-large" style="display:none;align-items:center;justify-content:center;font-size:2.5rem;font-weight:700;background: {{ $avatarColor }};">{{ $initials }}</div>
                @else
                    <div class="profile-avatar-large d-flex align-items-center justify-content-center" style="font-size:2.5rem;font-weight:700;background: {{ $avatarColor }};">{{ $initials }}</div>
                @endif
                <div class="profile-header-info">
                    <h1>{{ $user->name }}</h1>
                    <p><i class="fas fa-briefcase me-1"></i>{{ $user->getRoleDisplayName() }}</p>
                    @if($user->department)
                    <p><i class="fas fa-building me-1"></i>{{ $user->department->name }}</p>
                    @endif
                </div>
            </div>
            <div class="profile-header-actions">
                <a href="{{ route('profile.edit') }}" class="btn">
                    <i class="fas fa-edit me-1"></i>Edit Profil
                </a>
                <a href="{{ route('profile.settings') }}" class="btn">
                    <i class="fas fa-cog me-1"></i>Pengaturan
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Contact Information -->
        <div class="col-lg-6 mb-4">
            <div class="profile-card">
                <div class="profile-card-header">
                    <h5><i class="fas fa-address-card"></i>Informasi Kontak</h5>
                </div>
                <div class="profile-card-body">
                    <div class="profile-item">
                        <div class="profile-item-icon"><i class="fas fa-envelope"></i></div>
                        <div class="profile-item-content">
                            <div class="profile-item-label">Email</div>
                            <div class="profile-item-value">{{ $user->email }}</div>
                        </div>
                    </div>
                    @if($user->phone)
                    <div class="profile-item">
                        <div class="profile-item-icon"><i class="fas fa-phone"></i></div>
                        <div class="profile-item-content">
                            <div class="profile-item-label">Telepon</div>
                            <div class="profile-item-value">{{ $user->phone }}</div>
                        </div>
                    </div>
                    @endif
                    @if($user->address)
                    <div class="profile-item">
                        <div class="profile-item-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div class="profile-item-content">
                            <div class="profile-item-label">Alamat</div>
                            <div class="profile-item-value">{{ $user->address }}</div>
                        </div>
                    </div>
                    @endif
                    @if($user->employee_id)
                    <div class="profile-item">
                        <div class="profile-item-icon"><i class="fas fa-id-card"></i></div>
                        <div class="profile-item-content">
                            <div class="profile-item-label">ID Karyawan</div>
                            <div class="profile-item-value">{{ $user->employee_id }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="col-lg-6 mb-4">
            <div class="profile-card">
                <div class="profile-card-header">
                    <h5><i class="fas fa-user-circle"></i>Informasi Pribadi</h5>
                </div>
                <div class="profile-card-body">
                    @if($user->birth_date)
                    <div class="profile-item">
                        <div class="profile-item-icon"><i class="fas fa-birthday-cake"></i></div>
                        <div class="profile-item-content">
                            <div class="profile-item-label">Tanggal Lahir</div>
                            <div class="profile-item-value">{{ $user->birth_date->format('d F Y') }}</div>
                        </div>
                    </div>
                    @endif
                    @if($user->gender)
                    <div class="profile-item">
                        <div class="profile-item-icon"><i class="fas fa-{{ $user->gender === 'male' ? 'mars' : 'venus' }}"></i></div>
                        <div class="profile-item-content">
                            <div class="profile-item-label">Jenis Kelamin</div>
                            <div class="profile-item-value">{{ $user->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</div>
                        </div>
                    </div>
                    @endif
                    <div class="profile-item">
                        <div class="profile-item-icon"><i class="fas fa-calendar"></i></div>
                        <div class="profile-item-content">
                            <div class="profile-item-label">Bergabung</div>
                            <div class="profile-item-value">{{ $user->created_at->format('d F Y') }}</div>
                        </div>
                    </div>
                    @if($user->last_login_at)
                    <div class="profile-item">
                        <div class="profile-item-icon"><i class="fas fa-clock"></i></div>
                        <div class="profile-item-content">
                            <div class="profile-item-label">Login Terakhir</div>
                            <div class="profile-item-value">{{ $user->last_login_at->format('d F Y, H:i') }}</div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Stats (for non-citizens) -->
    @if(!$user->isCitizen())
    <div class="row">
        <div class="col-12">
            <div class="profile-card">
                <div class="profile-card-header">
                    <h5><i class="fas fa-chart-bar"></i>Statistik Aktivitas</h5>
                </div>
                <div class="profile-card-body">
                    <div class="stats-grid">
                        @if($user->isAdmin())
                        <div class="stat-box">
                            <div class="stat-icon"><i class="fas fa-users"></i></div>
                            <div class="stat-number">{{ \App\Models\User::count() }}</div>
                            <div class="stat-label">Total Pengguna</div>
                        </div>
                        <div class="stat-box stat-success">
                            <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
                            <div class="stat-number">{{ \App\Models\Report::count() }}</div>
                            <div class="stat-label">Total Laporan</div>
                        </div>
                        <div class="stat-box stat-warning">
                            <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                            <div class="stat-number">{{ \App\Models\Complaint::count() }}</div>
                            <div class="stat-label">Total Keluhan</div>
                        </div>
                        <div class="stat-box stat-info">
                            <div class="stat-icon"><i class="fas fa-building"></i></div>
                            <div class="stat-number">{{ \App\Models\Department::count() }}</div>
                            <div class="stat-label">Total Departemen</div>
                        </div>
                        @else
                        <div class="stat-box">
                            <div class="stat-icon"><i class="fas fa-tasks"></i></div>
                            <div class="stat-number">{{ $user->assignedReports()->count() }}</div>
                            <div class="stat-label">Tugas Laporan</div>
                        </div>
                        <div class="stat-box stat-success">
                            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                            <div class="stat-number">{{ $user->assignedReports()->where('status', 'resolved')->count() }}</div>
                            <div class="stat-label">Diselesaikan</div>
                        </div>
                        <div class="stat-box stat-warning">
                            <div class="stat-icon"><i class="fas fa-clock"></i></div>
                            <div class="stat-number">{{ $user->assignedReports()->whereIn('status', ['submitted', 'pending', 'in_progress'])->count() }}</div>
                            <div class="stat-label">Dalam Proses</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
