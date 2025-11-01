@extends('layouts.dashboard')

@section('title', 'Edit Keluhan')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit me-2"></i>Edit Keluhan #{{ $complaint->id }}
            </h1>
            <a href="{{ route('admin.complaints') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Form Edit Keluhan</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.complaints.update', $complaint->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">Judul Keluhan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $complaint->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('category') is-invalid @enderror" 
                                    id="category" name="category" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Pelayanan" {{ old('category', $complaint->category) == 'Pelayanan' ? 'selected' : '' }}>Pelayanan</option>
                                <option value="Administrasi" {{ old('category', $complaint->category) == 'Administrasi' ? 'selected' : '' }}>Administrasi</option>
                                <option value="Keamanan" {{ old('category', $complaint->category) == 'Keamanan' ? 'selected' : '' }}>Keamanan</option>
                                <option value="Infrastruktur" {{ old('category', $complaint->category) == 'Infrastruktur' ? 'selected' : '' }}>Infrastruktur</option>
                                <option value="Lingkungan" {{ old('category', $complaint->category) == 'Lingkungan' ? 'selected' : '' }}>Lingkungan</option>
                                <option value="Lainnya" {{ old('category', $complaint->category) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                                    <option value="{{ $department->id }}" {{ old('department_id', $complaint->department_id) == $department->id ? 'selected' : '' }}>
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
                                <option value="low" {{ old('priority', $complaint->priority) == 'low' ? 'selected' : '' }}>Rendah</option>
                                <option value="medium" {{ old('priority', $complaint->priority) == 'medium' ? 'selected' : '' }}>Sedang</option>
                                <option value="high" {{ old('priority', $complaint->priority) == 'high' ? 'selected' : '' }}>Tinggi</option>
                                <option value="urgent" {{ old('priority', $complaint->priority) == 'urgent' ? 'selected' : '' }}>Mendesak</option>
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
                                <option value="pending" {{ old('status', $complaint->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ old('status', $complaint->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="investigating" {{ old('status', $complaint->status) == 'investigating' ? 'selected' : '' }}>Investigating</option>
                                <option value="resolved" {{ old('status', $complaint->status) == 'resolved' ? 'selected' : '' }}>Resolved</option>
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
                                    <option value="{{ $user->id }}" {{ old('assigned_to', $complaint->assigned_to) == $user->id ? 'selected' : '' }}>
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
                               id="location" name="location" value="{{ old('location', $complaint->location) }}" 
                               placeholder="Contoh: Jl. Sudirman No. 123, Jakarta">
                        @error('location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi Lengkap <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="6" required 
                                  placeholder="Jelaskan secara detail keluhan yang diajukan...">{{ old('description', $complaint->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.complaints') }}" class="btn btn-secondary">
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
                <h6 class="m-0 font-weight-bold text-primary">Informasi Keluhan</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>ID Keluhan:</strong><br>
                    <span class="text-muted">#{{ $complaint->id }}</span>
                </div>
                
                <div class="mb-3">
                    <strong>Diajukan oleh:</strong><br>
                    <span class="text-muted">{{ $complaint->user->name }}</span><br>
                    <small class="text-muted">{{ $complaint->user->email }}</small>
                </div>
                
                <div class="mb-3">
                    <strong>Tanggal Diajukan:</strong><br>
                    <span class="text-muted">{{ $complaint->created_at->format('d F Y, H:i') }}</span>
                </div>
                
                <div class="mb-3">
                    <strong>Terakhir Diupdate:</strong><br>
                    <span class="text-muted">{{ $complaint->updated_at->format('d F Y, H:i') }}</span>
                </div>
                
                @if($complaint->assignedUser)
                <div class="mb-3">
                    <strong>Ditugaskan ke:</strong><br>
                    <span class="text-muted">{{ $complaint->assignedUser->name }}</span><br>
                    <small class="text-muted">{{ $complaint->assignedUser->email }}</small>
                </div>
                @endif
                
                @if($complaint->attachments && count($complaint->attachments) > 0)
                <div class="mb-3">
                    <strong>Lampiran:</strong><br>
                    @foreach($complaint->attachments as $attachment)
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
                    @if($complaint->status == 'pending')
                    <form action="{{ route('admin.complaints.confirm', $complaint->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-success w-100">
                            <i class="fas fa-check me-1"></i>Konfirmasi Keluhan
                        </button>
                    </form>
                    @endif
                    
                    <form action="{{ route('admin.complaints.delete', $complaint->id) }}" method="POST" 
                          onsubmit="return confirm('Yakin ingin menghapus keluhan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-1"></i>Hapus Keluhan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection