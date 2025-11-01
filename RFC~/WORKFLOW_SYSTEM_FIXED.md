# Sistem Workflow yang Diperbaiki

## Ringkasan Perbaikan

Sistem workflow telah diperbaiki sesuai dengan kebutuhan yang diminta. Berikut adalah perbaikan yang telah dilakukan:

## 1. Alur Workflow yang Benar

### Alur Lengkap:
1. **Citizen** → Membuat laporan/keluhan
2. **Admin** → Menerima laporan, dapat mengirim ke Staff atau Department Head
3. **Staff** → Menerima laporan, mengkonfirmasi, dan mengirim ke Department Head
4. **Department Head** → Review laporan, dapat mengembalikan ke Staff
5. **Staff** → Menyelesaikan dan mengirim konfirmasi ke Admin
6. **Admin** → Approve/Reject laporan, menutup laporan
7. **Citizen** → Menerima notifikasi bahwa laporan telah ditangani

## 2. Perbaikan yang Dilakukan

### A. Admin Dashboard Controller
- ✅ Memperbaiki fungsi `assignReport()` untuk menggunakan WorkflowService
- ✅ Memperbaiki fungsi `sendReportToHead()` untuk menggunakan WorkflowService
- ✅ Menambahkan validasi yang proper

### B. File Access Control
- ✅ Memperbaiki `FilePolicy` untuk memberikan akses yang lebih luas ke staff
- ✅ Staff dapat mengakses file jika:
  - Laporan ada di departemen mereka, ATAU
  - Laporan ditugaskan kepada mereka, ATAU
  - Laporan dalam status submitted/pending/verified di departemen mereka

### C. Workflow Management Controller (Baru)
- ✅ `adminAssignToStaff()` - Admin assign ke staff
- ✅ `adminAssignToHead()` - Admin assign ke department head
- ✅ `staffConfirmAndForward()` - Staff konfirmasi dan kirim ke head
- ✅ `headReviewAndReturn()` - Head review dan kembalikan ke staff
- ✅ `staffConfirmToAdmin()` - Staff selesaikan dan kirim ke admin
- ✅ `adminApproveAndClose()` - Admin approve dan tutup laporan
- ✅ `adminRejectToStaff()` - Admin reject dan kembalikan ke staff

### D. Routes yang Ditambahkan
- ✅ `/workflow/reports/{id}/admin-assign-staff`
- ✅ `/workflow/reports/{id}/admin-assign-head`
- ✅ `/workflow/reports/{id}/staff-confirm-forward`
- ✅ `/workflow/reports/{id}/head-review-return`
- ✅ `/workflow/reports/{id}/staff-confirm-admin`
- ✅ `/workflow/reports/{id}/admin-approve-close`
- ✅ `/workflow/reports/{id}/admin-reject-staff`

### E. Komponen UI (Baru)
- ✅ `workflow-buttons.blade.php` - Komponen tombol workflow
- ✅ Modal untuk setiap aksi workflow:
  - `assign-staff.blade.php`
  - `assign-head.blade.php`
  - `approve-report.blade.php`
  - `reject-report.blade.php`
  - `confirm-forward.blade.php`
  - `complete-report.blade.php`
  - `review-return.blade.php`

### F. Nomor Tiket
- ✅ Format nomor tiket diperbaiki: `RPT-YYYYMMDD-XXXXXX`
- ✅ Nomor tiket otomatis dibuat saat laporan dibuat
- ✅ Ditampilkan di tabel admin dan administration

### G. View yang Diperbaiki
- ✅ Admin reports view menggunakan komponen workflow buttons
- ✅ Administration reports view menggunakan komponen workflow buttons
- ✅ Menambahkan kolom nomor tiket di tabel
- ✅ Menambahkan tombol download untuk staff

## 3. Status Laporan

### Status yang Tersedia:
- `submitted` - Baru dikirim oleh citizen
- `pending` - Menunggu konfirmasi
- `verified` - Dikonfirmasi oleh staff
- `assigned` - Ditugaskan ke staff/head
- `in_progress` - Sedang dikerjakan
- `reviewed` - Direview oleh head
- `awaiting_admin_approval` - Menunggu persetujuan admin
- `needs_revision` - Perlu revisi
- `resolved` - Selesai dan ditutup

## 4. Fitur Admin

### Admin dapat:
1. **Memantau Grafik Laporan** - Dashboard dengan statistik lengkap
2. **Jenis Laporan** - Filter berdasarkan kategori dan prioritas
3. **Pergerakan Website** - Monitoring real-time
4. **Semua Sistem** - Akses penuh ke semua fitur

### Admin Dashboard Features:
- ✅ Statistik umum (total users, reports, complaints, departments)
- ✅ Statistik berdasarkan departemen
- ✅ Laporan terbaru
- ✅ Keluhan terbaru
- ✅ Statistik bulanan
- ✅ Monitoring sistem
- ✅ Performance departemen
- ✅ SLA monitoring

## 5. Cara Penggunaan

### Untuk Admin:
1. Login sebagai admin
2. Lihat laporan di dashboard admin
3. Klik tombol "Assign ke Staff" atau "Kirim ke Head"
4. Pilih staff/head yang sesuai
5. Tambahkan catatan jika perlu
6. Submit

### Untuk Staff:
1. Login sebagai staff
2. Lihat laporan yang ditugaskan di dashboard administration
3. Klik "Konfirmasi & Kirim ke Head" untuk laporan baru
4. Klik "Selesaikan & Kirim ke Admin" untuk laporan yang sudah dikerjakan

### Untuk Department Head:
1. Login sebagai department head
2. Lihat laporan yang ditugaskan
3. Klik "Review & Kembalikan ke Staff" untuk mengembalikan ke staff

## 6. Notifikasi

Sistem akan mengirim notifikasi otomatis untuk:
- ✅ Laporan baru dikirim
- ✅ Laporan ditugaskan
- ✅ Status laporan berubah
- ✅ SLA breach
- ✅ Komentar ditambahkan

## 7. File Access

### Staff dapat:
- ✅ Download laporan dalam format ZIP atau JSON
- ✅ Lihat file attachments
- ✅ Preview gambar
- ✅ Download file individual

## 8. Testing

Untuk menguji sistem:
1. Buat akun dengan role yang berbeda (admin, staff, department_head, citizen)
2. Login sebagai citizen dan buat laporan
3. Login sebagai admin dan assign laporan
4. Login sebagai staff dan proses laporan
5. Login sebagai department head dan review laporan
6. Kembali ke admin untuk approve/close

## 9. Troubleshooting

### Jika masih ada error:
1. Pastikan semua route sudah terdaftar
2. Pastikan middleware sudah benar
3. Pastikan policy sudah terdaftar di AuthServiceProvider
4. Clear cache: `php artisan config:clear && php artisan route:clear && php artisan view:clear`

## 10. File yang Dimodifikasi

### Controller:
- `app/Http/Controllers/AdminDashboardController.php`
- `app/Http/Controllers/WorkflowManagementController.php` (baru)

### Policy:
- `app/Policies/FilePolicy.php`

### Model:
- `app/Models/Report.php`

### Routes:
- `routes/web.php`

### Views:
- `resources/views/admin/reports.blade.php`
- `resources/views/administration/reports.blade.php`
- `resources/views/components/workflow-buttons.blade.php` (baru)
- `resources/views/components/modals/` (semua file modal baru)

Sistem workflow sekarang sudah berfungsi dengan baik sesuai dengan alur yang diminta!
