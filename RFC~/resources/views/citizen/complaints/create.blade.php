@extends('layouts.dashboard')

@section('title', 'Ajukan Keluhan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-exclamation-triangle me-2"></i>Ajukan Keluhan Baru
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
                <h6 class="m-0 font-weight-bold text-primary">Form Keluhan</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('citizen.complaints.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">Judul Keluhan <span class="text-danger">*</span></label>
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
                                <option value="Pelayanan Publik" {{ old('category') == 'Pelayanan Publik' ? 'selected' : '' }}>Pelayanan Publik</option>
                                <option value="Birokrasi" {{ old('category') == 'Birokrasi' ? 'selected' : '' }}>Birokrasi</option>
                                <option value="Korupsi" {{ old('category') == 'Korupsi' ? 'selected' : '' }}>Korupsi</option>
                                <option value="Diskriminasi" {{ old('category') == 'Diskriminasi' ? 'selected' : '' }}>Diskriminasi</option>
                                <option value="Pelanggaran Hukum" {{ old('category') == 'Pelanggaran Hukum' ? 'selected' : '' }}>Pelanggaran Hukum</option>
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
                        <label for="location" class="form-label">Lokasi Kejadian</label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror" 
                               id="location" name="location" value="{{ old('location') }}" 
                               placeholder="Contoh: Kantor Kelurahan ABC, Jakarta">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi Keluhan <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="6" required 
                                  placeholder="Jelaskan secara detail keluhan Anda, termasuk kronologi kejadian, pihak yang terlibat, dan dampak yang dirasakan...">{{ old('description') }}</textarea>
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
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-paper-plane me-1"></i>Ajukan Keluhan
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
                <h6>Tips Mengajukan Keluhan yang Efektif:</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Faktual:</strong> Sampaikan fakta yang benar dan dapat diverifikasi
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Kronologis:</strong> Urutkan kejadian secara kronologis
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Spesifik:</strong> Sebutkan nama, waktu, dan tempat secara spesifik
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Objektif:</strong> Hindari emosi berlebihan, fokus pada fakta
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        <strong>Konstruktif:</strong> Berikan saran solusi jika memungkinkan
                    </li>
                </ul>
                
                <hr>
                
                <h6>Kategori Keluhan:</h6>
                <ul class="list-unstyled small">
                    <li><strong>Pelayanan Publik:</strong> Kualitas pelayanan pemerintah</li>
                    <li><strong>Birokrasi:</strong> Prosedur yang berbelit-belit</li>
                    <li><strong>Korupsi:</strong> Penyalahgunaan wewenang</li>
                    <li><strong>Diskriminasi:</strong> Perlakuan tidak adil</li>
                    <li><strong>Pelanggaran Hukum:</strong> Pelanggaran aturan/undang-undang</li>
                </ul>
                
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Catatan:</strong> Keluhan Anda akan ditangani secara serius dan rahasia. 
                    Identitas Anda akan dilindungi sesuai dengan ketentuan yang berlaku.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

