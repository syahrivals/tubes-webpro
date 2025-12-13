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
