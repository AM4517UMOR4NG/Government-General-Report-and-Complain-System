<!-- Approve Report Modal -->
<div class="modal fade" id="approveModal{{ $report->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $report->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel{{ $report->id }}">Approve & Close Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('workflow.reports.admin_approve_close', $report->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Laporan akan disetujui dan ditutup. User akan mendapat notifikasi.
                    </div>
                    <div class="mb-3">
                        <label for="final_notes{{ $report->id }}" class="form-label">Catatan Final (Opsional):</label>
                        <textarea class="form-control" id="final_notes{{ $report->id }}" name="final_notes" rows="3" placeholder="Tambahkan catatan final..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Approve & Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
