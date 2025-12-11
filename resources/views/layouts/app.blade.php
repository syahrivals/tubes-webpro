<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi Presensi')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/university.css'])

    <style>
        .nav-premium {
            background: linear-gradient(135deg, #3A7BD5 0%, #00D2FF 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        }

        body {
            background: #f6f9fc !important;
            font-family: 'Inter', sans-serif;
        }

        .nav-link-custom {
            color: #ffffff !important;
            opacity: 0.95;
            font-weight: 500;
            transition: 0.25s;
        }

        .nav-link-custom:hover {
            opacity: 1;
            transform: translateY(-2px);
        }

        .navbar-brand {
            font-weight: 700 !important;
            letter-spacing: 0.4px;
        }

        .card-premium {
            background: #ffffff;
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            padding: 20px;
        }

        .alert {
            border-radius: 12px !important;
        }

        .btn-logout {
            color: #fff !important;
            font-weight: 500;
            transition: 0.25s;
        }

        .btn-logout:hover {
            opacity: 1;
            transform: translateY(-2px);
        }
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
                @if(auth()->user()->isDosen())
                    <a href="{{ route('presences.index') }}" class="{{ request()->routeIs('presences.*') ? 'active' : '' }}">Presensi</a>
                    <a href="{{ route('dosen.matkuls.index') }}" class="{{ request()->routeIs('dosen.matkuls.*') ? 'active' : '' }}">Mata Kuliah</a>
                @else
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


    <main class="container" style="margin-top:40px;max-width:900px;">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card-premium">
            @yield('content')
        </div>
    </main>
</body>
</html>
