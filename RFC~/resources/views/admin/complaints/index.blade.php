@extends('layouts.app')

@section('title', 'Complaints Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle"></i>
                        Complaints Management
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Ticket No</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Department</th>
                                    <th>Assigned To</th>
                                    <th>Created By</th>
                                    <th>Created At</th>
                                    <th>Files</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($complaints as $complaint)
                                <tr>
                                    <td>
                                        <span class="badge badge-info">{{ $complaint->ticket_no }}</span>
                                    </td>
                                    <td>{{ Str::limit($complaint->title, 30) }}</td>
                                    <td>{{ $complaint->category }}</td>
                                    <td>
                                        <span class="badge badge-{{ $complaint->priority === 'urgent' ? 'danger' : ($complaint->priority === 'high' ? 'warning' : ($complaint->priority === 'medium' ? 'info' : 'secondary')) }}">
                                            {{ ucfirst($complaint->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $complaint->status === 'closed' ? 'success' : ($complaint->status === 'resolved' ? 'primary' : ($complaint->status === 'in_progress' ? 'info' : 'warning')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                                        </span>
                                    </td>
                                    <td>{{ $complaint->department->name ?? 'N/A' }}</td>
                                    <td>{{ $complaint->assignedUser->name ?? 'Unassigned' }}</td>
                                    <td>{{ $complaint->user->name }}</td>
                                    <td>{{ $complaint->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        <x-file-view-button :reportable="$complaint" type="complaint" />
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.complaints.edit', $complaint->id) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center">No complaints found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $complaints->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
