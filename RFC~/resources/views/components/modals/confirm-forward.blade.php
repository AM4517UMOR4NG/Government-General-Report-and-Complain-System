<!-- Confirm and Forward Modal -->
<div class="modal fade" id="confirmForwardModal{{ $report->id }}" tabindex="-1" aria-labelledby="confirmForwardModalLabel{{ $report->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmForwardModalLabel{{ $report->id }}">Konfirmasi & Kirim ke Kepala Departemen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('workflow.reports.staff_confirm_forward', $report->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Laporan akan dikonfirmasi dan dikirim ke Kepala Departemen: <strong>{{ $report->department->name ?? 'Departemen tidak ditemukan' }}</strong>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Konfirmasi & Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>
