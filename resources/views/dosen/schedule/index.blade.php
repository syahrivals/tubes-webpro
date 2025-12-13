@extends('layouts.app')

@section('title', 'Manajemen Jadwal')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Manajemen Jadwal Kuliah
                    </h4>
                    <div>
                        <a href="{{ route('dosen.schedule.calendar') }}" class="btn btn-light btn-sm me-2">
                            <i class="fas fa-calendar me-1"></i>Kalender
                        </a>
                        <a href="{{ route('dosen.schedule.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus me-1"></i>Tambah Jadwal
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach(['senin' => 'Senin', 'selasa' => 'Selasa', 'rabu' => 'Rabu', 'kamis' => 'Kamis', 'jumat' => 'Jumat', 'sabtu' => 'Sabtu', 'minggu' => 'Minggu'] as $dayKey => $dayName)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-calendar-day me-2"></i>{{ $dayName }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @if(count($schedule[$dayKey]) > 0)
                                        @foreach($schedule[$dayKey] as $matkul)
                                        <div class="d-flex justify-content-between align-items-start mb-3 p-3 bg-primary bg-opacity-10 rounded">
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $matkul->nama }}</h6>
                                                <p class="mb-1 small text-muted">{{ $matkul->deskripsi }}</p>
                                                <div class="d-flex align-items-center">
                                                    <small class="text-muted me-3">
                                                        <i class="fas fa-clock me-1"></i>{{ $matkul->jam }}
                                                    </small>
                                                    @if($matkul->ruangan)
                                                    <small class="text-muted">
                                                        <i class="fas fa-map-marker-alt me-1"></i>{{ $matkul->ruangan }}
                                                    </small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="{{ route('dosen.schedule.edit', $matkul) }}">
                                                        <i class="fas fa-edit me-2"></i>Edit
                                                    </a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" href="#" onclick="confirmDelete({{ $matkul->id }}, '{{ $matkul->nama }}')">
                                                        <i class="fas fa-trash me-2"></i>Hapus
                                                    </a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="text-center py-4 text-muted">
                                            <i class="fas fa-calendar-times fa-2x mb-2"></i>
                                            <p class="mb-0">Tidak ada jadwal</p>
                                        </div>
                                    @endif
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus jadwal mata kuliah <strong id="deleteMatkulName"></strong>?</p>
                <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(matkulId, matkulName) {
    document.getElementById('deleteMatkulName').textContent = matkulName;
    document.getElementById('deleteForm').action = '{{ route("dosen.schedule.index") }}/' + matkulId;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection