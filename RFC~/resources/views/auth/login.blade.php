@extends('layouts.app')

@section('content')
<div class="login-main-container">
    <!-- Animated Background -->
    <div class="animated-bg">
        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
        <div class="circle circle-3"></div>
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
    </div>
    
    <div class="login-illustration">
        <!-- Logo & Branding -->
        <div class="brand-section">
            <div class="logo-container">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h1 class="brand-title">FRC System</h1>
            <p class="brand-subtitle">Sistem Pelaporan & Keluhan Masyarakat</p>
            <div class="features-list">
                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Laporan Real-time</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Tracking Status</span>
                </div>
                <div class="feature-item">
                    <i class="fas fa-check-circle"></i>
                    <span>Respon Cepat</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="login-form-section">
        <div class="login-form-card">
            <div class="card-header-modern">
                <h2 class="login-title">Selamat Datang! 👋</h2>
                <p class="login-desc">Silakan login dengan email dan password Anda untuk mengakses sistem</p>
            </div>
            <form method="POST" action="{{ route('login') }}" class="login-form-modern">
                @csrf
                <div class="form-group-modern">
                    <div class="input-icon-group">
                        <i class="fas fa-envelope"></i>
                        <input id="email" type="email" class="form-input-modern @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email Address">
                    </div>
                    @error('email')
                        <div class="error-message-modern">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group-modern">
                    <div class="input-icon-group">
                        <i class="fas fa-lock"></i>
                        <input id="password" type="password" class="form-input-modern @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                        <span class="toggle-password" onclick="togglePassword()"><i class="fas fa-eye"></i></span>
                    </div>
                    @error('password')
                        <div class="error-message-modern">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-options-modern">
                    <label class="checkbox-modern">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="checkmark-modern"></span>
                        Remember Me
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link-modern">Forgot Password?</a>
                    @endif
                </div>
                <div class="form-btns-modern">
                    <button type="submit" class="login-btn-modern">Login Now</button>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="register-btn-modern">Create Account</a>
                    @endif
                </div>
                <div class="login-divider"><span>Or you can join with</span></div>
                <div class="login-socials">
                    <a href="#" class="social-btn google"><i class="fab fa-google"></i></a>
                    <a href="#" class="social-btn facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-btn twitter"><i class="fab fa-twitter"></i></a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Main Container with Animated Background */
