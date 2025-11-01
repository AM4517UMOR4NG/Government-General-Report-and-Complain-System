@extends('layouts.dashboard')

@section('title', 'Edit Laporan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit me-2"></i>Edit Laporan #{{ $report->id }}
            </h1>
            <a href="{{ route('admin.reports') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form Edit Laporan</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reports.update', $report->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">Judul Laporan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $report->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" 
                                    id="category" name="category" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Infrastruktur" {{ old('category', $report->category) == 'Infrastruktur' ? 'selected' : '' }}>Infrastruktur</option>
                                <option value="Lingkungan" {{ old('category', $report->category) == 'Lingkungan' ? 'selected' : '' }}>Lingkungan</option>
                                <option value="Keamanan" {{ old('category', $report->category) == 'Keamanan' ? 'selected' : '' }}>Keamanan</option>
                                <option value="Kesehatan" {{ old('category', $report->category) == 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                                <option value="Pendidikan" {{ old('category', $report->category) == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                                <option value="Lainnya" {{ old('category', $report->category) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                                    <option value="{{ $department->id }}" {{ old('department_id', $report->department_id) == $department->id ? 'selected' : '' }}>
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
                                <option value="low" {{ old('priority', $report->priority) == 'low' ? 'selected' : '' }}>Rendah</option>
                                <option value="medium" {{ old('priority', $report->priority) == 'medium' ? 'selected' : '' }}>Sedang</option>
                                <option value="high" {{ old('priority', $report->priority) == 'high' ? 'selected' : '' }}>Tinggi</option>
                                <option value="urgent" {{ old('priority', $report->priority) == 'urgent' ? 'selected' : '' }}>Mendesak</option>
                            </select>
                            @error('priority')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="pending" {{ old('status', $report->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ old('status', $report->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="in_progress" {{ old('status', $report->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ old('status', $report->status) == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="assigned_to" class="form-label">Ditugaskan ke</label>
                            <select class="form-select @error('assigned_to') is-invalid @enderror" 
                                    id="assigned_to" name="assigned_to">
                                <option value="">Pilih Staff</option>
                                @foreach($staff as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to', $report->assigned_to) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('assigned_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="location" class="form-label">Lokasi</label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror" 
                               id="location" name="location" value="{{ old('location', $report->location) }}" 
                               placeholder="Contoh: Jl. Sudirman No. 123, Jakarta">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi Lengkap <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="6" required 
                                  placeholder="Jelaskan secara detail masalah yang ditemukan...">{{ old('description', $report->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.reports') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Informasi Laporan</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>ID Laporan:</strong><br>
                    <span class="text-muted">#{{ $report->id }}</span>
                </div>
                
                <div class="mb-3">
                    <strong>Dibuat oleh:</strong><br>
                    <span class="text-muted">{{ $report->user->name }}</span><br>
                    <small class="text-muted">{{ $report->user->email }}</small>
                </div>
                
                <div class="mb-3">
                    <strong>Tanggal Dibuat:</strong><br>
                    <span class="text-muted">{{ $report->created_at->format('d F Y, H:i') }}</span>
                </div>
                
                <div class="mb-3">
                    <strong>Terakhir Diupdate:</strong><br>
                    <span class="text-muted">{{ $report->updated_at->format('d F Y, H:i') }}</span>
                </div>
                
                @if($report->assignedUser)
                <div class="mb-3">
                    <strong>Ditugaskan ke:</strong><br>
                    <span class="text-muted">{{ $report->assignedUser->name }}</span><br>
                    <small class="text-muted">{{ $report->assignedUser->email }}</small>
                </div>
                @endif
                
                @if($report->attachments && count($report->attachments) > 0)
                <div class="mb-3">
                    <strong>Lampiran:</strong><br>
                    @foreach($report->attachments as $attachment)
                        <small class="text-muted">
                            <i class="fas fa-paperclip me-1"></i>
                            {{ basename($attachment) }}
                        </small><br>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        
        <div class="card shadow mt-3">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.reports.download', $report->id) }}" class="btn btn-outline-primary">
                        <i class="fas fa-download me-1"></i>Download Laporan
                    </a>
                    
                    @if($report->department_id)
                    <form action="{{ route('admin.reports.send_to_head', $report->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-success w-100">
                            <i class="fas fa-paper-plane me-1"></i>Kirim ke Kepala Dept
                        </button>
                    </form>
                    @endif
                    
                    <form action="{{ route('admin.reports.delete', $report->id) }}" method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-1"></i>Hapus Laporan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection