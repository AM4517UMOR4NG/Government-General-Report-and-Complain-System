# Modern Dashboard System - Government FRC

## 🎨 Overview

Dashboard modern yang telah dibuat mengikuti desain referensi dengan ciri khas:
- **Header banner biru** dengan gradasi dan gambar pemerintahan sebagai background
- **Layout card-based** yang clean dan tertata rapi
- **Sidebar navigasi** yang modern dengan user info dan menu yang jelas
- **Statistik cards** dengan ikon dan warna yang menarik
- **Feature cards** dengan deskripsi lengkap dan aksi yang jelas
- **Responsive design** yang beradaptasi dengan berbagai ukuran layar
- **Animasi smooth** untuk pengalaman pengguna yang lebih baik

## 📁 File Structure

### Layout Files
- `resources/views/layouts/modern-dashboard.blade.php` - Layout utama dashboard modern
- `resources/views/layouts/dashboard.blade.php` - Layout lama (masih tersedia)

### Dashboard Views
- `resources/views/admin/modern-dashboard.blade.php` - Dashboard Admin
- `resources/views/administration/modern-dashboard.blade.php` - Dashboard Department Head & Staff
- `resources/views/citizen/modern-dashboard.blade.php` - Dashboard Citizen

### Controllers Updated
- `app/Http/Controllers/AdminDashboardController.php` - Menggunakan view `admin.modern-dashboard`
- `app/Http/Controllers/AdministrationDashboardController.php` - Menggunakan view `administration.modern-dashboard`
- `app/Http/Controllers/CitizenDashboardController.php` - Menggunakan view `citizen.modern-dashboard`

## 🎯 Features per Role

### 👨‍💼 Admin Dashboard
**Header:** "Admin Dashboard - Kelola sistem laporan dan keluhan pemerintah secara terpusat"

**Statistics Cards:**
- Total Laporan
- Total Keluhan  
- Total Pengguna
- Laporan Pending

**Feature Cards:**
1. **Semua Laporan & Publikasi**
   - Laporan terbaru (3 teratas)
   - Status overview (Pending, In Progress, Resolved)
   - Actions: Lihat Semua Laporan, Monitoring

2. **Rencana Kerja**
   - Departemen aktif (4 teratas)
   - Aktivitas terbaru (timeline)
   - Actions: Kelola Departemen, Kelola Pengguna

3. **Manajemen Sistem**
   - Status sistem (Server, Database, Storage)
   - Alerts & Notifications (SLA breach, due soon)
   - Actions: System Monitoring, Settings

4. **Aksi Cepat**
   - Buat Laporan Manual
   - Tambah Pengguna
   - Export Data
   - Broadcast Notification
   - Action: Mulai Sekarang

### 🏢 Department Head Dashboard
**Header:** "Department Head Dashboard - Kelola laporan dan staff di departemen [Nama Departemen]"

**Statistics Cards:**
- Laporan Departemen
- Keluhan Departemen
- Menunggu Tindakan
- Diselesaikan

**Feature Cards:**
1. **Laporan Masyarakat**
   - Laporan terbaru dengan status badge
   - Status overview departemen
   - Actions: Lihat Semua Laporan

2. **Manajemen Tim**
   - Staff departemen dengan avatar
   - Aktivitas hari ini (timeline)
   - Actions: Kelola Staff, Lihat Progress

3. **Keluhan & Feedback**
   - Keluhan terbaru dengan status
   - Status keluhan overview
   - Actions: Lihat Semua Keluhan

4. **Aksi Cepat**
   - Assign Laporan
   - Teruskan ke Atasan
   - Export Laporan
   - Notifikasi Urgent
   - Action: Mulai Bekerja

### 👨‍💻 Staff Dashboard
**Header:** "Staff Dashboard - Tangani laporan masyarakat dan keluhan di departemen [Nama Departemen]"

**Statistics Cards:**
- Laporan Saya
- Keluhan Saya
- Menunggu Tindakan
- Diselesaikan

**Feature Cards:**
1. **Laporan Masyarakat**
   - Laporan terbaru yang ditugaskan
   - Status overview
   - Actions: Lihat Semua Laporan, Tugas Saya

2. **Alur Kerja**
   - Workflow steps (3 langkah):
     1. Terima Laporan
     2. Konfirmasi
     3. Teruskan
   - Aktivitas hari ini
   - Actions: Lihat Progress

3. **Keluhan & Feedback**
   - Keluhan yang ditangani
   - Status keluhan
   - Actions: Lihat Semua Keluhan

4. **Aksi Cepat**
   - Konfirmasi Laporan
   - Teruskan ke Atasan
   - Export Laporan
   - Notifikasi Urgent
   - Action: Mulai Bekerja

### 👨‍👩‍👧‍👦 Citizen Dashboard
**Header:** "Dashboard Warga - Selamat datang di sistem pelaporan dan keluhan pemerintah"

