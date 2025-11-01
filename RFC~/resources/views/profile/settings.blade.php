@extends('layouts.dashboard')

@php
    $lang = $settings['language'] ?? (auth()->user()->getSettings('language','id') ?? 'id');
    $t = function(string $key) use ($lang) {
        $id = [
            'settings' => 'Pengaturan',
            'back_to_profile' => 'Kembali ke Profil',
            'general_prefs' => 'Preferensi Umum',
            'theme_appearance' => 'Tema & Tampilan',
            'theme' => 'Tema',
            'light' => 'Terang',
            'dark' => 'Gelap',
            'language' => 'Bahasa',
            'notifications' => 'Notifikasi',
            'email_notifications' => 'Notifikasi Email',
            'browser_notifications' => 'Notifikasi Browser',
            'sms_notifications' => 'Notifikasi SMS',
            'privacy' => 'Privasi',
            'show_email' => 'Tampilkan Email',
            'show_phone' => 'Tampilkan Telepon',
            'show_address' => 'Tampilkan Alamat',
            'save_settings' => 'Simpan Pengaturan',
            'change_password' => 'Ubah Password',
            'current_password' => 'Password Saat Ini',
            'new_password' => 'Password Baru',
            'confirm_new_password' => 'Konfirmasi Password Baru',
            'update_password' => 'Ubah Password',
            'account_info' => 'Informasi Akun',
            'role' => 'Role',
            'department' => 'Departemen',
            'joined' => 'Bergabung',
            'last_login' => 'Login Terakhir',
            'status' => 'Status',
            'active' => 'Aktif',
        ];
        $en = [
            'settings' => 'Settings',
            'back_to_profile' => 'Back to Profile',
            'general_prefs' => 'General Preferences',
            'theme_appearance' => 'Theme & Appearance',
            'theme' => 'Theme',
            'light' => 'Light',
            'dark' => 'Dark',
            'language' => 'Language',
            'notifications' => 'Notifications',
            'email_notifications' => 'Email Notifications',
            'browser_notifications' => 'Browser Notifications',
            'sms_notifications' => 'SMS Notifications',
            'privacy' => 'Privacy',
            'show_email' => 'Show Email',
            'show_phone' => 'Show Phone',
            'show_address' => 'Show Address',
            'save_settings' => 'Save Settings',
            'change_password' => 'Change Password',
            'current_password' => 'Current Password',
            'new_password' => 'New Password',
            'confirm_new_password' => 'Confirm New Password',
            'update_password' => 'Update Password',
            'account_info' => 'Account Information',
            'role' => 'Role',
            'department' => 'Department',
            'joined' => 'Joined',
            'last_login' => 'Last Login',
            'status' => 'Status',
            'active' => 'Active',
        ];
        return $lang === 'en' ? ($en[$key] ?? $key) : ($id[$key] ?? $key);
    };
@endphp

