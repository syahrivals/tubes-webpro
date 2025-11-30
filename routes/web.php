<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\PresenceController;
use Illuminate\Support\Facades\Route;

// Halaman utama redirect ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Route untuk user yang belum login (guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Route untuk user yang sudah login
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard utama - redirect berdasarkan role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->role == 'dosen') {
            return redirect()->route('dosen.dashboard');
        } else {
            return redirect()->route('mahasiswa.dashboard');
        }
    })->name('dashboard');

    // Route khusus untuk dosen
    Route::middleware('role:dosen')->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');
    });

    // Route khusus untuk mahasiswa
    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [MahasiswaDashboardController::class, 'profile'])->name('profile');
        Route::put('/profile', [MahasiswaDashboardController::class, 'updateProfile'])->name('profile.update');
    });

    // Route untuk presensi (hanya dosen)
    Route::middleware('role:dosen')->group(function () {
        Route::get('/presences', [PresenceController::class, 'index'])->name('presences.index');
        Route::post('/presences', [PresenceController::class, 'store'])->name('presences.store');
    });
});
