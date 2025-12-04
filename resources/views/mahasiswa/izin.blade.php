@extends('layouts.app')

@section('title', 'Ajukan Izin')

@section('content')
<div class="mb-4">
    <h1 class="display-5">Ajukan Izin</h1>
</div>

<div class="card shadow">
    <div class="card-body">
        <form method="POST" action="{{ route('mahasiswa.izin.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-3">
                <label for="matkul_id" class="form-label fw-bold">Mata Kuliah <span class="text-danger">*</span></label>
                <select name="matkul_id" id="matkul_id" class="form-select @error('matkul_id') is-invalid @enderror" required>
                    <option value="">Pilih Mata Kuliah</option>
                    @foreach($matkuls as $matkul)
                    <option value="{{ $matkul->id }}" {{ old('matkul_id') == $matkul->id ? 'selected' : '' }}>
                        {{ $matkul->kode }} - {{ $matkul->nama }}
                    </option>
                    @endforeach
                </select>
                @error('matkul_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="tanggal" class="form-label fw-bold">Tanggal Izin <span class="text-danger">*</span></label>
                <input type="date" name="tanggal" id="tanggal" 
                       class="form-control @error('tanggal') is-invalid @enderror" 
                       value="{{ old('tanggal') }}" required>
                @error('tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="alasan" class="form-label fw-bold">Alasan</label>
                <textarea name="alasan" id="alasan" rows="3" 
                          class="form-control @error('alasan') is-invalid @enderror" 
                          placeholder="Masukkan alasan izin (opsional)">{{ old('alasan') }}</textarea>
                @error('alasan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="bukti_file" class="form-label fw-bold">Upload Bukti <span class="text-danger">*</span></label>
                <input type="file" name="bukti_file" id="bukti_file" 
                       class="form-control @error('bukti_file') is-invalid @enderror" 
                       accept=".pdf,.jpg,.jpeg,.png" required>
                <small class="form-text text-muted">Format: PDF, JPG, PNG (Max: 2MB)</small>
                @error('bukti_file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Ajukan Izin</button>
                <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

