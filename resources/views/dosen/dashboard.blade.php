@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('content')

<style>
    /* ==== COLOR BLUE PALETTE ==== */
    :root {
        --primary: #0A74FF; 
        --primary-light: #4A97FF;
        --primary-dark: #0059D4;

        --success: #33D697;
        --success-light: #66F3B7;
        --success-dark: #1EA56D;

        --yellow: #F6C979;
        --yellow-dark: #E8A844;

        --bg-soft: #F3F7FF;
        --text-dark: #1F2430;
        --text-soft: #6B7082;
    }

    body {
        background: var(--bg-soft) !important;
    }

    /* ===== GLOBAL A NO UNDERLINE ===== */
    a {
        text-decoration: none !important;
    }

    /* ===== MODERN CARD ===== */
    .modern-card {
        border: none;
        border-radius: 22px;
        background: #ffffff;
        padding: 28px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        transition: 0.3s ease;
    }
    .modern-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 36px rgba(0,0,0,0.1);
    }

    /* ===== STATISTIC CARDS ===== */
    .stat-card {
        padding: 26px;
        border-radius: 20px;
        color: white;
        background-size: 180%;
        background-position: center;
        box-shadow: 0 6px 18px rgba(0,0,0,0.12);
        transition: 0.3s;
    }

    .stat-blue {
        background: linear-gradient(135deg, var(--primary-light), var(--primary-dark));
    }

    .stat-green {
        background: linear-gradient(135deg, var(--success-light), var(--success-dark));
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 26px rgba(0,0,0,0.16);
    }

    /* ===== BUTTONS SUPER MODERN ===== */
    .modern-btn {
        border-radius: 14px;
        padding: 12px 26px;
        font-weight: 600;
        font-size: 15px;
        color: white !important;
        transition: 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none !important;
        box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    }

    .modern-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 9px 26px rgba(0,0,0,0.22);
    }

    .btn-warning-modern {
        background: linear-gradient(140deg, #FFDD9B, #F6B457);
        border: none;
    }

    .btn-success-modern {
        background: linear-gradient(140deg, #78F2BB, #3CCF8C);
        border: none;
    }

    /* ===== TABLE ===== */
    .table thead {
        background: #E4EEFF;
        font-weight: 600;
        color: var(--text-dark);
    }
    .table-hover tbody tr:hover {
        background: #f0f6ff;
    }

    .modern-title {
        font-weight: 800;
        color: var(--text-dark);
    }

    .text-soft {
        color: var(--text-soft);
    }
</style>


<!-- ===== HEADER ===== -->
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="display-5 modern-title">Dashboard Dosen</h1>
        <p class="text-soft">Selamat datang, {{ auth()->user()->name }}</p>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ route('dosen.izin.index') }}" class="modern-btn btn-warning-modern">
            📝 Validasi Izin
        </a>

        <a href="{{ route('dosen.qr-code') }}" class="modern-btn btn-success-modern">
            📱 Absen dengan QR Code
        </a>
    </div>
</div>


<!-- ===== STATISTIK ===== -->
<div class="modern-card mb-4">
    <h2 class="modern-title mb-4">Statistik</h2>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="stat-card stat-blue">
                <p class="mb-1">Total Mata Kuliah</p>
                <h2 class="fw-bold">{{ $matkuls->count() }}</h2>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="stat-card stat-green">
                <p class="mb-1">Total Mahasiswa</p>
                <h2 class="fw-bold">{{ $totalMahasiswa }}</h2>
            </div>
        </div>
    </div>

    @if(count($chartLabels) > 0)
        <h3 class="modern-title mt-3 mb-3">Grafik Persentase Kehadiran per Mata Kuliah</h3>
        <canvas id="attendanceChart" style="max-height: 360px;"></canvas>
    @endif
</div>


<!-- ===== TABLE ===== -->
<div class="modern-card">
    <h2 class="modern-title mb-4">Mata Kuliah</h2>

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

                        <!-- Tombol Presensi -->
                        <a href="{{ route('presences.index', ['matkul_id' => $matkul->id]) }}" 
                            class="btn btn-outline-primary btn-sm">
                            Presensi
                        </a>

                        <!-- Tombol Hapus Mata Kuliah -->
                        <form action="{{ route('dosen.matkul.destroy', $matkul->id) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus mata kuliah ini?');">
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



<!-- ===== CHART ===== -->
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
