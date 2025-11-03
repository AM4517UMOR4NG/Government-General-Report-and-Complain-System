@extends('layouts.app')

@section('content')
<!-- Force blue palette on login page to remove purple header/bar -->
<style>
    /* Replace page background (overrides layout) */
    body {
        background: #2d4152 !important;
        background-image: none !important;
    }
    /* Hide navbar/header completely */
    .navbar, header[role="banner"], nav, .app-header {
        display: none !important;
    }
    /* Remove any padding/margin from body */
    body { 
        padding-top: 0 !important;
        margin: 0 !important;
    }
    html, body {
        height: 100%;
        overflow-x: hidden;
    }
</style>
<div class="login-main-container">
    <div class="login-illustration">
        <!-- Animated Background Circles -->
        <div class="bg-circle circle-1"></div>
        <div class="bg-circle circle-2"></div>
        <div class="bg-circle circle-3"></div>
        <div class="bg-circle circle-4"></div>
        
        <!-- Logo & Branding -->
        <div class="brand-section">
            <div class="logo-hexagon">
                <div class="logo-content">
                    <div class="logo-text-top">GOVERNMENT</div>
                    <div class="logo-text-mid">REPORT</div>
                    <div class="logo-text-bottom">SYSTEM</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="login-form-section">
        <div class="login-form-card">
            <div class="card-header-modern">
                <h2 class="login-title">LOGIN</h2>
                <p class="login-subtitle">WELCOME BACK</p>
            </div>
            <form method="POST" action="{{ route('login') }}" class="login-form-modern">
                @csrf
                <div class="form-group-modern">
                    <label class="form-label">EMAIL</label>
                    <input id="email" type="email" class="form-input-modern @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email address">
                    @error('email')
                        <div class="error-message-modern">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group-modern">
                    <label class="form-label">PASSWORD</label>
                    <div class="password-input-wrapper">
                        <input id="password" type="password" class="form-input-modern @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
                        <span class="toggle-password" onclick="togglePassword()"><i class="fas fa-eye"></i></span>
                    </div>
                    @error('password')
                        <div class="error-message-modern">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-options-modern">
                    <label class="checkbox-container">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        <span class="checkbox-text">Remember Me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link-modern">Forgot Password?</a>
                    @endif
                </div>
                <div class="form-btns-modern">
                    <button type="submit" class="login-btn-modern">LOGIN NOW</button>
                </div>
                <div class="register-link-text">
                    Don't have an account? <a href="{{ route('register') }}" class="link-simple">Register</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Main Container */
.login-main-container {
    display: flex;
    min-height: 100vh;
    background: #2d4152;
    position: relative;
    overflow: hidden;
}

/* Left Illustration Section with Turquoise Gradient */
.login-illustration {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 3rem;
    position: relative;
    background: linear-gradient(135deg, #26d0ce 0%, #1a9ec2 100%);
    overflow: hidden;
}

/* Animated Background Circles */
.bg-circle {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.15);
    animation: float 20s infinite ease-in-out;
}

.circle-1 {
    width: 450px;
    height: 450px;
    top: -150px;
    left: -100px;
    background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.05) 100%);
    animation-delay: 0s;
}

.circle-2 {
    width: 350px;
    height: 350px;
    top: 100px;
    left: 50px;
    background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0.03) 100%);
    animation-delay: 3s;
}

.circle-3 {
    width: 500px;
    height: 500px;
    bottom: -200px;
    right: -150px;
    background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0.05) 100%);
    animation-delay: 6s;
}

.circle-4 {
    width: 300px;
    height: 300px;
    bottom: 100px;
    left: 20%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.02) 100%);
    animation-delay: 9s;
}

@keyframes float {
    0%, 100% { transform: translate(0, 0) scale(1); }
    25% { transform: translate(20px, -20px) scale(1.05); }
    50% { transform: translate(-15px, 15px) scale(0.95); }
    75% { transform: translate(15px, 20px) scale(1.02); }
}

/* Brand Section */
.brand-section {
    text-align: center;
    position: relative;
    z-index: 2;
}

/* Hexagonal Logo */
.logo-hexagon {
    width: 200px;
    height: 230px;
    position: relative;
    margin: 0 auto;
    clip-path: polygon(50% 0%, 100% 25%, 100% 75%, 50% 100%, 0% 75%, 0% 25%);
    background: #2d4152;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
    animation: pulse 3s infinite;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.03); }
}

.logo-content {
    text-align: center;
    color: white;
}

.logo-text-top {
    font-size: 0.9rem;
    letter-spacing: 2px;
    color: #26d0ce;
    font-weight: 600;
    margin-bottom: 5px;
}

.logo-text-mid {
    font-size: 2.5rem;
    font-weight: 800;
    color: white;
    margin: 5px 0;
    letter-spacing: 3px;
}

.logo-text-bottom {
    font-size: 1.2rem;
    letter-spacing: 3px;
    color: #26d0ce;
    font-weight: 600;
    margin-top: 5px;
}

/* Right Form Section */
.login-form-section {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    position: relative;
    background: #384454;
}

.login-form-card {
    width: 100%;
    max-width: 480px;
    padding: 3rem 2.5rem;
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
    margin-bottom: 2.5rem;
    text-align: center;
}

.login-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.3rem;
    letter-spacing: 2px;
}

.login-subtitle {
    color: #26d0ce;
    font-size: 0.9rem;
    letter-spacing: 1px;
    font-weight: 400;
}

.login-form-modern {
    width: 100%;
}

.form-group-modern {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    color: #a0aec0;
    font-size: 0.75rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.password-input-wrapper {
    position: relative;
}

.form-input-modern {
    width: 100%;
    padding: 0.9rem 1rem;
    border: 1px solid #4a5568;
    border-radius: 4px;
    background: transparent;
    color: #ffffff;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-input-modern::placeholder {
    color: #718096;
}

.form-input-modern:focus {
    border-color: #26d0ce;
    outline: none;
    box-shadow: 0 0 0 2px rgba(38, 208, 206, 0.2);
}

.form-input-modern.is-invalid {
    border-color: #ef4444;
}

.password-input-wrapper .form-input-modern {
    padding-right: 3rem;
}

.toggle-password {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #718096;
    cursor: pointer;
    font-size: 1.1rem;
    transition: color 0.3s ease;
}

.toggle-password:hover {
    color: #26d0ce;
}

.error-message-modern {
    color: #fc8181;
    font-size: 0.8rem;
    margin-top: 0.4rem;
}

.form-options-modern {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    font-size: 0.85rem;
}

/* Checkbox Styling */
.checkbox-container {
    display: flex;
    align-items: center;
    cursor: pointer;
    user-select: none;
    color: #a0aec0;
}

.checkbox-container input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.checkmark {
    width: 18px;
    height: 18px;
    min-width: 18px;
    border: 1px solid #4a5568;
    border-radius: 3px;
    margin-right: 0.6rem;
    background: transparent;
    position: relative;
    transition: all 0.3s ease;
}

.checkbox-container input:checked ~ .checkmark {
    background: #26d0ce;
    border-color: #26d0ce;
}

.checkbox-container input:checked ~ .checkmark:after {
    content: '';
    position: absolute;
    left: 5px;
    top: 2px;
    width: 5px;
    height: 9px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.checkbox-text {
    font-size: 0.85rem;
}

.forgot-link-modern {
    color: #26d0ce;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.forgot-link-modern:hover {
    color: #1db8b6;
    text-decoration: underline;
}

.form-btns-modern {
    margin-bottom: 1.5rem;
}

.login-btn-modern {
    width: 100%;
    background: #26d0ce;
    color: #ffffff;
    border: none;
    border-radius: 4px;
    padding: 1rem 0;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.login-btn-modern:hover {
    background: #1db8b6;
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(38, 208, 206, 0.4);
}

.login-btn-modern:active {
    transform: translateY(0);
}

.register-link-text {
    text-align: center;
    color: #a0aec0;
    font-size: 0.85rem;
}

.link-simple {
    color: #26d0ce;
    text-decoration: none;
    font-weight: 600;
    margin-left: 5px;
}

.link-simple:hover {
    text-decoration: underline;
}

@media (max-width: 1024px) {
    .login-illustration {
        display: none;
    }
    
    .login-form-section {
        flex: 1;
        width: 100%;
    }
}

@media (max-width: 640px) {
    .login-form-card {
        padding: 2rem 1.5rem;
    }
    
    .login-title {
        font-size: 1.8rem;
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
