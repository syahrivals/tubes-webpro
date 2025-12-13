@extends('layouts.app')

@section('title', 'Laporan Saya')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Laporan Presensi Saya
                    </h4>
                </div>
                <div class="card-body">
                    @if($matkuls->count() > 0)
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <select class="form-select" id="matkulSelect" onchange="changeMatkul()">
                                <option value="">Pilih Mata Kuliah</option>
                                @foreach($matkuls as $matkul)
                                <option value="{{ $matkul->id }}" {{ request('matkul_id') == $matkul->id ? 'selected' : '' }}>
                                    {{ $matkul->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="reportContent">
                        @include('reports.partials.student-report-content')
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-info-circle fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada mata kuliah yang diambil</h5>
                        <p class="text-muted">Silakan enroll ke mata kuliah terlebih dahulu.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changeMatkul() {
    const matkulId = document.getElementById('matkulSelect').value;
    if (matkulId) {
        window.location.href = '{{ route("mahasiswa.reports.student") }}?matkul_id=' + matkulId;
    }
}
</script>
@endsection