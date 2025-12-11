@extends('layouts.app')
@section('title', isset($matkul) ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah')
@section('content')
<div class="container" style="max-width:600px;">
    <div class="card mb-4">
        <div class="card-body">
            <h1 class="mb-4" style="font-weight:700;color:var(--primary);">{{ isset($matkul) ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah' }}</h1>
            <form method="POST" action="{{ isset($matkul) ? route('dosen.matkuls.update', $matkul->id) : route('dosen.matkuls.store') }}">
                @csrf
                @if(isset($matkul)) @method('PUT') @endif
                <div class="mb-3">
                    <label for="kode" class="form-label">Kode</label>
                    <input type="text" class="form-control" name="kode" id="kode" value="{{ old('kode', $matkul->kode ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama</label>
                    <input type="text" class="form-control" name="nama" id="nama" value="{{ old('nama', $matkul->nama ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label for="hari" class="form-label">Hari</label>
                    <select name="hari" id="hari" class="form-select" required>
                        @foreach(['senin','selasa','rabu','kamis','jumat','sabtu','minggu'] as $h)
                        <option value="{{ $h }}" {{ old('hari', $matkul->hari ?? '') == $h ? 'selected' : '' }}>{{ ucfirst($h) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="jam" class="form-label">Jam (misal: 08:00-10:00)</label>
                    <input type="text" class="form-control" name="jam" id="jam" value="{{ old('jam', $matkul->jam ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label for="semester" class="form-label">Semester</label>
                    <input type="number" class="form-control" name="semester" id="semester" value="{{ old('semester', $matkul->semester ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label for="credits" class="form-label">SKS</label>
                    <input type="number" class="form-control" name="credits" id="credits" value="{{ old('credits', $matkul->credits ?? '') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pilih Mahasiswa yang Mengikuti Mata Kuliah (bisa lebih dari satu):</label>
                    <div class="border rounded p-2" style="max-height:300px;overflow-y:auto;">
                        @foreach($allMahasiswas as $m)
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="mahasiswas[]" value="{{ $m->id }}" id="mhs{{ $m->id }}"
                                {{ (isset($matkul) && $matkul->mahasiswas->contains($m->id)) ? 'checked' : '' }}>
                            <label class="form-check-label" for="mhs{{ $m->id }}">
                                {{ $m->user->name }} ({{ $m->nim }})
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="border-radius:10px;font-weight:600;">Simpan</button>
                <a href="{{ route('dosen.dashboard') }}" class="btn btn-secondary" style="border-radius:10px;font-weight:600;">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
