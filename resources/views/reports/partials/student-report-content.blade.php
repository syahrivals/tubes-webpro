@if($matkul)
<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">{{ $matkul->nama }}</h5>
                <small class="text-muted">{{ $matkul->deskripsi }}</small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($presences as $presence)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($presence->tanggal)->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge
                                        @if($presence->status == 'hadir') bg-success
                                        @elseif($presence->status == 'izin') bg-warning
                                        @elseif($presence->status == 'sakit') bg-info
                                        @else bg-danger
                                        @endif">
                                        {{ ucfirst($presence->status) }}
                                    </span>
                                </td>
                                <td>{{ $presence->catatan ?: '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    Belum ada data presensi untuk mata kuliah ini
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">Ringkasan Kehadiran</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="p-3 bg-success bg-opacity-10 rounded">
                            <div class="h4 text-success mb-0">{{ $stats['hadir'] }}</div>
                            <small class="text-muted">Hadir</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="p-3 bg-warning bg-opacity-10 rounded">
                            <div class="h4 text-warning mb-0">{{ $stats['izin'] }}</div>
                            <small class="text-muted">Izin</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="p-3 bg-info bg-opacity-10 rounded">
                            <div class="h4 text-info mb-0">{{ $stats['sakit'] }}</div>
                            <small class="text-muted">Sakit</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="p-3 bg-danger bg-opacity-10 rounded">
                            <div class="h4 text-danger mb-0">{{ $stats['alpha'] }}</div>
                            <small class="text-muted">Alpha</small>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="text-center">
                    <div class="h3 text-primary mb-1">
                        {{ $stats['total'] > 0 ? round(($stats['hadir'] / $stats['total']) * 100, 1) : 0 }}%
                    </div>
                    <small class="text-muted">Persentase Kehadiran</small>
                </div>

                @if($stats['total'] > 0)
                <div class="mt-3">
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" style="width: {{ ($stats['hadir'] / $stats['total']) * 100 }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">Informasi Mata Kuliah</h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <small class="text-muted d-block">Hari</small>
                    <span class="fw-semibold">{{ $matkul->hari }}</span>
                </div>
                <div class="mb-2">
                    <small class="text-muted d-block">Jam</small>
                    <span class="fw-semibold">{{ $matkul->jam }}</span>
                </div>
                <div class="mb-0">
                    <small class="text-muted d-block">Dosen</small>
                    <span class="fw-semibold">{{ $matkul->dosen->name ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endif