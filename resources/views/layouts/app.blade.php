<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplikasi Presensi')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Premium Blue Gradient */
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
    @auth
    <nav class="navbar nav-premium navbar-dark shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">Aplikasi Presensi</a>

            <div class="d-flex gap-4">
                <a href="{{ route('dashboard') }}" class="nav-link-custom mt-1">Dashboard</a>

                @if(auth()->user()->isDosen())
                    <a href="{{ route('presences.index') }}" class="nav-link-custom mt-1">Presensi</a>
                @else
                    <a href="{{ route('mahasiswa.profile') }}" class="nav-link-custom mt-1">Profil</a>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link btn-logout text-decoration-none p-0 border-0">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>
    @endauth

    <main class="container my-4">
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
