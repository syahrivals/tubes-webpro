@extends('layouts.app')

@section('title', 'Validasi Izin')

@section('content')
<div class="mb-4">
    <h1 class="display-5" style="font-weight:800;color:var(--primary);">Validasi Izin Mahasiswa</h1>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('dosen.izin.index') }}" class="row g-3 align-items-end">
            <div class="col-md-10">
                <label class="form-label fw-bold">Filter Mata Kuliah</label>
                <select name="matkul_id" class="form-select" onchange="this.form.submit();">
                    <option value="">Semua Mata Kuliah</option>
                    @foreach($matkuls as $m)
                        <option value="{{ $m->id }}" {{ $selectedMatkulId == $m->id ? 'selected' : '' }}>
                            {{ $m->kode }} - {{ $m->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <a href="{{ route('dosen.izin.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Mata Kuliah</th>
                        <th>Tanggal</th>
                        <th>Alasan</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($izins as $izin)
                    <tr>
                        <td>
                            <div>
                                <strong>{{ $izin->mahasiswa->user->name }}</strong><br>
                                <small class="text-muted">{{ $izin->mahasiswa->nim }}</small>
                            </div>
                        </td>
                        <td>{{ $izin->matkul->kode }} - {{ $izin->matkul->nama }}</td>
                        <td>{{ $izin->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $izin->alasan ?? '-' }}</td>
                        <td>
                            @if($izin->bukti_file)
                                <a href="{{ asset('storage/' . $izin->bukti_file) }}" target="_blank" class="btn btn-sm btn-outline-primary" style="border-radius:8px;">
                                    📄 Lihat Bukti
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($izin->status == 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($izin->status == 'approved')
                                <span class="badge badge-success">Approved</span>
                            @else
                                <span class="badge badge-danger">Rejected</span>
                            @endif
                        </td>
                        <td>
                            @if($izin->status == 'pending')
                                <div class="d-flex gap-2">
                                    <form method="POST" action="{{ route('dosen.izin.approve', $izin->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" style="border-radius:8px;" onclick="return confirm('Setujui izin ini?')">
                                            ✓ Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('dosen.izin.reject', $izin->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" style="border-radius:8px;" onclick="return confirm('Tolak izin ini?')">
                                            ✗ Reject
                                        </button>
                                    </form>
                                </div>
                            @else
                                <small class="text-muted">
                                    Validasi: {{ $izin->validated_at ? $izin->validated_at->format('d/m/Y H:i') : '-' }}
                                </small>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Tidak ada data izin</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

