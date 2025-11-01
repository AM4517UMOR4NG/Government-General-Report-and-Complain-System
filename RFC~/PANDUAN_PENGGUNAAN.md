# Panduan Penggunaan Government FRC System

## Cara Mengakses Sistem

### 1. Login ke Sistem
- Buka browser dan akses aplikasi
- Klik tombol "Login" di halaman utama
- Masukkan email dan password yang telah didaftarkan
- Klik "Login"

### 2. Dashboard Berdasarkan Role

#### A. Admin Dashboard
**URL:** `/admin/dashboard`
**Fitur yang tersedia:**
- Dashboard utama dengan statistik lengkap
- Manajemen Laporan (`/admin/reports`)
- Manajemen Keluhan (`/admin/complaints`)
- Manajemen Pengguna (`/admin/users`)
- Manajemen Departemen (`/admin/departments`)

**Menu Navigasi:**
- Dashboard
- Laporan
- Keluhan
- Pengguna
- Departemen

#### B. Administration Dashboard (Department Head & Staff)
**URL:** `/administration/dashboard`
**Fitur yang tersedia:**
- Dashboard departemen dengan statistik
- Laporan Departemen (`/administration/reports`)
- Keluhan Departemen (`/administration/complaints`)
- Staff Departemen (`/administration/staff`)

**Menu Navigasi:**
- Dashboard
- Laporan
- Keluhan
- Staff

#### C. Citizen Dashboard
**URL:** `/citizen/dashboard`
**Fitur yang tersedia:**
- Dashboard pribadi dengan statistik laporan/keluhan
- Laporan Saya (`/citizen/reports`)
- Keluhan Saya (`/citizen/complaints`)
- Buat Laporan Baru (`/citizen/reports/create`)
- Ajukan Keluhan Baru (`/citizen/complaints/create`)

**Menu Navigasi:**
- Dashboard
- Laporan Saya
- Keluhan Saya

### 3. Cara Menggunakan Fitur

#### Untuk Warga (Citizen):

**Membuat Laporan:**
1. Login sebagai citizen
2. Klik "Buat Laporan" di dashboard atau menu "Laporan Saya"
3. Isi form laporan:
   - Judul laporan
   - Deskripsi masalah
   - Kategori
   - Departemen terkait
   - Lokasi (opsional)
   - Prioritas
4. Klik "Kirim Laporan"

**Mengajukan Keluhan:**
1. Login sebagai citizen
2. Klik "Ajukan Keluhan" di dashboard atau menu "Keluhan Saya"
3. Isi form keluhan:
   - Judul keluhan
   - Deskripsi keluhan
   - Kategori
   - Departemen terkait
   - Lokasi (opsional)
   - Prioritas
4. Klik "Kirim Keluhan"

**Melacak Status:**
- Lihat status laporan/keluhan di dashboard
- Klik "Laporan Saya" atau "Keluhan Saya" untuk detail lengkap
- Status: Pending, In Progress, Resolved

#### Untuk Admin:

**Mengelola Laporan:**
1. Akses `/admin/reports`
2. Lihat daftar semua laporan
3. Klik tombol "Lihat" untuk detail
4. Klik "Tugaskan" untuk menugaskan ke staff

**Mengelola Keluhan:**
1. Akses `/admin/complaints`
2. Lihat daftar semua keluhan
3. Klik tombol "Lihat" untuk detail
4. Klik "Tugaskan" untuk menugaskan ke staff

**Mengelola Pengguna:**
1. Akses `/admin/users`
2. Lihat daftar semua pengguna
3. Klik "Lihat" untuk detail pengguna
4. Klik "Edit" untuk mengubah informasi

**Mengelola Departemen:**
1. Akses `/admin/departments`
2. Lihat daftar semua departemen
3. Klik "Lihat" untuk detail departemen
4. Klik "Edit" untuk mengubah informasi

#### Untuk Department Head & Staff:

**Mengelola Laporan Departemen:**
1. Akses `/administration/reports`
2. Lihat laporan yang ditujukan ke departemen Anda
3. Klik "Lihat" untuk detail laporan
4. Klik "Tugaskan" untuk menugaskan ke staff

**Mengelola Keluhan Departemen:**
1. Akses `/administration/complaints`
2. Lihat keluhan yang ditujukan ke departemen Anda
3. Klik "Lihat" untuk detail keluhan
4. Klik "Tugaskan" untuk menugaskan ke staff

**Mengelola Staff:**
1. Akses `/administration/staff`
2. Lihat daftar staff di departemen Anda
3. Klik "Lihat" untuk detail staff

### 4. Troubleshooting

**Jika halaman kosong:**
1. Pastikan Anda sudah login
2. Periksa role Anda (admin, department_head, staff, citizen)
3. Pastikan middleware sudah terdaftar dengan benar
4. Cek log error di `storage/logs/laravel.log`

**Jika tidak bisa akses halaman:**
1. Pastikan role Anda sesuai dengan halaman yang diakses
2. Admin: hanya bisa akses `/admin/*`
3. Department Head/Staff: hanya bisa akses `/administration/*`
4. Citizen: hanya bisa akses `/citizen/*`

**Jika menu tidak muncul:**
1. Pastikan layout dashboard sudah benar
2. Periksa kondisi role di navbar
3. Pastikan user sudah memiliki role yang valid

### 5. URL Lengkap Sistem

**Admin:**
- Dashboard: `/admin/dashboard`
- Laporan: `/admin/reports`
- Keluhan: `/admin/complaints`
- Pengguna: `/admin/users`
- Departemen: `/admin/departments`

**Administration:**
- Dashboard: `/administration/dashboard`
- Laporan: `/administration/reports`
- Keluhan: `/administration/complaints`
- Staff: `/administration/staff`

**Citizen:**
- Dashboard: `/citizen/dashboard`
- Laporan Saya: `/citizen/reports`
- Keluhan Saya: `/citizen/complaints`
- Buat Laporan: `/citizen/reports/create`
- Ajukan Keluhan: `/citizen/complaints/create`

### 6. Tips Penggunaan

1. **Untuk Warga:** Gunakan fitur "Buat Laporan" untuk melaporkan masalah infrastruktur, dan "Ajukan Keluhan" untuk menyampaikan ketidakpuasan terhadap pelayanan.

2. **Untuk Admin:** Monitor statistik di dashboard untuk melihat performa sistem secara keseluruhan.

3. **Untuk Department Head:** Fokus pada laporan dan keluhan yang ditujukan ke departemen Anda.

4. **Untuk Staff:** Kerjakan laporan dan keluhan yang telah ditugaskan kepada Anda.

5. **Status Tracking:** Selalu periksa status laporan/keluhan untuk mengetahui progress penanganan.

### 7. Kontak Support

Jika mengalami masalah teknis, silakan hubungi administrator sistem atau cek log error di `storage/logs/laravel.log`.
