@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-bell me-2"></i>Notifikasi
                    </h4>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                    <form method="POST" action="{{ route('notifications.markAllAsRead') }}" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-light btn-sm">
                            <i class="fas fa-check-double me-1"></i>Tandai Semua Dibaca
                        </button>
                    </form>
                    @endif
                </div>
                <div class="card-body">
                    @if($notifications->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                            <div class="list-group-item px-0 {{ $notification->read_at ? 'bg-light' : 'bg-primary bg-opacity-10' }}">
                                <div class="d-flex w-100 justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            @if(!$notification->read_at)
                                            <span class="badge bg-primary me-2">Baru</span>
                                            @endif
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                            </small>
                                        </div>

                                        @if($notification->type === 'App\\Notifications\\AttendanceMarked')
                                            <div class="mb-2">
                                                <i class="fas fa-user-check text-success me-2"></i>
                                                <strong>Presensi Dicatat</strong>
                                            </div>
                                            <p class="mb-2">{{ $notification->data['message'] ?? 'Presensi Anda telah dicatat.' }}</p>
                                            @if(isset($notification->data['tanggal']))
                                            <small class="text-muted">
                                                Tanggal: {{ \Carbon\Carbon::parse($notification->data['tanggal'])->format('d/m/Y') }}
                                            </small>
                                            @endif
                                        @elseif($notification->type === 'App\\Notifications\\IzinSubmitted')
                                            <div class="mb-2">
                                                <i class="fas fa-file-alt text-warning me-2"></i>
                                                <strong>Permohonan Izin</strong>
                                            </div>
                                            <p class="mb-2">{{ $notification->data['message'] ?? 'Ada permohonan izin baru.' }}</p>
                                        @elseif($notification->type === 'App\\Notifications\\EnrollmentApproved')
                                            <div class="mb-2">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                <strong>Enrollment Disetujui</strong>
                                            </div>
                                            <p class="mb-2">{{ $notification->data['message'] ?? 'Enrollment Anda telah disetujui.' }}</p>
                                        @else
                                            <div class="mb-2">
                                                <i class="fas fa-info-circle text-info me-2"></i>
                                                <strong>Notifikasi</strong>
                                            </div>
                                            <p class="mb-2">{{ $notification->data['message'] ?? 'Ada notifikasi baru.' }}</p>
                                        @endif
                                    </div>

                                    <div class="d-flex gap-2">
                                        @if(!$notification->read_at)
                                        <form method="POST" action="{{ route('notifications.markAsRead', $notification) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-outline-primary btn-sm" title="Tandai Dibaca">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        @endif

                                        <form method="POST" action="{{ route('notifications.destroy', $notification) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada notifikasi</h5>
                            <p class="text-muted">Notifikasi akan muncul di sini ketika ada aktivitas baru.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection