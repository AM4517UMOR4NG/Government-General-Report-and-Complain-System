@extends('layouts.app')

@section('content')
<!-- Force blue palette on register page to remove purple header/bar -->
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
<div class="register-main-container">
    <div class="register-illustration">
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
    
    <div class="register-form-section">
        <div class="register-form-card">
            <div class="card-header-modern">
                <h2 class="register-title">REGISTER</h2>
                <p class="register-subtitle">AND REPORT YOUR PROBLEMS!</p>
            </div>
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="register-form-modern">
                @csrf
                
                <div class="form-group-modern">
                    <label class="form-label">NAME</label>
                    <input id="name" type="text" class="form-input-simple @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Your full name">
                    @error('name')
                        <div class="error-message-modern">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-modern">
                    <label class="form-label">USERNAME</label>
                    <input id="phone" type="tel" class="form-input-simple @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" placeholder="Username">
                    @error('phone')
                        <div class="error-message-modern">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-modern">
                    <label class="form-label">EMAIL</label>
                    <input id="email" type="email" class="form-input-simple @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email address">
                    @error('email')
                        <div class="error-message-modern">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-modern">
                    <label class="form-label">ID NUMBER</label>
                    <input id="id_number" type="text" class="form-input-simple @error('id_number') is-invalid @enderror" name="id_number" value="{{ old('id_number') }}" required placeholder="ID Number (e.g. NIK)">
                    @error('id_number')
                        <div class="error-message-modern">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-modern">
                    <label class="form-label">PASSWORD</label>
                    <input id="password" type="password" class="form-input-simple @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                    @error('password')
                        <div class="error-message-modern">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-modern">
                    <label class="form-label">CONFIRM PASSWORD</label>
                    <input id="password-confirm" type="password" class="form-input-simple" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm password">
                </div>

                <div class="form-group-modern">
                    <label class="form-label">BIRTH DATE</label>
                    <input id="birth_date" type="date" class="form-input-simple @error('birth_date') is-invalid @enderror" name="birth_date" value="{{ old('birth_date') }}" placeholder="dd / mm / yyyy">
                    @error('birth_date')
                        <div class="error-message-modern">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-modern">
                    <label class="form-label">GENDER</label>
                    <select id="gender" class="form-input-simple @error('gender') is-invalid @enderror" name="gender">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                    @error('gender')
                        <div class="error-message-modern">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-modern">
                    <label class="form-label">ADDRESS</label>
                    <textarea id="address" class="form-input-simple textarea-simple @error('address') is-invalid @enderror" name="address" rows="2" placeholder="Your full address">{{ old('address') }}</textarea>
                    @error('address')
                        <div class="error-message-modern">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group-modern">
                    <label class="checkbox-container">
                        <input type="checkbox" required>
                        <span class="checkmark"></span>
                        <span class="checkbox-text">I have read and agree to the terms & conditions</span>
                    </label>
                </div>

                <div class="form-btns-modern">
                    <button type="submit" class="register-btn-simple">CREATE ACCOUNT</button>
                </div>

                <div class="login-link-text">
                    I'm already a member <a href="{{ route('login') }}" class="link-simple">Login</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Main Container */
.register-main-container {
    display: flex;
    min-height: 100vh;
    background: #2d4152;
    position: relative;
    overflow: hidden;
}

/* Left Illustration Section with Turquoise Gradient */
.register-illustration {
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
.register-form-section {
    flex: 1;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    padding: 1rem 1.5rem;
    position: relative;
    background: #384454;
    overflow-y: auto;
    overflow-x: hidden;
    max-height: 100vh;
    -webkit-overflow-scrolling: touch;
}

.register-form-card {
    width: 100%;
    max-width: 520px;
    padding: 2rem 2.5rem;
    margin: auto;
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

/* Custom Scrollbar */
.register-form-section::-webkit-scrollbar {
    width: 8px;
}

.register-form-section::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.1);
}

.register-form-section::-webkit-scrollbar-thumb {
    background: rgba(38, 208, 206, 0.3);
    border-radius: 10px;
}

.register-form-section::-webkit-scrollbar-thumb:hover {
    background: rgba(38, 208, 206, 0.5);
}

.card-header-modern {
    margin-bottom: 2rem;
    text-align: center;
}

.register-title {
    font-size: 2rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 0.3rem;
    letter-spacing: 2px;
}

.register-subtitle {
    color: #26d0ce;
    font-size: 0.9rem;
    letter-spacing: 1px;
    font-weight: 400;
}

.register-form-modern {
    width: 100%;
}

.form-group-modern {
    margin-bottom: 1.2rem;
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

.form-input-simple {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 1px solid #4a5568;
    border-radius: 4px;
    background: transparent;
    color: #ffffff;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.form-input-simple::placeholder {
    color: #718096;
}

.form-input-simple:focus {
    border-color: #26d0ce;
    outline: none;
    box-shadow: 0 0 0 2px rgba(38, 208, 206, 0.2);
}

.form-input-simple.is-invalid {
    border-color: #ef4444;
}

select.form-input-simple {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23718096' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 1rem center;
    padding-right: 2.5rem;
}

select.form-input-simple option {
    background: #1a202c;
    color: #ffffff;
    padding: 0.5rem;
}

.textarea-simple {
    resize: vertical;
    min-height: 60px;
    font-family: inherit;
}

.error-message-modern {
    color: #fc8181;
    font-size: 0.8rem;
    margin-top: 0.3rem;
}

/* Checkbox Styling */
.checkbox-container {
    display: flex;
    align-items: flex-start;
    cursor: pointer;
    user-select: none;
    color: #a0aec0;
    font-size: 0.85rem;
    line-height: 1.4;
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
    margin-right: 0.7rem;
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
    flex: 1;
}

.form-btns-modern {
    margin-top: 1.5rem;
    margin-bottom: 1rem;
}

.register-btn-simple {
    width: 100%;
    background: #26d0ce;
    color: #ffffff;
    border: none;
    border-radius: 4px;
    padding: 0.9rem 0;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.register-btn-simple:hover {
    background: #1db8b6;
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(38, 208, 206, 0.4);
}

.register-btn-simple:active {
    transform: translateY(0);
}

.login-link-text {
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
    .register-illustration {
        display: none;
    }
    
    .register-form-section {
        flex: 1;
        width: 100%;
    }
}

@media (max-width: 768px) {
    .register-form-card {
        padding: 1.5rem 1.2rem;
    }
    
    .register-title {
        font-size: 1.6rem;
    }
}
</style>


<script>
function togglePasswordField(fieldId) {
    var input = document.getElementById(fieldId);
    var icon = input.parentElement.querySelector('.toggle-password i');
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

// Auto-format phone number
document.addEventListener('DOMContentLoaded', function() {
    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 13) value = value.slice(0, 13);
            e.target.value = value;
        });
    }

    // Auto-format ID number
    const idInput = document.getElementById('id_number');
    if (idInput) {
        idInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 16) value = value.slice(0, 16);
            e.target.value = value;
        });
    }
});
</script>
@endsection