@extends('layouts.app')

@section('title', 'Reports Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt"></i>
                        Reports Management
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
                                @forelse($reports as $report)
                                <tr>
                                    <td>
                                        <span class="badge badge-info">{{ $report->ticket_no }}</span>
                                    </td>
                                    <td>{{ Str::limit($report->title, 30) }}</td>
                                    <td>{{ $report->category }}</td>
                                    <td>
                                        <span class="badge badge-{{ $report->priority === 'urgent' ? 'danger' : ($report->priority === 'high' ? 'warning' : ($report->priority === 'medium' ? 'info' : 'secondary')) }}">
                                            {{ ucfirst($report->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $report->status === 'closed' ? 'success' : ($report->status === 'resolved' ? 'primary' : ($report->status === 'in_progress' ? 'info' : 'warning')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                        </span>
                                    </td>
                                    <td>{{ $report->department->name ?? 'N/A' }}</td>
                                    <td>{{ $report->assignedUser->name ?? 'Unassigned' }}</td>
                                    <td>{{ $report->user->name }}</td>
                                    <td>{{ $report->created_at->format('d M Y, H:i') }}</td>
                                    <td>
                                        <x-file-view-button :reportable="$report" type="report" />
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.reports.edit', $report->id) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.reports.download', $report->id) }}" class="btn btn-success btn-sm">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center">No reports found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $reports->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