.login-main-container {
    display: flex;
    min-height: 100vh;
    background: linear-gradient(135deg, #003d6b 0%, #004a7f 50%, #00527a 100%);
    position: relative;
    overflow: hidden;
}

/* Animated Background Elements */
.animated-bg {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: 0;
}

.circle {
    position: absolute;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: float 20s infinite ease-in-out;
}

.circle-1 {
    width: 400px;
    height: 400px;
    top: -200px;
    right: -100px;
    animation-delay: 0s;
}

.circle-2 {
    width: 300px;
    height: 300px;
    bottom: -150px;
    left: -100px;
    animation-delay: 5s;
}

.circle-3 {
    width: 250px;
    height: 250px;
    top: 50%;
    left: 50%;
    animation-delay: 10s;
}

@keyframes float {
    0%, 100% { transform: translate(0, 0) scale(1); }
    25% { transform: translate(30px, -30px) scale(1.1); }
    50% { transform: translate(-20px, 20px) scale(0.9); }
    75% { transform: translate(20px, 30px) scale(1.05); }
}

.floating-shapes {
    position: absolute;
    width: 100%;
    height: 100%;
}

.shape {
    position: absolute;
    background: rgba(255, 255, 255, 0.05);
    animation: rotate 30s infinite linear;
}

.shape-1 {
    width: 80px;
    height: 80px;
    top: 20%;
    left: 10%;
    border-radius: 20px;
    animation-delay: 0s;
}

.shape-2 {
    width: 60px;
    height: 60px;
    top: 60%;
    right: 15%;
    border-radius: 50%;
    animation-delay: 10s;
}

.shape-3 {
    width: 100px;
    height: 100px;
    bottom: 15%;
    left: 50%;
    border-radius: 30px;
    animation-delay: 20s;
}

@keyframes rotate {
    0% { transform: rotate(0deg) translateY(0); }
    50% { transform: rotate(180deg) translateY(-20px); }
    100% { transform: rotate(360deg) translateY(0); }
}

/* Left Illustration Section */
.login-illustration {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem;
    position: relative;
    z-index: 1;
}

.brand-section {
    text-align: center;
    color: white;
    animation: fadeInLeft 0.8s ease-out;
}

@keyframes fadeInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.logo-container {
    width: 120px;
    height: 120px;
    margin: 0 auto 2rem;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border-radius: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
    animation: pulse 3s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.logo-container i {
    font-size: 4rem;
    color: white;
}

.brand-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    text-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.brand-subtitle {
    font-size: 1.1rem;
    opacity: 0.95;
    margin-bottom: 3rem;
    font-weight: 300;
}

.features-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    align-items: flex-start;
    max-width: 350px;
    margin: 0 auto;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 1rem 1.5rem;
    border-radius: 12px;
    width: 100%;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.feature-item:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateX(10px);
}

.feature-item i {
    font-size: 1.5rem;
    color: #4ade80;
}

.feature-item span {
    font-size: 1rem;
    font-weight: 500;
}

/* Right Form Section */
.login-form-section {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    position: relative;
    z-index: 1;
}

.login-form-card {
    width: 100%;
    max-width: 480px;
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    border-radius: 24px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    padding: 3rem 2.5rem;
    border: 1px solid rgba(255, 255, 255, 0.5);
    animation: fadeInRight 0.8s ease-out;
}

@keyframes fadeInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.card-header-modern {
    margin-bottom: 2rem;
}

.login-title {
    font-size: 2.2rem;
    font-weight: 700;
    background: linear-gradient(135deg, #003d6b 0%, #00527a 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.5rem;
}

.login-desc {
    color: #6b7280;
    font-size: 0.95rem;
    line-height: 1.6;
}
.login-form-modern {
    width: 100%;
}
.form-group-modern {
    margin-bottom: 1.3rem;
}
.input-icon-group {
    position: relative;
    display: flex;
    align-items: center;
}
.input-icon-group i.fas {
    position: absolute;
    left: 1rem;
    color: #b0b8c1;
    font-size: 1.1rem;
    z-index: 2;
}
.form-input-modern {
    width: 100%;
    padding: 1rem 1rem 1rem 3rem;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    background: #f9fafb;
    color: #1f2937;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-input-modern:focus {
    border-color: #004a7f;
    outline: none;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(0, 74, 127, 0.1);
    transform: translateY(-1px);
}
.toggle-password {
    position: absolute;
    right: 1rem;
    color: #b0b8c1;
    cursor: pointer;
    font-size: 1.1rem;
    z-index: 2;
}
.error-message-modern {
    color: #e74c3c;
    font-size: 0.9rem;
    margin-top: 0.3rem;
    display: flex;
    align-items: center;
    gap: 0.4rem;
}
.form-options-modern {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    font-size: 0.95rem;
}
.checkbox-modern {
    display: flex;
    align-items: center;
    cursor: pointer;
    user-select: none;
}
.checkbox-modern input {
    display: none;
}
.checkmark-modern {
    width: 18px;
    height: 18px;
    border: 1.5px solid #b0b8c1;
    border-radius: 4px;
    margin-right: 0.5rem;
    background: #f4f8fb;
    position: relative;
}
.checkbox-modern input:checked + .checkmark-modern {
    background: #3498db;
    border-color: #3498db;
}
.checkbox-modern input:checked + .checkmark-modern:after {
    content: '';
    position: absolute;
    left: 5px;
    top: 2px;
    width: 5px;
    height: 10px;
    border: solid #fff;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}
.forgot-link-modern {
    color: #3498db;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}
.forgot-link-modern:hover {
    color: #217dbb;
}
.form-btns-modern {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
}
.login-btn-modern {
    flex: 1;
    background: linear-gradient(135deg, #003d6b 0%, #00527a 100%);
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 1rem 0;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 61, 107, 0.3);
    position: relative;
    overflow: hidden;
}

.login-btn-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.login-btn-modern:hover::before {
    left: 100%;
}

.login-btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 61, 107, 0.4);
}
.register-btn-modern {
    flex: 1;
    background: transparent;
    color: #004a7f;
    border: 2px solid #004a7f;
    border-radius: 12px;
    padding: 1rem 0;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    transition: all 0.3s ease;
}

.register-btn-modern:hover {
    background: #004a7f;
    color: #fff;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 74, 127, 0.3);
}
.login-divider {
    text-align: center;
    color: #b0b8c1;
    margin: 1.2rem 0 1.1rem 0;
    font-size: 0.95rem;
    position: relative;
}
.login-divider span {
    background: #fff;
    padding: 0 1rem;
    position: relative;
    z-index: 1;
}
.login-divider:before, .login-divider:after {
    content: '';
    position: absolute;
    top: 50%;
    width: 40%;
    height: 1px;
    background: #e3e6f0;
}
.login-divider:before {
    left: 0;
}
.login-divider:after {
    right: 0;
}
.login-socials {
    display: flex;
    justify-content: center;
    gap: 1.2rem;
    margin-bottom: 0.5rem;
}
.social-btn {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
    color: #fff;
    background: #b0b8c1;
    transition: background 0.2s, box-shadow 0.2s;
    box-shadow: 0 2px 8px rgba(44,62,80,0.08);
    text-decoration: none;
}
.social-btn.google { background: #ea4335; }
.social-btn.facebook { background: #1877f3; }
.social-btn.twitter { background: #1da1f2; }
.social-btn:hover { filter: brightness(1.1); }
@media (max-width: 1024px) {
    .login-illustration {
        display: none;
    }
    
    .login-form-section {
        flex: 1;
        width: 100%;
    }
    
    .login-form-card {
        max-width: 500px;
    }
}

@media (max-width: 640px) {
    .login-form-card {
        padding: 2rem 1.5rem;
        border-radius: 20px;
    }
    
    .login-title {
        font-size: 1.8rem;
    }
    
    .form-btns-modern {
        flex-direction: column;
    }
}
</style>
<script>
function togglePassword() {
    var input = document.getElementById('password');
    var icon = document.querySelector('.toggle-password i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endsection
