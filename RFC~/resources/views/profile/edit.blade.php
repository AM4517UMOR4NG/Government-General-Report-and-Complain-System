@extends('layouts.dashboard')

@section('title', 'Edit Profil')

@section('content')
<style>
    .edit-header {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 50%, #0369a1 100%);
        color: white;
        padding: 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .edit-header h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
    }

    .edit-header .btn {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .edit-header .btn:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.5);
        color: white;
    }

    .edit-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #f0f0f0;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .edit-card-header {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        padding: 1.5rem;
        border-bottom: 1px solid #e9ecef;
    }

    .edit-card-header h5 {
        margin: 0;
        color: #0369a1;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1.1rem;
    }

    .edit-card-header i {
        color: #0284c7;
        font-size: 1.2rem;
    }

    .edit-card-body {
        padding: 2rem;
    }

    .form-section-title {
        color: #0284c7;
        font-weight: 600;
        font-size: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .form-section-title i {
        font-size: 1.1rem;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-control, .form-select {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .form-control:focus, .form-select:focus {
        background: white;
        border-color: #0284c7;
        box-shadow: 0 0 0 0.2rem rgba(14, 165, 233, 0.15);
    }

    .form-control::placeholder {
        color: #999;
    }

    .avatar-upload-section {
        padding: 2rem 0;
        text-align: center;
    }

    .avatar-dropzone {
        border: 2px dashed #0284c7;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.05) 0%, rgba(2, 132, 199, 0.05) 100%);
        padding: 2rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .avatar-dropzone:hover {
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.1) 0%, rgba(2, 132, 199, 0.1) 100%);
        border-color: #0369a1;
    }

    .avatar-dropzone.dragover {
        background: linear-gradient(135deg, rgba(14, 165, 233, 0.15) 0%, rgba(2, 132, 199, 0.15) 100%);
        border-color: #0284c7;
        box-shadow: 0 4px 15px rgba(14, 165, 233, 0.2);
    }

    .avatar-preview {
        position: relative;
        display: inline-block;
        margin-bottom: 1rem;
    }

    .avatar-preview img {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid #0284c7;
        object-fit: cover;
        box-shadow: 0 4px 15px rgba(14, 165, 233, 0.2);
    }

    .avatar-placeholder {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        border: 4px solid #0284c7;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        box-shadow: 0 4px 15px rgba(14, 165, 233, 0.2);
    }

    .avatar-edit-indicator {
        position: absolute;
        right: -6px;
        bottom: -6px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(14, 165, 233, 0.3);
        font-size: 1.1rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(14, 165, 233, 0.3);
    }

    .btn-outline-secondary {
        border: 2px solid #e9ecef;
        color: #0284c7;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background: #f8f9fa;
        border-color: #0284c7;
        color: #0284c7;
    }

    .btn-outline-danger {
        border: 2px solid #e74c3c;
        color: #e74c3c;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-outline-danger:hover {
        background: rgba(231, 76, 60, 0.1);
        border-color: #e74c3c;
        color: #e74c3c;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        padding-top: 1.5rem;
        border-top: 1px solid #f0f0f0;
    }

    .invalid-feedback {
        color: #e74c3c;
        font-size: 0.85rem;
        margin-top: 0.25rem;
        display: block;
    }

    .text-danger {
        color: #e74c3c;
    }

    .text-muted {
        color: #999;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .edit-header {
            flex-direction: column;
            text-align: center;
        }

        .edit-header h1 {
            font-size: 1.5rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-actions .btn {
            width: 100%;
        }
    }
</style>

<div class="container-fluid">
    <!-- Edit Header -->
    <div class="edit-header">
        <div>
            <h1><i class="fas fa-user-edit me-2"></i>Edit Profil</h1>
        </div>
        <a href="{{ route('profile.show') }}" class="btn">
            <i class="fas fa-arrow-left me-1"></i>Kembali ke Profil
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="edit-card">
                <div class="edit-card-header">
                    <h5><i class="fas fa-edit"></i>Informasi Profil</h5>
                </div>
                <div class="edit-card-body">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Avatar Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="avatar-upload-section">
                                    <div id="avatarDropzone" class="avatar-dropzone d-flex flex-column align-items-center justify-content-center text-center">
                                        <div class="position-relative mb-3">
                                            @if($user->getAvatarUrl())
                                                <img id="avatarPreview" src="{{ $user->getAvatarUrl() }}" alt="Avatar" class="rounded-circle shadow" width="128" height="128" style="object-fit: cover; border: 4px solid var(--border);">
                                            @else
                                                <div id="avatarPlaceholder" class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center shadow" 
                                                     style="width: 128px; height: 128px; background-color: {{ $user->getAvatarColor() }}; border: 4px solid var(--border); font-size: 2.5rem; font-weight: bold; color: white;">
                                                    {{ $user->getAvatarInitials() }}
                                                </div>
                                                <img id="avatarPreview" src="" alt="Avatar" class="rounded-circle d-none shadow" width="128" height="128" style="object-fit: cover; border: 4px solid var(--border);">
                                            @endif
                                            <span class="avatar-edit-indicator" title="Ganti foto"><i class="fas fa-camera"></i></span>
                                        </div>
                                        <p class="mb-2 text-muted small">Seret & letakkan gambar di sini, atau klik untuk memilih.</p>
                                        <div class="d-flex gap-2">
                                            <label for="avatar" class="btn btn-primary btn-sm">
                                                <i class="fas fa-upload me-1"></i>Pilih Foto
                                            </label>
                                            @if($user->avatar)
                                            <button id="deleteAvatarBtn" type="button" class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-trash me-1"></i>Hapus
                                            </button>
                                            @endif
                                        </div>
                                        <input type="file" id="avatar" name="avatar" class="d-none" accept="image/*">
                                        <small id="avatarHelp" class="text-muted d-block mt-2">Format: JPG, PNG, GIF. Maksimal 2MB.</small>
                                        <div id="avatarError" class="invalid-feedback d-block mt-2" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="form-section-title">
                                    <i class="fas fa-user"></i>Informasi Dasar
                                </div>
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Nomor Telepon</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="birth_date" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" name="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}">
                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="gender" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Work & Contact Information -->
                            <div class="col-md-6">
                                <div class="form-section-title">
                                    <i class="fas fa-briefcase"></i>Informasi Pekerjaan & Kontak
                                </div>

                                @if(!$user->isCitizen())
                                <div class="mb-3">
                                    <label for="position" class="form-label">Jabatan</label>
                                    <input type="text" class="form-control @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position', $user->position) }}">
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @endif

                                <div class="mb-3">
                                    <label for="address" class="form-label">Alamat</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" placeholder="Masukkan alamat lengkap Anda...">{{ old('address', $user->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="bio" class="form-label">Bio / Deskripsi</label>
                                    <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="4" placeholder="Ceritakan sedikit tentang diri Anda...">{{ old('bio', $user->bio) }}</textarea>
                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Maksimal 1000 karakter</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                    @if($user->avatar)
                    <!-- Separate delete avatar form to avoid nested forms -->
                    <form id="deleteAvatarForm" action="{{ route('profile.avatar.delete') }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    const fileInput = document.getElementById('avatar');
    const dropzone = document.getElementById('avatarDropzone');
    const previewImg = document.getElementById('avatarPreview');
    const placeholder = document.getElementById('avatarPlaceholder');
    const errorBox = document.getElementById('avatarError');
    const maxSize = 2 * 1024 * 1024; // 2MB

    function showError(msg){ if(!errorBox) return; errorBox.style.display='block'; errorBox.textContent = msg; }
    function clearError(){ if(!errorBox) return; errorBox.style.display='none'; errorBox.textContent=''; }

    function handleFiles(file){
        clearError();
        if(!file) return;
        const validTypes = ['image/jpeg','image/png','image/gif'];
        if(!validTypes.includes(file.type)){
            showError('Format tidak didukung. Gunakan JPG, PNG, atau GIF.');
            fileInput.value = '';
            return;
        }
        if(file.size > maxSize){
            showError('Ukuran file melebihi 2MB.');
            fileInput.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = (ev)=>{
            if(previewImg){
                previewImg.src = ev.target.result;
                previewImg.classList.remove('d-none');
            }
            if(placeholder){ placeholder.classList.add('d-none'); }
        };
        reader.readAsDataURL(file);
    }

    if(fileInput){
        fileInput.addEventListener('change', (e)=> handleFiles(e.target.files[0]));
    }
    if(dropzone){
        dropzone.addEventListener('click', ()=> fileInput && fileInput.click());
        dropzone.addEventListener('dragover', (e)=>{ e.preventDefault(); dropzone.classList.add('dragover'); });
        dropzone.addEventListener('dragleave', ()=> dropzone.classList.remove('dragover'));
        dropzone.addEventListener('drop', (e)=>{
            e.preventDefault();
            dropzone.classList.remove('dragover');
            const file = e.dataTransfer.files && e.dataTransfer.files[0];
            if(file){
                // Assign to input so form submits it
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;
                handleFiles(file);
            }
        });
    }

    const delBtn = document.getElementById('deleteAvatarBtn');
    const delForm = document.getElementById('deleteAvatarForm');
    if(delBtn && delForm){
        delBtn.addEventListener('click', ()=>{
            if(confirm('Hapus avatar?')) delForm.submit();
        });
    }
})();
</script>
@endsection
