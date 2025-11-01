<!-- Reject Report Modal -->
<div class="modal fade" id="rejectModal{{ $report->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $report->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel{{ $report->id }}">Reject Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('workflow.reports.admin_reject_staff', $report->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Laporan akan ditolak dan dikembalikan ke staff untuk revisi.
                    </div>
                    <div class="mb-3">
                        <label for="assigned_to{{ $report->id }}" class="form-label">Kembalikan ke Staff:</label>
                        <select class="form-select" id="assigned_to{{ $report->id }}" name="assigned_to" required>
                            <option value="">-- Pilih Staff --</option>
                            @if(isset($staffList))
                                @foreach($staffList->where('department_id', $report->department_id) as $staff)
                                    <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                @endforeach
                            @else
                                @foreach(\App\Models\User::where('role', 'staff')->where('department_id', $report->department_id)->get() as $staff)
                                    <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="rejection_reason{{ $report->id }}" class="form-label">Alasan Penolakan:</label>
                        <textarea class="form-control" id="rejection_reason{{ $report->id }}" name="rejection_reason" rows="3" placeholder="Jelaskan alasan penolakan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Reject & Kembalikan</button>
                </div>
            </form>
        </div>
    </div>
</div>
