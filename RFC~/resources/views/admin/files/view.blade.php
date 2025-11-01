@extends('layouts.dashboard')

@section('title', 'View Files - ' . $reportable->ticket_no)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt"></i>
                        Files for {{ ucfirst($type) }}: {{ $reportable->ticket_no }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                        @if(count($files) > 0)
                        <a href="{{ route('files.download_all', [$type, $reportable->id]) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> Download All
                        </a>
                        @endif
                    </div>
                </div>
                
                <div class="card-body">
                    @if(count($files) > 0)
                        <div class="row">
                            @foreach($files as $file)
                            <div class="col-md-4 col-lg-3 mb-4">
                                <div class="card file-card">
                                    <div class="card-body text-center">
                                        @if($file['is_image'])
                                            <div class="file-preview mb-3">
                                                <img src="{{ route('files.preview_image', [$type, $reportable->id, $file['name']]) }}" 
                                                     alt="{{ $file['name'] }}" 
                                                     class="img-fluid rounded" 
                                                     style="max-height: 150px; width: 100%; object-fit: cover;"
                                                     onclick="openImageModal('{{ route('files.preview_image', [$type, $reportable->id, $file['name']]) }}', '{{ $file['name'] }}')">
                                            </div>
                                        @else
                                            <div class="file-preview mb-3">
                                                <div class="file-icon">
                                                    @switch($file['extension'])
                                                        @case('pdf')
                                                            <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                                            @break
                                                        @case('doc')
                                                        @case('docx')
                                                            <i class="fas fa-file-word fa-3x text-primary"></i>
                                                            @break
                                                        @case('xls')
                                                        @case('xlsx')
                                                            <i class="fas fa-file-excel fa-3x text-success"></i>
                                                            @break
                                                        @case('zip')
                                                            <i class="fas fa-file-archive fa-3x text-warning"></i>
                                                            @break
                                                        @default
                                                            <i class="fas fa-file fa-3x text-secondary"></i>
                                                    @endswitch
                                                </div>
                                            </div>
                                        @endif
                                        
                                        <h6 class="card-title text-truncate" title="{{ $file['name'] }}">
                                            {{ $file['name'] }}
                                        </h6>
                                        
                                        <p class="card-text">
                                            <small class="text-muted">
                                                <i class="fas fa-weight-hanging"></i> {{ $file['size_formatted'] }}<br>
                                                <i class="fas fa-calendar"></i> {{ $file['created_at'] }}
                                            </small>
                                        </p>
                                        
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('files.download', [$type, $reportable->id, $file['name']]) }}" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                            @if($file['is_image'])
                                            <button type="button" 
                                                    class="btn btn-info btn-sm" 
                                                    onclick="openImageModal('{{ route('files.preview_image', [$type, $reportable->id, $file['name']]) }}', '{{ $file['name'] }}')">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No files uploaded</h5>
                            <p class="text-muted">This {{ $type }} has no attached files.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="" class="img-fluid">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a id="modalDownloadBtn" href="#" class="btn btn-primary">
                    <i class="fas fa-download"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.file-card {
    transition: transform 0.2s;
    border: 1px solid #dee2e6;
}

.file-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.file-preview {
    min-height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    border-radius: 8px;
}

.file-icon {
    color: #6c757d;
}

.card-title {
    font-size: 0.9rem;
    font-weight: 600;
}

.btn-group .btn {
    font-size: 0.8rem;
}
</style>

<script>
function openImageModal(imageSrc, fileName) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('modalImage').alt = fileName;
    document.getElementById('modalDownloadBtn').href = '{{ route("files.download", [$type, $reportable->id, ""]) }}/' + encodeURIComponent(fileName);
    $('#imageModal').modal('show');
}
</script>
@endsection
