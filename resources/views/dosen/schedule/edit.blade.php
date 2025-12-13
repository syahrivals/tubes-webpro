@extends('layouts.app')

@section('title', 'Edit Jadwal Kuliah')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Jadwal Kuliah
                    </h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('dosen.schedule.update', $matkul) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label">Nama Mata Kuliah <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                       id="nama" name="nama" value="{{ old('nama', $matkul->nama) }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="hari" class="form-label">Hari <span class="text-danger">*</span></label>
                                <select class="form-select @error('hari') is-invalid @enderror"
                                        id="hari" name="hari" required>
                                    <option value="">Pilih Hari</option>
                                    <option value="senin" {{ old('hari', $matkul->hari) == 'senin' ? 'selected' : '' }}>Senin</option>
                                    <option value="selasa" {{ old('hari', $matkul->hari) == 'selasa' ? 'selected' : '' }}>Selasa</option>
                                    <option value="rabu" {{ old('hari', $matkul->hari) == 'rabu' ? 'selected' : '' }}>Rabu</option>
                                    <option value="kamis" {{ old('hari', $matkul->hari) == 'kamis' ? 'selected' : '' }}>Kamis</option>
                                    <option value="jumat" {{ old('hari', $matkul->hari) == 'jumat' ? 'selected' : '' }}>Jumat</option>
                                    <option value="sabtu" {{ old('hari', $matkul->hari) == 'sabtu' ? 'selected' : '' }}>Sabtu</option>
                                    <option value="minggu" {{ old('hari', $matkul->hari) == 'minggu' ? 'selected' : '' }}>Minggu</option>
                                </select>
                                @error('hari')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jam" class="form-label">Jam <span class="text-danger">*</span></label>
                                <input type="time" class="form-control @error('jam') is-invalid @enderror"
                                       id="jam" name="jam" value="{{ old('jam', $matkul->jam) }}" required>
                                @error('jam')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="ruangan" class="form-label">Ruangan</label>
                                <input type="text" class="form-control @error('ruangan') is-invalid @enderror"
                                       id="ruangan" name="ruangan" value="{{ old('ruangan', $matkul->ruangan) }}"
                                       placeholder="Contoh: Ruang 101">
                                @error('ruangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                      id="deskripsi" name="deskripsi" rows="3"
                                      placeholder="Deskripsi mata kuliah...">{{ old('deskripsi', $matkul->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('dosen.schedule.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Update Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection