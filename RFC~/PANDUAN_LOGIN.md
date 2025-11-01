# Panduan Login - Government FRC System

## 🔐 Sandi Default yang Tersedia

Sistem ini sudah memiliki beberapa akun default untuk testing. Berikut adalah daftar lengkapnya:

### 👑 **ADMIN ACCOUNT**
- **Email:** `admin@government.gov`
- **Password:** `admin123`
- **Role:** Admin
- **Akses:** Semua fitur admin (dashboard, laporan, keluhan, pengguna, departemen)

### 🏢 **DEPARTMENT HEAD ACCOUNTS**
- **Email:** `head.pwd@government.gov`
- **Password:** `head123`
- **Role:** Department Head
- **Akses:** Dashboard departemen, laporan departemen, keluhan departemen, staff

### 👥 **STAFF ACCOUNTS**
- **Email:** `staff1.pwd@government.gov`
- **Password:** `staff123`
- **Role:** Staff
- **Akses:** Dashboard departemen, laporan departemen, keluhan departemen

### 👤 **CITIZEN ACCOUNTS**
- **Email:** `citizen1@example.com`
- **Password:** `citizen123`
- **Role:** Citizen
- **Akses:** Dashboard warga, laporan saya, keluhan saya, buat laporan/keluhan

## 🚀 Cara Login:

1. **Buka browser** dan akses aplikasi
2. **Klik "Login"** di halaman utama
3. **Masukkan email dan password** sesuai role yang ingin Anda test
4. **Klik "Login"**
5. **Sistem akan otomatis redirect** ke dashboard sesuai role

## 🎯 Dashboard Berdasarkan Role:

### **Admin Dashboard** (`/admin/dashboard`)
- **URL:** `http://localhost:8000/admin/dashboard`
- **Fitur:** Statistik lengkap, manajemen semua laporan/keluhan, pengguna, departemen
- **Menu:** Dashboard, Laporan, Keluhan, Pengguna, Departemen

### **Administration Dashboard** (`/administration/dashboard`)
- **URL:** `http://localhost:8000/administration/dashboard`
- **Fitur:** Dashboard departemen, laporan departemen, keluhan departemen, staff
- **Menu:** Dashboard, Laporan, Keluhan, Staff

### **Citizen Dashboard** (`/citizen/dashboard`)
- **URL:** `http://localhost:8000/citizen/dashboard`
- **Fitur:** Dashboard pribadi, laporan saya, keluhan saya, buat laporan/keluhan
- **Menu:** Dashboard, Laporan Saya, Keluhan Saya

## 🔑 Sandi Default Lengkap:

| Role | Email | Password | Akses |
|------|-------|----------|-------|
| **Admin** | `admin@government.gov` | `admin123` | Semua fitur |
| **Department Head** | `head.pwd@government.gov` | `head123` | Departemen |
| **Staff** | `staff1.pwd@government.gov` | `staff123` | Departemen |
| **Citizen** | `citizen1@example.com` | `citizen123` | Warga |

## 🎨 Fitur yang Dapat Ditest:

### **Sebagai Admin:**
- Lihat statistik lengkap sistem
- Kelola semua laporan dan keluhan
- Kelola pengguna dan departemen
- Assign laporan/keluhan ke staff

### **Sebagai Department Head/Staff:**
- Lihat statistik departemen
- Kelola laporan dan keluhan departemen
- Assign laporan/keluhan ke staff
- Lihat daftar staff departemen

### **Sebagai Citizen:**
- Buat laporan baru
- Ajukan keluhan
- Lihat status laporan/keluhan
- Melacak progress penanganan

## 🛠️ Troubleshooting:

**Jika tidak bisa login:**
1. Pastikan email dan password benar
2. Cek apakah user sudah ada di database
3. Jalankan: `php artisan db:seed --class=UserSeeder`

**Jika halaman kosong setelah login:**
1. Pastikan role user sudah benar
2. Cek middleware sudah terdaftar
3. Lihat log error di `storage/logs/laravel.log`

**Jika redirect tidak berfungsi:**
1. Pastikan route sudah terdaftar
2. Cek controller sudah ada
3. Pastikan view sudah dibuat

## 🔄 Reset Database (Jika Perlu):

```bash
# Hapus semua data dan jalankan seeder ulang
php artisan migrate:fresh --seed
```

## 📱 URL Login:

- **Halaman Login:** `http://localhost:8000/login`
- **Halaman Register:** `http://localhost:8000/register`
- **Home (Redirect ke Login):** `http://localhost:8000/`

## 🎯 Tips Penggunaan:

1. **Gunakan akun Admin** untuk melihat semua fitur sistem
2. **Gunakan akun Citizen** untuk test fitur warga
3. **Gunakan akun Department Head/Staff** untuk test fitur departemen
4. **Setiap role memiliki akses terbatas** sesuai fungsinya

Sekarang Anda dapat login dengan mudah menggunakan sandi default yang telah disediakan!