@section('title', $t('settings'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-cog me-2"></i>{{ $t('settings') }}
                </h1>
                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>{{ $t('back_to_profile') }}
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Settings Form -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-sliders-h me-2"></i>{{ $t('general_prefs') }}
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Theme Settings -->
                            <div class="col-md-6 mb-4">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-palette me-2"></i>{{ $t('theme_appearance') }}
                                </h6>
                                
                                <div class="mb-3">
                                    <label for="theme" class="form-label">{{ $t('theme') }}</label>
                                    <select class="form-select" id="theme" name="theme">
                                        <option value="light" {{ $settings['theme'] === 'light' ? 'selected' : '' }}>
                                            <i class="fas fa-sun"></i> {{ $t('light') }}
                                        </option>
                                        <option value="dark" {{ $settings['theme'] === 'dark' ? 'selected' : '' }}>
                                            <i class="fas fa-moon"></i> {{ $t('dark') }}
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="language" class="form-label">{{ $t('language') }}</label>
                                    <select class="form-select" id="language" name="language">
                                        <option value="id" {{ $settings['language'] === 'id' ? 'selected' : '' }}>
                                            🇮🇩 Bahasa Indonesia
                                        </option>
                                        <option value="en" {{ $settings['language'] === 'en' ? 'selected' : '' }}>
                                            🇺🇸 English
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Notification Settings -->
                            <div class="col-md-6 mb-4">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-bell me-2"></i>{{ $t('notifications') }}
                                </h6>
                                
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="notifications_email" name="notifications[email]" value="1" {{ $settings['notifications']['email'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="notifications_email">
                                        <i class="fas fa-envelope me-2"></i>{{ $t('email_notifications') }}
                                    </label>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="notifications_browser" name="notifications[browser]" value="1" {{ $settings['notifications']['browser'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="notifications_browser">
                                        <i class="fas fa-desktop me-2"></i>{{ $t('browser_notifications') }}
                                    </label>
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="notifications_sms" name="notifications[sms]" value="1" {{ $settings['notifications']['sms'] ? 'checked' : '' }}>
                                    <label class="form-check-label" for="notifications_sms">
                                        <i class="fas fa-sms me-2"></i>{{ $t('sms_notifications') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Privacy Settings -->
                        <div class="row">
                            <div class="col-12 mb-4">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-shield-alt me-2"></i>{{ $t('privacy') }}
                                </h6>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="privacy_show_email" name="privacy[show_email]" value="1" {{ $settings['privacy']['show_email'] ? 'checked' : '' }}>
                                            <label class="form-check-label" for="privacy_show_email">
                                                <i class="fas fa-envelope me-2"></i>{{ $t('show_email') }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="privacy_show_phone" name="privacy[show_phone]" value="1" {{ $settings['privacy']['show_phone'] ? 'checked' : '' }}>
                                            <label class="form-check-label" for="privacy_show_phone">
                                                <i class="fas fa-phone me-2"></i>{{ $t('show_phone') }}
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="privacy_show_address" name="privacy[show_address]" value="1" {{ $settings['privacy']['show_address'] ? 'checked' : '' }}>
                                            <label class="form-check-label" for="privacy_show_address">
                                                <i class="fas fa-map-marker-alt me-2"></i>{{ $t('show_address') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>{{ $t('save_settings') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Change Password & Account Actions -->
        <div class="col-lg-4">
            <!-- Change Password -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-key me-2"></i>{{ $t('change_password') }}
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">{{ $t('current_password') }}</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ $t('new_password') }}</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">{{ $t('confirm_new_password') }}</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-warning w-100">
                            <i class="fas fa-lock me-1"></i>{{ $t('update_password') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Account Info -->
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle me-2"></i>{{ $t('account_info') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">{{ $t('role') }}:</small>
                        <div class="fw-bold">{{ $user->getRoleDisplayName() }}</div>
                    </div>
                    
                    @if($user->department)
                    <div class="mb-3">
                        <small class="text-muted">{{ $t('department') }}:</small>
                        <div class="fw-bold">{{ $user->department->name }}</div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <small class="text-muted">{{ $t('joined') }}:</small>
                        <div class="fw-bold">{{ $user->created_at->format($lang === 'en' ? 'M d, Y' : 'd F Y') }}</div>
                    </div>

                    @if($user->last_login_at)
                    <div class="mb-3">
                        <small class="text-muted">{{ $t('last_login') }}:</small>
                        <div class="fw-bold">{{ $user->last_login_at->format($lang === 'en' ? 'M d, Y, H:i' : 'd F Y, H:i') }}</div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <small class="text-muted">{{ $t('status') }}:</small>
                        <span class="badge bg-success">{{ $t('active') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-check-input:checked {
    background-color: #0284c7;
    border-color: #0284c7;
}

.form-check-label {
    font-weight: 500;
    color: #5a5c69;
}

.card {
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0;
    background: linear-gradient(135deg, rgba(14, 165, 233, 0.1) 0%, rgba(2, 132, 199, 0.05) 100%);
}

.form-switch .form-check-input {
    width: 2.5em;
    height: 1.25em;
}

.form-switch .form-check-input:checked {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
}

.btn-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border: none;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(245, 158, 11, 0.4);
}
</style>
@endsection

@push('scripts')
<script>
    // Immediately apply selected theme when changed, and save preference to localStorage
    document.addEventListener('DOMContentLoaded', function() {
        const themeSelect = document.getElementById('theme');
        if (!themeSelect) return;

        function applyTheme(t) {
            const root = document.documentElement;
            if (t === 'dark') root.classList.add('dark'); else root.classList.remove('dark');
        }

        // Apply current value on load
        applyTheme(themeSelect.value || 'light');

        themeSelect.addEventListener('change', function() {
            const val = this.value;
            // Save to localStorage for immediate UX
            try { localStorage.setItem('theme', val); } catch(e) {}
            applyTheme(val);
        });
    });
</script>
@endpush
