<!-- Complete Report Modal -->
<div class="modal fade" id="completeModal{{ $report->id }}" tabindex="-1" aria-labelledby="completeModalLabel{{ $report->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="completeModalLabel{{ $report->id }}">Selesaikan & Kirim ke Admin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('workflow.reports.staff_confirm_admin', $report->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Laporan akan diselesaikan dan dikirim ke admin untuk persetujuan akhir.
                    </div>
                    <div class="mb-3">
                        <label for="completion_notes{{ $report->id }}" class="form-label">Catatan Penyelesaian:</label>
                        <textarea class="form-control" id="completion_notes{{ $report->id }}" name="completion_notes" rows="3" placeholder="Jelaskan tindakan yang telah dilakukan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Selesaikan & Kirim ke Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>
