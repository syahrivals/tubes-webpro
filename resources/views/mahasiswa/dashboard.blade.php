@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="mb-4">
    <h1 class="display-5">Dashboard Mahasiswa</h1>
    <p class="text-muted">Selamat datang, {{ $mahasiswa->user->name }}</p>
</div>

<div class="card shadow">
    <div class="card-body">
        <h2 class="card-title mb-4">Ringkasan Kehadiran</h2>
        
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
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-2">
                    <div class="card bg-success text-white">
                        <div class="card-body p-3">
                            <p class="card-text small mb-0">Hadir</p>
                            <h4 class="mb-0">{{ $selectedData['hadir'] }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="card bg-warning text-white">
                        <div class="card-body p-3">
                            <p class="card-text small mb-0">Izin</p>
                            <h4 class="mb-0">{{ $selectedData['izin'] }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="card bg-info text-white">
                        <div class="card-body p-3">
                            <p class="card-text small mb-0">Sakit</p>
                            <h4 class="mb-0">{{ $selectedData['sakit'] }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="card bg-danger text-white">
                        <div class="card-body p-3">
                            <p class="card-text small mb-0">Alpha</p>
                            <h4 class="mb-0">{{ $selectedData['alpha'] }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-2">
                    <div class="card bg-primary text-white">
                        <div class="card-body p-3">
                            <p class="card-text small mb-0">Persentase</p>
                            <h4 class="mb-0">{{ $selectedData['percentage'] }}%</h4>
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
