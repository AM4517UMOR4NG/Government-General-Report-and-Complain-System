<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Government Report System - Laporkan, Kami Tangani</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <div class="logo-icon"><i class="fas fa-shield-alt"></i></div>
                <div class="logo-text">
                    <span class="logo-main">GovReport</span>
                    <span class="logo-sub">System</span>
                </div>
            </div>
            <div class="nav-links">
                <a href="#home" class="nav-link active">Home</a>
                <a href="#features" class="nav-link">Features</a>
                <a href="#how-it-works" class="nav-link">How It Works</a>
                <a href="#stats" class="nav-link">Statistics</a>
            </div>
            <div class="nav-actions">
                <a href="{{ route('login') }}" class="btn-login">Login</a>
                <a href="{{ route('register') }}" class="btn-register">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-bg">
            <div class="hero-circle circle-1"></div>
            <div class="hero-circle circle-2"></div>
            <div class="hero-circle circle-3"></div>
        </div>
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-star"></i>
                    <span>Trusted by 10,000+ Citizens</span>
                </div>
                <h1 class="hero-title">
                    Laporkan Masalah,<br>
                    <span class="gradient-text">Kami Tangani Solusi</span>
                </h1>
                <p class="hero-description">
                    Platform pelaporan dan pengaduan masyarakat yang aman, cepat, dan terpercaya. 
                    Sampaikan aspirasi Anda dan pantau progress penanganan secara real-time.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('register') }}" class="btn-hero-primary">
                        <span>Mulai Sekarang</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="#how-it-works" class="btn-hero-secondary">
                        <i class="fas fa-play-circle"></i>
                        <span>Lihat Demo</span>
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="stat-number">50K+</div>
                        <div class="stat-label">Laporan Selesai</div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <div class="stat-number">95%</div>
                        <div class="stat-label">Kepuasan User</div>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Support Online</div>
                    </div>
                </div>
            </div>
            <div class="hero-image">
                <div class="floating-card card-1">
                    <i class="fas fa-check-circle"></i>
                    <div class="card-content">
                        <div class="card-title">Laporan Diterima</div>
                        <div class="card-desc">Ticket #12345</div>
                    </div>
                </div>
                <div class="floating-card card-2">
                    <i class="fas fa-clock"></i>
                    <div class="card-content">
                        <div class="card-title">Sedang Diproses</div>
                        <div class="card-desc">2 hari lagi</div>
                    </div>
                </div>
                <div class="floating-card card-3">
                    <i class="fas fa-trophy"></i>
                    <div class="card-content">
                        <div class="card-title">Selesai</div>
                        <div class="card-desc">5 menit lalu</div>
                    </div>
                </div>
                <div class="hero-illustration">
                    <div class="illustration-bg"></div>
                    <i class="fas fa-file-alt illustration-icon"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="section-container">
            <div class="section-header">
                <span class="section-badge">Features</span>
                <h2 class="section-title">Mengapa Memilih Platform Kami?</h2>
                <p class="section-description">Sistem pelaporan modern dengan fitur lengkap untuk kemudahan Anda</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon blue"><i class="fas fa-shield-check"></i></div>
                    <h3 class="feature-title">Keamanan Terjamin</h3>
                    <p class="feature-description">Data Anda terenkripsi dengan standar keamanan tinggi</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon green"><i class="fas fa-bolt"></i></div>
                    <h3 class="feature-title">Respon Cepat</h3>
                    <p class="feature-description">Laporan direspon maksimal 24 jam oleh tim kompeten</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon purple"><i class="fas fa-chart-line"></i></div>
                    <h3 class="feature-title">Tracking Real-time</h3>
                    <p class="feature-description">Pantau progress laporan dengan sistem tracking real-time</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon orange"><i class="fas fa-mobile-alt"></i></div>
                    <h3 class="feature-title">Mobile Friendly</h3>
                    <p class="feature-description">Akses dari mana saja melalui smartphone atau komputer</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon red"><i class="fas fa-bell"></i></div>
                    <h3 class="feature-title">Notifikasi Instant</h3>
                    <p class="feature-description">Update langsung setiap ada perubahan status laporan</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon cyan"><i class="fas fa-file-download"></i></div>
                    <h3 class="feature-title">Export Report</h3>
                    <p class="feature-description">Download laporan dalam format PDF atau Excel</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works" id="how-it-works">
        <div class="section-container">
            <div class="section-header">
                <span class="section-badge">Process</span>
                <h2 class="section-title">Cara Kerja Sistem</h2>
                <p class="section-description">Proses mudah dalam 4 langkah sederhana</p>
            </div>
            <div class="steps-container">
                <div class="step-item">
                    <div class="step-number">01</div>
                    <div class="step-icon"><i class="fas fa-user-plus"></i></div>
                    <h3 class="step-title">Daftar Akun</h3>
                    <p class="step-description">Buat akun gratis dengan mengisi data diri Anda</p>
                </div>
                <div class="step-connector"></div>
                <div class="step-item">
                    <div class="step-number">02</div>
                    <div class="step-icon"><i class="fas fa-edit"></i></div>
                    <h3 class="step-title">Buat Laporan</h3>
                    <p class="step-description">Isi form laporan dengan detail dan lampiran foto</p>
                </div>
                <div class="step-connector"></div>
                <div class="step-item">
                    <div class="step-number">03</div>
                    <div class="step-icon"><i class="fas fa-cogs"></i></div>
                    <h3 class="step-title">Proses Verifikasi</h3>
                    <p class="step-description">Tim kami akan verifikasi dan menindaklanjuti</p>
                </div>
                <div class="step-connector"></div>
                <div class="step-item">
                    <div class="step-number">04</div>
                    <div class="step-icon"><i class="fas fa-check-double"></i></div>
                    <h3 class="step-title">Selesai</h3>
                    <p class="step-description">Dapatkan notifikasi dan hasil penyelesaian</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section" id="stats">
        <div class="stats-bg"></div>
        <div class="section-container">
            <div class="stats-content">
                <div class="stats-left">
                    <h2 class="stats-title">Dipercaya oleh Ribuan Masyarakat</h2>
                    <p class="stats-description">Platform kami telah membantu menyelesaikan puluhan ribu laporan dengan tingkat kepuasan tinggi.</p>
                    <a href="{{ route('register') }}" class="btn-stats">
                        Bergabung Sekarang <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="stats-right">
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fas fa-users"></i></div>
                        <div class="stat-content">
                            <div class="stat-value">10,000+</div>
                            <div class="stat-text">Pengguna Aktif</div>
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
                        <div class="stat-content">
                            <div class="stat-value">50,000+</div>
                            <div class="stat-text">Laporan Selesai</div>
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fas fa-star"></i></div>
                        <div class="stat-content">
                            <div class="stat-value">95%</div>
                            <div class="stat-text">Tingkat Kepuasan</div>
                        </div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fas fa-clock"></i></div>
                        <div class="stat-content">
                            <div class="stat-value">24 Jam</div>
                            <div class="stat-text">Avg. Response Time</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="section-container">
            <div class="cta-card">
                <div class="cta-content">
                    <h2 class="cta-title">Siap Menyampaikan Aspirasi Anda?</h2>
                    <p class="cta-description">Bergabunglah dengan ribuan masyarakat yang telah mempercayai platform kami</p>
                    <div class="cta-actions">
                        <a href="{{ route('register') }}" class="btn-cta-primary">
                            Daftar Gratis <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="{{ route('login') }}" class="btn-cta-secondary">Sudah Punya Akun?</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="section-container">
            <div class="footer-content">
                <div class="footer-col">
                    <div class="footer-logo">
                        <div class="logo-icon"><i class="fas fa-shield-alt"></i></div>
                        <div class="logo-text">
                            <span class="logo-main">GovReport</span>
                            <span class="logo-sub">System</span>
                        </div>
                    </div>
                    <p class="footer-desc">Platform pelaporan dan pengaduan masyarakat yang aman, cepat, dan terpercaya.</p>
                </div>
                <div class="footer-col">
                    <h4 class="footer-title">Menu</h4>
                    <ul class="footer-links">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#how-it-works">How It Works</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4 class="footer-title">Contact</h4>
                    <ul class="footer-contact">
                        <li><i class="fas fa-envelope"></i> <span>support@govreport.id</span></li>
                        <li><i class="fas fa-phone"></i> <span>+62 21 1234 5678</span></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 GovReport System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/landing.js') }}"></script>
</body>
</html>
