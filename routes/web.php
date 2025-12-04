<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\IzinController as MahasiswaIzinController;
use App\Http\Controllers\PresenceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->role == 'dosen') {
            return redirect()->route('dosen.dashboard');
        } else {
            return redirect()->route('mahasiswa.dashboard');
        }
    })->name('dashboard');

    Route::middleware('role:dosen')->prefix('dosen')->name('dosen.')->group(function () {
        Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');
        Route::get('/qr-code', [DosenDashboardController::class, 'qrCode'])->name('qr-code');
    });

    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [MahasiswaDashboardController::class, 'profile'])->name('profile');
        Route::put('/profile', [MahasiswaDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::get('/izin', [MahasiswaIzinController::class, 'create'])->name('izin.create');
        Route::post('/izin', [MahasiswaIzinController::class, 'store'])->name('izin.store');
    });

    Route::middleware('role:dosen')->group(function () {
        Route::get('/presences', [PresenceController::class, 'index'])->name('presences.index');
        Route::post('/presences', [PresenceController::class, 'store'])->name('presences.store');
    });
});
