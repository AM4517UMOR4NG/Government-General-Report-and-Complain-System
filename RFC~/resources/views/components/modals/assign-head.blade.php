<!-- Assign to Department Head Modal -->
<div class="modal fade" id="assignHeadModal{{ $report->id }}" tabindex="-1" aria-labelledby="assignHeadModalLabel{{ $report->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignHeadModalLabel{{ $report->id }}">Kirim ke Kepala Departemen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('workflow.reports.admin_assign_head', $report->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Laporan akan dikirim ke Kepala Departemen: <strong>{{ $report->department->name ?? 'Departemen tidak ditemukan' }}</strong>
                    </div>
                    @if(!$report->department_id)
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            Laporan belum memiliki departemen. Silakan edit laporan terlebih dahulu.
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" {{ !$report->department_id ? 'disabled' : '' }}>
                        Kirim ke Kepala Departemen
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
