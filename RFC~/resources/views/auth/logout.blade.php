@extends('layouts.dashboard')

@section('title', 'Konfirmasi Logout')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header py-3 text-center">
                <h4 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-sign-out-alt me-2"></i>Konfirmasi Logout
                </h4>
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <i class="fas fa-question-circle text-warning" style="font-size: 4rem;"></i>
                </div>
                
                <h5 class="mb-3">Apakah Anda yakin ingin keluar?</h5>
                <p class="text-muted mb-4">
                    Anda akan keluar dari sistem dan perlu login kembali untuk mengakses dashboard.
                </p>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="{{ route('home') }}" class="btn btn-secondary me-md-2">
                        <i class="fas fa-arrow-left me-1"></i>Kembali ke Dashboard
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt me-1"></i>Ya, Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection