@extends('layouts.app')

@section('title', 'Enrollment Mata Kuliah')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Enrollment Mata Kuliah</h2>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-md-8">
                <div class="card-premium mb-4">
                    <h4 class="mb-3">Mata Kuliah Tersedia</h4>
                    <div class="row">
                        @foreach($availableMatkuls as $matkul)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $matkul->nama }}</h5>
                                        <p class="card-text">
                                            <strong>Dosen:</strong> {{ $matkul->dosen->name }}<br>
                                            <strong>Hari:</strong> {{ $matkul->hari }}<br>
                                            <strong>Jam:</strong> {{ $matkul->jam }}
                                        </p>
                                        @php
                                            $existingEnrollment = $enrollments->firstWhere('matkul_id', $matkul->id);
                                        @endphp
                                        @if($existingEnrollment)
                                            @if($existingEnrollment->status == 'pending')
                                                <span class="badge bg-warning">Menunggu Approval</span>
                                            @elseif($existingEnrollment->status == 'approved')
                                                <span class="badge bg-success">Sudah Terdaftar</span>
                                            @else
                                                <span class="badge bg-danger">Ditolak</span>
                                            @endif
                                        @else
                                            <form method="POST" action="{{ route('mahasiswa.enrollments.request', $matkul) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm">Ajukan Enrollment</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card-premium">
                    <h4 class="mb-3">Status Enrollment</h4>
                    @if($enrollments->count() > 0)
                        @foreach($enrollments as $enrollment)
                            <div class="mb-2 p-2 border rounded">
                                <strong>{{ $enrollment->matkul->nama }}</strong><br>
                                @if($enrollment->status == 'pending')
                                    <span class="badge bg-warning">Menunggu Approval</span>
                                @elseif($enrollment->status == 'approved')
                                    <span class="badge bg-success">Disetujui</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">Belum ada permintaan enrollment</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection