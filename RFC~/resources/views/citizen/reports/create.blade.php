@extends('layouts.dashboard')

@section('title', 'Buat Laporan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-file-alt me-2"></i>Buat Laporan Baru
            </h1>
            <a href="{{ route('citizen.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form Laporan</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('citizen.reports.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">Judul Laporan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" 
                                    id="category" name="category" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Infrastruktur" {{ old('category') == 'Infrastruktur' ? 'selected' : '' }}>Infrastruktur</option>
                                <option value="Lingkungan" {{ old('category') == 'Lingkungan' ? 'selected' : '' }}>Lingkungan</option>
                                <option value="Keamanan" {{ old('category') == 'Keamanan' ? 'selected' : '' }}>Keamanan</option>
                                <option value="Kesehatan" {{ old('category') == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                                <option value="Pendidikan" {{ old('category') == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                                <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="department_id" class="form-label">Departemen <span class="text-danger">*</span></label>
                            <select class="form-select @error('department_id') is-invalid @enderror" 
                                    id="department_id" name="department_id" required>
                                <option value="">Pilih Departemen</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }} ({{ $department->code }})
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="priority" class="form-label">Prioritas <span class="text-danger">*</span></label>
                            <select class="form-select @error('priority') is-invalid @enderror" 
                                    id="priority" name="priority" required>
                                <option value="">Pilih Prioritas</option>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Rendah</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Sedang</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>Tinggi</option>
                                <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Mendesak</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="location" class="form-label">Lokasi</label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror" 
                               id="location" name="location" value="{{ old('location') }}" 
                               placeholder="Contoh: Jl. Sudirman No. 123, Jakarta">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi Lengkap <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="6" required 
                                  placeholder="Jelaskan secara detail masalah yang Anda temukan...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="attachments" class="form-label">Lampiran (foto/berkas) <small class="text-muted">(opsional, maksimal 5MB/berkas)</small></label>
                        <input type="file" class="form-control @error('attachments.*') is-invalid @enderror" id="attachments" name="attachments[]" multiple accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx,.xls,.xlsx,.zip">
                        @error('attachments.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Anda dapat mengunggah beberapa berkas sekaligus.</div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('citizen.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-1"></i>Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Panduan</h6>
            </div>
            <div class="card-body">
                <h6>Tips Membuat Laporan yang Baik:</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Judul Jelas:</strong> Gunakan judul yang menggambarkan masalah secara singkat
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Deskripsi Detail:</strong> Jelaskan masalah secara lengkap dan jelas
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Lokasi Akurat:</strong> Berikan alamat atau lokasi yang tepat
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Prioritas Tepat:</strong> Pilih prioritas sesuai tingkat urgensi
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Departemen Sesuai:</strong> Pilih departemen yang menangani masalah tersebut
                    </li>
                </ul>
                
                <hr>
                
                <h6>Kategori Laporan:</h6>
                <ul class="list-unstyled small">
                    <li><strong>Infrastruktur:</strong> Jalan, jembatan, drainase</li>
                    <li><strong>Lingkungan:</strong> Sampah, polusi, kebersihan</li>
                    <li><strong>Keamanan:</strong> Keamanan publik, lalu lintas</li>
                    <li><strong>Kesehatan:</strong> Fasilitas kesehatan, sanitasi</li>
                    <li><strong>Pendidikan:</strong> Sekolah, fasilitas pendidikan</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

