@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center register-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-xl-7">
                <div class="card shadow-lg border-0" style="border-radius: 20px; backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.95);">
                    <div class="card-header text-center border-0 bg-transparent py-4">
                        <!-- Hapus gambar ilustrasi di atas form -->
                        <div class="logo-container mb-3">
                            <div class="logo-icon">
                                <i class="fas fa-shield-alt" style="font-size: 3rem; color: #4e73df;"></i>
                            </div>
                            <h2 class="logo-text mt-2" style="color: #2c3e50; font-weight: 700;">Daftar Akun Baru</h2>
                            <p class="text-muted">Bergabunglah dengan FRC System</p>
                        </div>
                    </div>
                    <div class="card-body px-5 pb-5">
                        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-user me-2"></i>Informasi Dasar
                                    </h6>

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Masukkan nama lengkap">
                                        </div>
                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="contoh@email.com">
                                        </div>
                                        @error('email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Nomor Telepon</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx">
                                        </div>
                                        @error('phone')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="id_number" class="form-label">Nomor KTP <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                            <input id="id_number" type="text" class="form-control @error('id_number') is-invalid @enderror" name="id_number" value="{{ old('id_number') }}" required placeholder="16 digit nomor KTP">
                                        </div>
                                        @error('id_number')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Personal & Security -->
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-lock me-2"></i>Keamanan & Pribadi
                                    </h6>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                                <i class="fas fa-eye" id="password-eye"></i>
                                            </button>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="password-confirm" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password">
                                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password-confirm')">
                                                <i class="fas fa-eye" id="password-confirm-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="birth_date" class="form-label">Tanggal Lahir</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-birthday-cake"></i></span>
                                            <input id="birth_date" type="date" class="form-control @error('birth_date') is-invalid @enderror" name="birth_date" value="{{ old('birth_date') }}">
                                        </div>
                                        @error('birth_date')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="gender" class="form-label">Jenis Kelamin</label>
                                        <select id="gender" class="form-select @error('gender') is-invalid @enderror" name="gender">
                                            <option value="">Pilih Jenis Kelamin</option>
                                            <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-map-marker-alt me-2"></i>Alamat
                                    </h6>
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Alamat Lengkap</label>
                                        <textarea id="address" class="form-control @error('address') is-invalid @enderror" name="address" rows="3" placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Terms & Submit -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="terms" required>
                                        <label class="form-check-label" for="terms">
                                            Saya menyetujui <a href="#" class="text-primary">Syarat dan Ketentuan</a> serta <a href="#" class="text-primary">Kebijakan Privasi</a>
                                        </label>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary btn-lg" style="border-radius: 50px; background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); border: none; padding: 12px 0;">
                                            <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                                        </button>
                                    </div>

                                    <div class="text-center mt-3">
                                        <p class="mb-0">Sudah punya akun? 
                                            <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Login di sini</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.input-group-text {
    background: linear-gradient(135deg, rgba(78, 115, 223, 0.1) 0%, rgba(78, 115, 223, 0.05) 100%);
    border-color: #d1d3e2;
    color: #4e73df;
}

.form-control:focus, .form-select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4);
    transition: all 0.3s ease;
}

.card {
    animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.logo-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

.register-bg {
    background: url('https://infodiklatkeuangan.com/wp-content/uploads/2022/02/pemerintahan.png') no-repeat center center fixed !important;
    background-size: cover !important;
    min-height: 100vh;
    width: 100vw;
    z-index: 0;
}
.card {
    position: relative;
    z-index: 1;
    background: rgba(255,255,255,0.88);
    backdrop-filter: blur(8px);
    box-shadow: 0 8px 32px rgba(44,62,80,0.18), 0 2px 8px rgba(44,62,80,0.10);
}
.register-illustration {
    display: none;
}
body > #app > .register-bg {
    min-height: 100vh;
    width: 100vw;
    z-index: 0;
}
/* Navbar dan gradasi utama */
.navbar, .navbar.navbar-expand-md {
    background: linear-gradient(135deg, #b3c6e0 0%, #6a93c9 100%) !important;
}
body {
    background: linear-gradient(135deg, #b3c6e0 0%, #6a93c9 100%) !important;
}
</style>


<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const eye = document.getElementById(fieldId + '-eye');
    
    if (field.type === 'password') {
        field.type = 'text';
        eye.classList.remove('fa-eye');
        eye.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        eye.classList.remove('fa-eye-slash');
        eye.classList.add('fa-eye');
    }
}

// Auto-format phone number
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 13) value = value.slice(0, 13);
    e.target.value = value;
});

// Auto-format ID number
document.getElementById('id_number').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 16) value = value.slice(0, 16);
    e.target.value = value;
});
</script>
@endsection