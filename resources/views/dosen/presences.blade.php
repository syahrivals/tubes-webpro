@extends('layouts.app')

@section('title', 'Tabel Presensi')

@section('content')
<div class="mb-4">
    <h1 class="display-5">Tabel Presensi</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('presences.index') }}" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label fw-bold">Mata Kuliah</label>
                <select name="matkul_id" class="form-select" required>
                    @foreach($matkuls as $m)
                        <option value="{{ $m->id }}" {{ ($matkul && $matkul->id == $m->id) ? 'selected' : '' }}>
                            {{ $m->kode }} - {{ $m->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label fw-bold">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
            </div>
        </form>
    </div>
</div>

@if($matkul && $mahasiswas->count() > 0)
<form method="POST" action="{{ route('presences.store') }}">
    @csrf
    <input type="hidden" name="matkul_id" value="{{ $matkul->id }}">
    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
    
    <div class="card shadow">
        <div class="card-body">
            <h2 class="card-title mb-4">{{ $matkul->nama }} - {{ date('d/m/Y', strtotime($tanggal)) }}</h2>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th class="text-center">Hadir</th>
                            <th class="text-center">Izin</th>
                            <th class="text-center">Sakit</th>
                            <th class="text-center">Alpha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($mahasiswas as $mhs)
                        @php
                            $presence = $presences[$mhs->id] ?? null;
                            $currentStatus = $presence ? $presence->status : 'alpha';
                        @endphp
                        <tr>
                            <td>{{ $mhs->nim }}</td>
                            <td>{{ $mhs->user->name }}</td>
                            <td class="text-center">
                                <input type="radio" name="presences[{{ $loop->index }}][status]" value="hadir" 
                                       {{ $currentStatus == 'hadir' ? 'checked' : '' }} class="form-check-input">
                            </td>
                            <td class="text-center">
                                <input type="radio" name="presences[{{ $loop->index }}][status]" value="izin" 
                                       {{ $currentStatus == 'izin' ? 'checked' : '' }} class="form-check-input">
                            </td>
                            <td class="text-center">
                                <input type="radio" name="presences[{{ $loop->index }}][status]" value="sakit" 
                                       {{ $currentStatus == 'sakit' ? 'checked' : '' }} class="form-check-input">
                            </td>
                            <td class="text-center">
                                <input type="radio" name="presences[{{ $loop->index }}][status]" value="alpha" 
                                       {{ $currentStatus == 'alpha' ? 'checked' : '' }} class="form-check-input">
                                <input type="hidden" name="presences[{{ $loop->index }}][mahasiswa_id]" value="{{ $mhs->id }}">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-success">Simpan Presensi</button>
            </div>
        </div>
    </div>
</form>
@elseif($matkul)
    <div class="card shadow">
        <div class="card-body">
            <p class="text-muted mb-0">Tidak ada mahasiswa yang terdaftar pada mata kuliah ini.</p>
        </div>
    </div>
@endif
@endsection
