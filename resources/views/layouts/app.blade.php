<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi Presensi')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/university.css'])

    <style>
        :root {
            --primary: #1d4ed8;
            --primary-light: #3b82f6;
            --primary-dark: #1e40af;
            --secondary: #64748b;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --info: #06b6d4;
            --light: #f8fafc;
            --dark: #1e293b;
        }

        .nav-premium {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #06b6d4 100%);
            box-shadow: 0 8px 32px rgba(30, 58, 138, 0.3);
        }

        body {
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 50%, #7dd3fc 100%) !important;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        .nav-link-custom {
            color: #ffffff !important;
            opacity: 0.95;
            font-weight: 500;
            transition: 0.3s ease;
        }

        .nav-link-custom:hover {
            opacity: 1;
            transform: translateY(-2px);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .navbar-brand {
            font-weight: 700 !important;
            letter-spacing: 0.4px;
            color: #1e40af !important;
        }

        .card-premium {
            background: linear-gradient(145deg, #ffffff 0%, #f0f9ff 100%);
            border: 1px solid rgba(59, 130, 246, 0.1);
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(59, 130, 246, 0.15);
            padding: 24px;
            backdrop-filter: blur(10px);
        }

        .alert {
            border-radius: 12px !important;
            border: none !important;
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.1);
        }

        .alert-success {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%) !important;
            border: none !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 16px rgba(29, 78, 216, 0.3);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%) !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(29, 78, 216, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #64748b 0%, #475569 100%) !important;
            border: none !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 16px rgba(100, 116, 139, 0.3);
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #475569 0%, #334155 100%) !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(100, 116, 139, 0.4);
        }

        .btn-logout {
            color: #fff !important;
            font-weight: 500;
            transition: 0.3s ease;
        }

        .btn-logout:hover {
            opacity: 1;
            transform: translateY(-2px);
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-label {
            color: #374151;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            padding: 16px 0;
            box-shadow: 0 4px 20px rgba(30, 58, 138, 0.2);
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .nav a {
            color: #ffffff;
            text-decoration: none;
            margin: 0 16px;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav a:hover, .nav a.active {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        /* ===== ANIMATIONS ===== */
        @keyframes slideInLeft {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes scaleInCenter {
            from {
                transform: scale(0);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes slideInTop {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes swingInRightFwd {
            0% {
                transform: rotateY(100deg);
                transform-origin: 0% 50%;
                opacity: 0;
            }
            100% {
                transform: rotateY(0);
                transform-origin: 0% 50%;
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes bounceIn {
            0% {
                transform: scale(0.3);
                opacity: 0;
            }
            50% {
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.8);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* Animation Classes */
        .animate-slideInLeft {
            animation: slideInLeft 0.5s ease-out forwards;
        }

        .animate-slideInRight {
            animation: slideInRight 0.5s ease-out forwards;
        }

        .animate-scaleInCenter {
            animation: scaleInCenter 0.5s ease-out forwards;
        }

        .animate-slideInTop {
            animation: slideInTop 0.5s ease-out forwards;
        }

        .animate-swingInRightFwd {
            animation: swingInRightFwd 0.5s ease-out forwards;
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .animate-bounceIn {
            animation: bounceIn 0.8s ease-out forwards;
        }

        .animate-zoomIn {
            animation: zoomIn 0.5s ease-out forwards;
        }

        /* Staggered animations */
        .animate-delay-100 { animation-delay: 0.1s; }
        .animate-delay-200 { animation-delay: 0.2s; }
        .animate-delay-300 { animation-delay: 0.3s; }
        .animate-delay-400 { animation-delay: 0.4s; }
        .animate-delay-500 { animation-delay: 0.5s; }

        /* Page entrance animations */
        .page-enter {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .card-enter {
            animation: zoomIn 0.6s ease-out forwards;
        }

        .stagger-children > * {
            opacity: 0;
            animation: fadeInUp 0.6s ease-out forwards;
        }

        .stagger-children > *:nth-child(1) { animation-delay: 0.1s; }
        .stagger-children > *:nth-child(2) { animation-delay: 0.2s; }
        .stagger-children > *:nth-child(3) { animation-delay: 0.3s; }
        .stagger-children > *:nth-child(4) { animation-delay: 0.4s; }
        .stagger-children > *:nth-child(5) { animation-delay: 0.5s; }
        .stagger-children > *:nth-child(6) { animation-delay: 0.6s; }
        .stagger-children > *:nth-child(7) { animation-delay: 0.7s; }
        .stagger-children > *:nth-child(8) { animation-delay: 0.8s; }
    </style>
</head>

<body>
    <header class="header">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div class="logo">
                <!-- <img src="/favicon.ico" alt="Logo" style="height:32px;vertical-align:middle;margin-right:10px;"> -->
                Kitpeyut University
            </div>
            @auth
            <nav class="nav">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
                
                <!-- Notifications Dropdown -->
                <div class="dropdown">
                    <a href="#" class="dropdown-toggle" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="badge bg-danger">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown" style="min-width: 300px;">
                        <li><h6 class="dropdown-header">Notifikasi</h6></li>
                        @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                        <li>
                            <a class="dropdown-item {{ !$notification->read_at ? 'fw-bold' : '' }}" href="{{ route('notifications.index') }}">
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small><br>
                                {{ \Illuminate\Support\Str::limit($notification->data['message'] ?? 'Notifikasi baru', 50) }}
                            </a>
                        </li>
                        @empty
                        <li><span class="dropdown-item-text text-muted">Tidak ada notifikasi baru</span></li>
                        @endforelse
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-center" href="{{ route('notifications.index') }}">Lihat Semua</a></li>
                    </ul>
                </div>
                
                @if(auth()->user()->isDosen())
                    <a href="{{ route('presences.index') }}" class="{{ request()->routeIs('presences.*') ? 'active' : '' }}">Presensi</a>
                    <a href="{{ route('dosen.matkuls.index') }}" class="{{ request()->routeIs('dosen.matkuls.*') ? 'active' : '' }}">Mata Kuliah</a>
                    <a href="{{ route('dosen.schedule.index') }}" class="{{ request()->routeIs('dosen.schedule.*') ? 'active' : '' }}">Jadwal</a>
                    <a href="{{ route('dosen.enrollments.index') }}" class="{{ request()->routeIs('dosen.enrollments.*') ? 'active' : '' }}">Enrollment</a>
                    <a href="{{ route('dosen.reports.index') }}" class="{{ request()->routeIs('dosen.reports.*') ? 'active' : '' }}">Laporan</a>
                    <a href="{{ route('analytics.index') }}" class="{{ request()->routeIs('analytics.*') ? 'active' : '' }}">Analytics</a>
                @else
                    <a href="{{ route('mahasiswa.enrollments.index') }}" class="{{ request()->routeIs('mahasiswa.enrollments.*') ? 'active' : '' }}">Enrollment</a>
                    <a href="{{ route('mahasiswa.reports.student') }}" class="{{ request()->routeIs('mahasiswa.reports.*') ? 'active' : '' }}">Laporan</a>
                    <a href="{{ route('analytics.index') }}" class="{{ request()->routeIs('analytics.*') ? 'active' : '' }}">Analytics</a>
                    <a href="{{ route('mahasiswa.profile') }}" class="{{ request()->routeIs('mahasiswa.profile') ? 'active' : '' }}">Profil</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-secondary" style="margin-left:16px;">Logout</button>
                </form>
            </nav>
            @endauth
        </div>
    </header>


    <main class="container page-enter" style="margin-top:40px;max-width:900px;">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm animate-fadeInUp">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm animate-fadeInUp animate-delay-100">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm animate-fadeInUp animate-delay-200">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card-premium card-enter">
            @yield('content')
        </div>
    </main>
</body>
</html>
