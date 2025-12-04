@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="display-5">Dashboard Dosen</h1>
        <p class="text-muted">Selamat datang, {{ auth()->user()->name }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('dosen.izin.index') }}" class="btn btn-warning btn-lg">
            📝 Validasi Izin
        </a>
        <a href="{{ route('dosen.qr-code') }}" class="btn btn-success btn-lg">
            📱 Absen dengan QR Code
        </a>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <h2 class="card-title mb-4">Statistik</h2>
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <p class="card-text mb-0">Total Mata Kuliah</p>
                        <h3 class="mb-0">{{ $matkuls->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <p class="card-text mb-0">Total Mahasiswa</p>
                        <h3 class="mb-0">{{ $totalMahasiswa }}</h3>
                    </div>
                </div>
            </div>
        </div>
        
        @if(count($chartLabels) > 0)
        <div class="mt-4">
            <h3 class="mb-3">Grafik Persentase Kehadiran per Mata Kuliah</h3>
            <canvas id="attendanceChart" style="max-height: 400px;"></canvas>
        </div>
        @endif
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <h2 class="card-title mb-4">Mata Kuliah</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Mahasiswa</th>
                        <th>Kehadiran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($matkuls as $matkul)
                    <tr>
                        <td>{{ $matkul->kode }}</td>
                        <td>{{ $matkul->nama }}</td>
                        <td>{{ $mahasiswaCounts[$matkul->id] ?? 0 }}</td>
                        <td>{{ $attendanceStats[$matkul->id] ?? 0 }}%</td>
                        <td>
                            <a href="{{ route('presences.index', ['matkul_id' => $matkul->id]) }}" 
                               class="btn btn-sm btn-primary">
                                Presensi
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Tidak ada mata kuliah</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if(count($chartLabels) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('attendanceChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Persentase Kehadiran (%)',
                data: @json($chartData),
                backgroundColor: 'rgba(13, 110, 253, 0.5)',
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
</script>
@endif
@endsection
