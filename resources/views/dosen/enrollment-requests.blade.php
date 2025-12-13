@extends('layouts.app')

@section('title', 'Permintaan Enrollment')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Permintaan Enrollment Mata Kuliah</h2>
        
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

        <div class="card-premium">
            <h4 class="mb-3">Permintaan Enrollment Pending</h4>
            
            @if($enrollmentRequests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Mahasiswa</th>
                                <th>NIM</th>
                                <th>Mata Kuliah</th>
                                <th>Tanggal Request</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($enrollmentRequests as $request)
                                <tr>
                                    <td>{{ $request->mahasiswa->user->name }}</td>
                                    <td>{{ $request->mahasiswa->nim }}</td>
                                    <td>{{ $request->matkul->nama }}</td>
                                    <td>{{ $request->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('dosen.enrollments.approve', $request) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                                        </form>
                                        <form method="POST" action="{{ route('dosen.enrollments.reject', $request) }}" class="d-inline ms-1">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">Tidak ada permintaan enrollment yang pending</p>
            @endif
        </div>
    </div>
</div>
@endsection