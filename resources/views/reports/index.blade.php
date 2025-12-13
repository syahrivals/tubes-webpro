@extends('layouts.app')

@section('title', 'Laporan Presensi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Laporan Presensi
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($matkuls as $matkul)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">{{ $matkul->nama }}</h5>
                                    <p class="card-text text-muted">{{ $matkul->deskripsi }}</p>
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>{{ $matkul->hari }}
                                            <i class="fas fa-clock ms-2 me-1"></i>{{ $matkul->jam }}
                                        </small>
                                    </div>
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-outline-primary btn-sm" onclick="generateReport({{ $matkul->id }}, 'pdf')">
                                            <i class="fas fa-file-pdf me-1"></i>PDF Report
                                        </button>
                                        <button class="btn btn-outline-success btn-sm" onclick="generateReport({{ $matkul->id }}, 'excel')">
                                            <i class="fas fa-file-excel me-1"></i>Excel Report
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Generation Modal -->
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title">Generate Report</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="reportForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="matkul_id" id="modalMatkulId">
                    <input type="hidden" name="type" id="modalType">

                    <div class="mb-3">
                        <label for="month" class="form-label">Bulan</label>
                        <input type="month" class="form-control" id="month" name="month" value="{{ date('Y-m') }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Generate</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function generateReport(matkulId, type) {
    document.getElementById('modalMatkulId').value = matkulId;
    document.getElementById('modalType').value = type;
    document.getElementById('reportForm').action = '{{ route("dosen.reports.generate") }}';
    new bootstrap.Modal(document.getElementById('reportModal')).show();
}
</script>
@endsection