**Statistics Cards:**
- Laporan Saya
- Keluhan Saya
- Sedang Diproses
- Selesai

**Feature Cards:**
1. **Buat Laporan Baru**
   - Jenis laporan dengan ikon:
     - Infrastruktur (jalan, jembatan)
     - Lingkungan (kebersihan, polusi)
     - Keamanan (ketertiban, kriminalitas)
     - Pelayanan (administrasi, birokrasi)
   - Actions: Buat Laporan, Buat Keluhan

2. **Laporan Saya**
   - Laporan terbaru dengan status dan departemen
   - Timeline dan assigned user
   - Actions: Lihat Semua

3. **Keluhan Saya**
   - Keluhan terbaru dengan status
   - Departemen dan timeline
   - Actions: Lihat Semua

4. **Panduan & Bantuan**
   - Help items dengan ikon:
     - Panduan Penggunaan
     - Tips & Trik
     - Kontak Bantuan
     - Status & Timeline
   - Actions: Baca Panduan, Hubungi Support

**Additional Feature:**
5. **Progress Tracking** (jika ada laporan)
   - Progress bar untuk setiap laporan
   - Status dan assigned user
   - Timeline creation dan handling

## 🎨 Design Elements

### Color Scheme
```css
:root {
    --primary-blue: #1e40af;
    --secondary-blue: #3b82f6;
    --light-blue: #dbeafe;
    --dark-navy: #1e293b;
    --text-dark: #334155;
    --text-light: #64748b;
    --bg-light: #f8fafc;
    --white: #ffffff;
    --border-light: #e2e8f0;
}
```

### Icon Colors
- **Government Icon:** Blue gradient (#0ea5e9 to #0284c7)
- **Report Icon:** Green gradient (#10b981 to #059669)
- **Complaint Icon:** Orange gradient (#f59e0b to #d97706)
- **User Icon:** Purple gradient (#8b5cf6 to #7c3aed)
- **Alert Icon:** Red gradient (#ef4444 to #dc2626)

### Typography
- **Font Family:** 'Inter', sans-serif
- **Page Title:** 2rem, font-weight: 700
- **Feature Title:** 1.25rem, font-weight: 600
- **Stat Number:** 2.5rem, font-weight: 700

### Animations
- **Fade In Up:** Cards muncul dengan animasi dari bawah
- **Hover Effects:** Transform translateY(-4px) pada cards
- **Button Hover:** Transform translateY(-2px) dengan shadow

## 📱 Responsive Design

### Desktop (>1024px)
- Sidebar fixed 280px width
- Main content dengan margin-left 280px
- Stats grid: auto-fit minmax(280px, 1fr)
- Feature grid: auto-fit minmax(350px, 1fr)

### Tablet (768px - 1024px)
- Sidebar hidden (transform: translateX(-100%))
- Main content full width
- Stats grid: auto-fit minmax(250px, 1fr)
- Feature grid: 1fr (single column)

### Mobile (<768px)
- Main content padding: 1rem
- Page header padding: 1.5rem
- Page title: 1.5rem
- Stats grid: 1fr (single column)

## 🔧 Implementation Notes

### Background Images
- Header banner menggunakan gambar pemerintahan sebagai background dengan opacity 0.1
- Page header menggunakan gambar yang sama di sisi kanan dengan opacity 0.05

### Navigation
- Active state dengan gradient background dan white text
- Hover effects dengan light blue background dan primary blue text
- Transform translateX(4px) pada hover

### Cards
- Box shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05) default
- Hover shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1)
- Border radius: 12px
- Border: 1px solid var(--border-light)

### Status Badges
- Submitted/Pending: bg-warning (orange)
- Verified: bg-primary (blue) dengan text "Dikonfirmasi"
- In Progress/Assigned: bg-info (light blue)
- Resolved: bg-success (green)

## 🚀 Usage

Untuk menggunakan dashboard modern:

1. **Admin:** Akses `/admin/dashboard` - otomatis menggunakan `admin.modern-dashboard`
2. **Department Head/Staff:** Akses `/administration/dashboard` - otomatis menggunakan `administration.modern-dashboard`  
3. **Citizen:** Akses `/citizen/dashboard` - otomatis menggunakan `citizen.modern-dashboard`

Dashboard lama masih tersedia jika diperlukan dengan mengubah view name di controller kembali ke view yang lama.

## 📊 Data Requirements

Setiap dashboard memerlukan data statistik yang dihitung di controller:
- Total counts untuk berbagai entitas
- Recent items (laporan, keluhan terbaru)
- Status breakdowns
- Timeline data (today's activities)
- Department/staff information
- SLA breach notifications (untuk admin)

Semua data sudah diimplementasikan di controller masing-masing dengan query yang efisien.
