<!-- Review and Return Modal -->
<div class="modal fade" id="reviewReturnModal{{ $report->id }}" tabindex="-1" aria-labelledby="reviewReturnModalLabel{{ $report->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewReturnModalLabel{{ $report->id }}">Review & Kembalikan ke Staff</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('workflow.reports.head_review_return', $report->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Laporan akan dikembalikan ke staff untuk tindak lanjut.
                    </div>
                    <div class="mb-3">
                        <label for="assigned_to{{ $report->id }}" class="form-label">Kembalikan ke Staff:</label>
                        <select class="form-select" id="assigned_to{{ $report->id }}" name="assigned_to" required>
                            <option value="">-- Pilih Staff --</option>
                            @foreach(\App\Models\User::where('role', 'staff')->where('department_id', $report->department_id)->get() as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes{{ $report->id }}" class="form-label">Catatan Review (Opsional):</label>
                        <textarea class="form-control" id="notes{{ $report->id }}" name="notes" rows="3" placeholder="Tambahkan catatan review..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Review & Kembalikan</button>
                </div>
            </form>
        </div>
    </div>
</div>
