<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Dosen\IzinController as DosenIzinController;
use App\Http\Controllers\Dosen\MatkulController as DosenMatkulController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\IzinController as MahasiswaIzinController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Dosen\ScheduleController as DosenScheduleController;
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
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::patch('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unreadCount');

    // Analytics
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    
    // Test Dashboard with animations
    Route::get('/test-dashboard', function () {
        return view('dashboard');
    })->name('test.dashboard');
    
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return $user->role === 'dosen'
            ? redirect()->route('dosen.dashboard')
            : redirect()->route('mahasiswa.dashboard');
    })->name('dashboard');

    // =======================
    // ROUTE DOSEN
    // =======================
    Route::middleware('role:dosen')
        ->prefix('dosen')
        ->name('dosen.')
        ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');
        Route::get('/qr-code', [DosenDashboardController::class, 'qrCode'])->name('qr-code');

        // Izin mahasiswa
        Route::get('/izin', [DosenIzinController::class, 'index'])->name('izin.index');
        Route::post('/izin/{id}/approve', [DosenIzinController::class, 'approve'])->name('izin.approve');
        Route::post('/izin/{id}/reject', [DosenIzinController::class, 'reject'])->name('izin.reject');

        // Enrollment requests
        Route::get('/enrollments', [DosenDashboardController::class, 'enrollmentRequests'])->name('enrollments.index');
        Route::patch('/enrollments/{enrollment}/approve', [DosenDashboardController::class, 'approveEnrollment'])->name('enrollments.approve');
        Route::patch('/enrollments/{enrollment}/reject', [DosenDashboardController::class, 'rejectEnrollment'])->name('enrollments.reject');

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::post('/reports/generate', [ReportController::class, 'generate'])->name('reports.generate');

        // Schedule Management
        Route::resource('schedule', DosenScheduleController::class)->parameters(['schedule' => 'matkul']);
        Route::get('/schedule-calendar', [DosenScheduleController::class, 'calendar'])->name('schedule.calendar');
        Route::get('/schedule-events', [DosenScheduleController::class, 'getEvents'])->name('schedule.events');
    Route::resource('matkuls', DosenMatkulController::class);
    Route::get('/matkul/create', [DosenMatkulController::class, 'create'])->name('dosen.matkul.create');
    // Tetap tambahkan route manual jika ada custom logic di create/edit/destroy
    });

    // =======================
    // ROUTE MAHASISWA
    // =======================
    Route::middleware('role:mahasiswa')
        ->prefix('mahasiswa')
        ->name('mahasiswa.')
        ->group(function () {

        Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [MahasiswaDashboardController::class, 'profile'])->name('profile');
        Route::put('/profile', [MahasiswaDashboardController::class, 'updateProfile'])->name('profile.update');

        Route::get('/izin', [MahasiswaIzinController::class, 'create'])->name('izin.create');
        Route::post('/izin', [MahasiswaIzinController::class, 'store'])->name('izin.store');
        
        // Enrollment routes
        Route::get('/enrollments', [MahasiswaDashboardController::class, 'enrollments'])->name('enrollments.index');
        Route::post('/enrollments/{matkul}', [MahasiswaDashboardController::class, 'requestEnrollment'])->name('enrollments.request');

        // Reports
        Route::get('/reports', [ReportController::class, 'studentReport'])->name('reports.student');

        Route::get('/scan', [\App\Http\Controllers\Mahasiswa\ScanController::class, 'index'])->name('scan.index');
        Route::post('/scan', [\App\Http\Controllers\Mahasiswa\ScanController::class, 'store'])->name('scan.store');
    });

    // =======================
    // PRESENSI DOSEN
    // =======================
    Route::middleware('role:dosen')->group(function () {
        Route::get('/presences', [PresenceController::class, 'index'])->name('presences.index');
        Route::post('/presences', [PresenceController::class, 'store'])->name('presences.store');
    });
});
