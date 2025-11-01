@props(['reportable', 'type'])

@if(count($reportable->attachments ?? []) > 0)
    <a href="{{ route('files.view', [$type, $reportable->id]) }}" 
       class="btn btn-info btn-sm" 
       title="View Files ({{ count($reportable->attachments) }} files)">
        <i class="fas fa-file-alt"></i> 
        Files ({{ count($reportable->attachments) }})
    </a>
@else
    <span class="btn btn-secondary btn-sm disabled" title="No files attached">
        <i class="fas fa-file-alt"></i> 
        No Files
    </span>
@endif
