# File Viewer & Download System

## Overview
Sistem file viewer memungkinkan admin, staff, dan kepala departemen untuk melihat dan mengunduh file/foto yang diupload oleh masyarakat dalam laporan dan keluhan.

## Features

### 🔐 **Access Control**
- **Admin**: Dapat melihat semua file dari semua laporan/keluhan
- **Kepala Departemen**: Dapat melihat file dari laporan/keluhan di departemen mereka
- **Staff**: Dapat melihat file dari laporan/keluhan yang ditugaskan kepada mereka
- **Masyarakat**: Tidak dapat mengakses file viewer (hanya dapat upload)

### 📁 **File Management**
- **View Files**: Galeri file dengan preview untuk gambar
- **Download Individual**: Unduh file satu per satu
- **Download All**: Unduh semua file dalam format ZIP
- **Preview Images**: Preview gambar langsung di browser
- **File Information**: Menampilkan ukuran file, tanggal upload, dll.

### 🛡️ **Security Features**
- **Role-based Access Control**: Hanya role tertentu yang bisa akses
- **File Validation**: Validasi tipe dan ukuran file
- **Secure Download**: File diunduh melalui sistem yang aman
- **Temporary File Cleanup**: File temporary dibersihkan otomatis

## Usage

### 1. **Melihat File dari Dashboard**
```php
// Di halaman admin/administration dashboard
<x-file-view-button :reportable="$report" type="report" />
<x-file-view-button :reportable="$complaint" type="complaint" />
```

### 2. **Akses File Viewer**
```
/files/report/{id}     - Lihat file laporan
/files/complaint/{id}  - Lihat file keluhan
```

### 3. **Download File**
```
/files/report/{id}/download/{filename}     - Download file laporan
/files/complaint/{id}/download/{filename} - Download file keluhan
/files/report/{id}/download-all           - Download semua file (ZIP)
```

### 4. **Preview Gambar**
```
/files/report/{id}/preview/{filename}     - Preview gambar laporan
/files/complaint/{id}/preview/{filename}  - Preview gambar keluhan
```

## File Types Supported

### **Images**
- JPG, JPEG
- PNG
- GIF
- WEBP

### **Documents**
- PDF
- DOC, DOCX
- XLS, XLSX
- ZIP

## Security Rules

### **Access Permissions**
```php
// Admin - dapat akses semua file
if ($user->role === 'admin') {
    return true;
}

// Kepala Departemen - hanya file di departemen mereka
if ($user->role === 'department_head' && $reportable->department_id === $user->department_id) {
    return true;
}

// Staff - hanya file yang ditugaskan kepada mereka
if ($user->role === 'staff' && $reportable->assigned_to === $user->id) {
    return true;
}
```

### **File Validation**
- **Size Limit**: Maksimal 5MB per file
- **Type Validation**: Hanya tipe file yang diizinkan
- **Security Check**: Pengecekan ekstensi berbahaya

## API Endpoints

### **View Files**
```http
GET /files/{type}/{id}
```
- `type`: `report` atau `complaint`
- `id`: ID laporan/keluhan

### **Download File**
```http
GET /files/{type}/{id}/download/{filename}
```

### **Preview Image**
```http
GET /files/{type}/{id}/preview/{filename}
```

### **Download All Files**
```http
GET /files/{type}/{id}/download-all
```

## Database Schema

### **File Storage**
```php
// File disimpan di storage/app/public/attachments/
'attachments' => [
    'attachments/reports/filename.jpg',
    'attachments/complaints/document.pdf'
]
```

### **File Metadata**
```php
$fileInfo = [
    'name' => 'filename.jpg',
    'path' => 'attachments/reports/filename.jpg',
    'size' => 1024000,
    'size_formatted' => '1.02 MB',
    'extension' => 'jpg',
    'mime_type' => 'image/jpeg',
    'is_image' => true,
    'created_at' => '2025-01-01 12:00:00'
];
```

## Maintenance

### **Cleanup Temporary Files**
```bash
# Manual cleanup
php artisan files:cleanup

# Automatic cleanup (setiap 6 jam)
# Sudah dikonfigurasi di Kernel.php
```

### **File Storage**
- **Upload Path**: `storage/app/public/attachments/`
- **Temp Path**: `storage/app/temp/`
- **Symlink**: Pastikan `storage/app/public` ter-link ke `public/storage`

## Error Handling

### **Common Errors**
- **403 Forbidden**: User tidak memiliki akses
- **404 Not Found**: File tidak ditemukan
- **400 Bad Request**: File bukan gambar (untuk preview)

### **Error Messages**
```php
'Unauthorized access to files.'
'File not found.'
'File is not an image.'
'No files to download.'
'Failed to create archive.'
```

## Best Practices

### **Performance**
- File preview menggunakan lazy loading
- ZIP download untuk multiple files
- Temporary file cleanup otomatis

### **Security**
- Validasi role sebelum akses file
- Sanitasi filename untuk download
- Rate limiting untuk download

### **User Experience**
- Preview gambar langsung di browser
- File information yang lengkap
- Responsive design untuk mobile

## Troubleshooting

### **File Tidak Bisa Diakses**
1. Cek permission user
2. Cek file path di storage
3. Cek symlink public/storage

### **Download Gagal**
1. Cek file exists
2. Cek permission storage
3. Cek disk space

### **Preview Gambar Tidak Muncul**
1. Cek file adalah gambar
2. Cek MIME type
3. Cek browser support
