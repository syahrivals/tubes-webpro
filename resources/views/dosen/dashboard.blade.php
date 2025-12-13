@extends('layouts.app')

@section('title', 'Dashboard Dosen')


@section('content')

<!-- ===== TABEL NOTIFIKASI IZIN MAHASISWA ===== -->
@if(isset($notifications) && $notifications->count() > 0)
<div class="card shadow-sm mb-4 animate-slideInTop">
    <div class="card-body">
        <h5 class="mb-3" style="font-weight:700;">Notifikasi Izin Mahasiswa</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Mata Kuliah</th>
                        <th>Tanggal Izin</th>
                        <th>Waktu Masuk</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($notifications as $notif)
                        <tr>
                            <td>{{ $notif->data['message'] ? explode(' ', $notif->data['message'])[1] : '-' }}</td>
                            <td>
                                @php
                                    $msg = $notif->data['message'] ?? '';
                                    preg_match('/untuk (.*?) pada/', $msg, $matkulMatch);
                                @endphp
                                {{ $matkulMatch[1] ?? '-' }}
                            </td>
                            <td>
                                @php
                                    preg_match('/pada (\d{4}-\d{2}-\d{2})/', $msg, $tglMatch);
                                @endphp
                                {{ $tglMatch[1] ?? '-' }}
                            </td>
                            <td>{{ $notif->created_at->diffForHumans() }}</td>
                            <td>
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-2 text-end">
            <a href="{{ route('dosen.izin.index') }}" class="btn btn-sm btn-primary">Lihat Semua & Validasi Izin</a>
        </div>
    </div>
</div>
@endif

<div class="mb-4 d-flex justify-content-between align-items-center animate-fadeInUp">
    <div>
        <h1 class="display-5" style="font-weight:800;color:var(--primary);">Dashboard Dosen</h1>
        <p style="color:var(--text-soft);">Selamat datang, {{ auth()->user()->name }}</p>
    </div>

    <div class="d-flex gap-2">
    <a href="{{ route('dosen.matkuls.create') }}" class="btn btn-primary animate-bounceIn animate-delay-100">
            Tambah Mata Kuliah
        </a>
        <a href="{{ route('dosen.izin.index') }}" class="btn btn-secondary animate-bounceIn animate-delay-200">
            Validasi Izin
        </a>
        <a href="{{ route('dosen.qr-code') }}" class="btn btn-success animate-bounceIn animate-delay-300">
            Absen dengan QR Code
        </a>
    </div>
</div>

@if(isset($todaysMatkuls) && $todaysMatkuls->count() > 0)
<div class="card mb-4 animate-slideInLeft">
    <h2 class="mb-3" style="font-weight:700;color:var(--primary);">Mata Kuliah Hari Ini</h2>
    <div class="row stagger-children">
        @foreach($todaysMatkuls as $m)
        <div class="col-md-6 mb-3">
            <div class="p-3 border rounded shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold" style="font-size:1.1rem;">{{ $m->kode }} - {{ $m->nama }}</div>
                        <div style="color:var(--text-soft);">{{ ucfirst($m->hari) }} @if($m->jam) | Jam: {{ $m->jam }} @endif</div>
                        <div style="color:var(--text-soft);">{{ $mahasiswaCounts[$m->id] ?? 0 }} mahasiswa</div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('presences.index', ['matkul_id' => $m->id]) }}" class="btn btn-outline-primary btn-sm">Presensi</a>
                        <a href="{{ route('dosen.qr-code', ['matkul_id' => $m->id]) }}" class="btn btn-success btn-sm">Buat QR</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="card mb-4 animate-slideInRight">
    <h2 class="mb-4" style="font-weight:700;color:var(--primary);">Statistik</h2>

    <div class="row mb-4 stagger-children">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm text-white" style="background:var(--primary);border-radius:20px;">
                <p class="mb-1">Total Mata Kuliah</p>
                <h2 class="fw-bold">{{ $matkuls->count() }}</h2>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm text-white" style="background:var(--success);border-radius:20px;">
                <p class="mb-1">Total Mahasiswa</p>
                <h2 class="fw-bold">{{ $totalMahasiswa }}</h2>
            </div>
        </div>
    </div>

    @if(count($chartLabels) > 0)
    <h3 class="mt-3 mb-3" style="font-weight:700;color:var(--primary);">Grafik Persentase Kehadiran per Mata Kuliah</h3>
        <canvas id="attendanceChart" style="max-height: 360px;" class="animate-zoomIn animate-delay-300"></canvas>
    @endif
</div>

<div class="card animate-scaleInCenter">
    <h2 class="mb-4" style="font-weight:700;color:var(--primary);">Mata Kuliah</h2>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
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
                    <td class="fw-semibold">{{ $matkul->kode }}</td>
                    <td>{{ $matkul->nama }}</td>
                    <td>{{ $mahasiswaCounts[$matkul->id] ?? 0 }}</td>
                    <td class="fw-semibold">{{ $attendanceStats[$matkul->id] ?? 0 }}%</td>

                    <td class="d-flex gap-2">

                        <a href="{{ route('presences.index', ['matkul_id' => $matkul->id]) }}" 
                            class="btn btn-outline-primary btn-sm">
                            Presensi
                        </a>

                        <a href="{{ route('dosen.matkuls.edit', $matkul->id) }}" class="btn btn-warning btn-sm" style="color:white;font-weight:600;">
                            Edit
                        </a>

                        <form action="{{ route('dosen.matkuls.destroy', $matkul->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus mata kuliah ini?');" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm"
                                style="
                                    background: linear-gradient(140deg, #FF7979, #D72638);
                                    border: none;
                                    color: white;
                                    padding: 6px 14px;
                                    border-radius: 8px;
                                    font-weight: 600;
                                ">
                                Hapus
                            </button>
                        </form>


                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        Tidak ada mata kuliah
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if(count($chartLabels) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('attendanceChart'), {
        type: 'bar',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: "Persentase Kehadiran (%)",
                data: @json($chartData),
                backgroundColor: "rgba(10,116,255,0.45)",
                borderColor: "rgba(10,116,255,0.95)",
                borderWidth: 2,
                borderRadius: 12
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, max: 100 }
            }
        }
    });
</script>
@endif
@endsection