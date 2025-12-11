@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h1 class="display-5" style="font-weight:800;color:var(--primary);">Dashboard Mahasiswa</h1>
        <p style="color:var(--text-soft);">Selamat datang, {{ $mahasiswa->user->name }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('mahasiswa.izin.create') }}" class="btn btn-primary btn-lg">
            📝 Ajukan Izin
        </a>
        <a href="{{ route('mahasiswa.scan.index') }}" class="btn btn-success btn-lg">
            📷 Scan QR
        </a>
    </div>
</div>

@if(isset($todaysMatkuls) && $todaysMatkuls->count() > 0)
<div class="card mb-4">
    <div class="card-body">
        <h2 class="mb-3" style="font-weight:700;color:var(--primary);">Mata Kuliah Hari Ini</h2>
        <div class="row">
            @foreach($todaysMatkuls as $m)
            <div class="col-md-6 mb-3">
                <div class="p-3 border rounded shadow-sm">
                    <div class="fw-semibold" style="font-size:1.1rem;">{{ $m->kode }} - {{ $m->nama }}</div>
                    <div style="color:var(--text-soft);">{{ ucfirst($m->hari) }} @if($m->jam) | Jam: {{ $m->jam }} @endif</div>
                    <div style="color:var(--text-soft);">Semester: {{ $m->semester }}, SKS: {{ $m->credits }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<div class="card">
    <div class="card-body">
        <h2 class="mb-4" style="font-weight:700;color:var(--primary);">Ringkasan Kehadiran</h2>
        @if($matkuls->count() > 0)
        <div class="mb-4">
            <label for="matkulSelect" class="form-label">Pilih Mata Kuliah:</label>
            <form method="GET" action="{{ route('mahasiswa.dashboard') }}" id="matkulForm">
                <select name="matkul_id" id="matkulSelect" class="form-select" onchange="document.getElementById('matkulForm').submit();">
                    @foreach($matkuls as $matkul)
                    <option value="{{ $matkul->id }}" {{ $selectedMatkulId == $matkul->id ? 'selected' : '' }}>
                        {{ $matkul->kode }} - {{ $matkul->nama }}
                    </option>
                    @endforeach
                </select>
            </form>
        </div>
        @if($selectedData)
        <div>
            <h3 class="h5 mb-3">{{ $selectedData['matkul']->kode }} - {{ $selectedData['matkul']->nama }}</h3>
            <div class="mb-4">
                <div class="d-flex flex-wrap gap-3 justify-content-between">
                    <div class="card text-white flex-grow-1" style="min-width:120px;max-width:180px;background:var(--success);">
                        <div class="card-body text-center py-3">
                            <div style="font-size:1.1rem;font-weight:600;">Hadir</div>
                            <div style="font-size:2rem;font-weight:700;">{{ $selectedData['hadir'] }}</div>
                        </div>
                    </div>
                    <div class="card text-white flex-grow-1" style="min-width:120px;max-width:180px;background:var(--secondary);color:var(--primary);">
                        <div class="card-body text-center py-3">
                            <div style="font-size:1.1rem;font-weight:600;">Izin</div>
                            <div style="font-size:2rem;font-weight:700;">{{ $selectedData['izin'] }}</div>
                        </div>
                    </div>
                    <div class="card text-white flex-grow-1" style="min-width:120px;max-width:180px;background:var(--info);">
                        <div class="card-body text-center py-3">
                            <div style="font-size:1.1rem;font-weight:600;">Sakit</div>
                            <div style="font-size:2rem;font-weight:700;">{{ $selectedData['sakit'] }}</div>
                        </div>
                    </div>
                    <div class="card text-white flex-grow-1" style="min-width:120px;max-width:180px;background:var(--danger);">
                        <div class="card-body text-center py-3">
                            <div style="font-size:1.1rem;font-weight:600;">Alpha</div>
                            <div style="font-size:2rem;font-weight:700;">{{ $selectedData['alpha'] }}</div>
                        </div>
                    </div>
                    <div class="card text-white flex-grow-1" style="min-width:120px;max-width:180px;background:var(--primary);">
                        <div class="card-body text-center py-3">
                            <div style="font-size:1.1rem;font-weight:600;">Persentase</div>
                            <div style="font-size:2rem;font-weight:700;">{{ $selectedData['percentage'] }}%</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($selectedData['presences'] as $presence)
                        <tr>
                            <td>{{ $presence->tanggal->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge 
                                    {{ $presence->status == 'hadir' ? 'bg-success' : '' }}
                                    {{ $presence->status == 'izin' ? 'bg-warning' : '' }}
                                    {{ $presence->status == 'sakit' ? 'bg-info' : '' }}
                                    {{ $presence->status == 'alpha' ? 'bg-danger' : '' }}">
                                    {{ strtoupper($presence->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">Belum ada data presensi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        @else
        <p class="text-muted mb-0">Anda belum terdaftar pada mata kuliah apapun.</p>
        @endif
    </div>
</div>
@endsection
