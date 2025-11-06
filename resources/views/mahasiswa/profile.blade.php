@extends('layouts.app')

@section('title', 'Profil Mahasiswa')

@section('content')
<div class="mb-4">
    <h1 class="display-5">Profil Mahasiswa</h1>
</div>

<div class="card shadow">
    <div class="card-body">
        <form method="POST" action="{{ route('mahasiswa.profile.update') }}">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label fw-bold">Nama</label>
                <input type="text" value="{{ $mahasiswa->user->name }}" disabled class="form-control bg-light">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Email</label>
                <input type="email" value="{{ $mahasiswa->user->email }}" disabled class="form-control bg-light">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">NIM</label>
                <input type="text" value="{{ $mahasiswa->nim }}" disabled class="form-control bg-light">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Jurusan</label>
                <input type="text" value="{{ $mahasiswa->jurusan }}" disabled class="form-control bg-light">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Angkatan</label>
                <input type="text" value="{{ $mahasiswa->angkatan }}" disabled class="form-control bg-light">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $mahasiswa->phone) }}" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Photo URL</label>
                <input type="text" name="photo" value="{{ old('photo', $mahasiswa->photo) }}" 
                       class="form-control" placeholder="https://example.com/photo.jpg">
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
