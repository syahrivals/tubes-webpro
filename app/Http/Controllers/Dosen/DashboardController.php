<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Matkul;
use App\Models\Enrollment;
use App\Models\Presence;
use App\Notifications\EnrollmentApproved;

class DashboardController extends Controller
{
    public function index()
    {
    $user = auth()->user();
    $matkuls = Matkul::where('dosen_id', $user->id)->get();
    // Ambil notifikasi izin terbaru
    $notifications = $user->notifications()->where('type', 'App\\Notifications\\IzinSubmitted')->latest()->take(10)->get();
        
        $totalMahasiswa = 0;
        $mahasiswaIds = [];
        
        foreach ($matkuls as $matkul) {
            $enrollments = Enrollment::where('matkul_id', $matkul->id)->where('status', 'approved')->get();
            
            foreach ($enrollments as $enrollment) {
                if (!in_array($enrollment->mahasiswa_id, $mahasiswaIds)) {
                    $mahasiswaIds[] = $enrollment->mahasiswa_id;
                    $totalMahasiswa++;
                }
            }
        }
        
        $attendanceStats = [];
        $chartLabels = [];
        $chartData = [];
        $mahasiswaCounts = [];
        
        foreach ($matkuls as $matkul) {
            $enrollments = Enrollment::where('matkul_id', $matkul->id)->where('status', 'approved')->get();
            $mahasiswaCounts[$matkul->id] = count($enrollments);
            
            $allPresences = Presence::where('matkul_id', $matkul->id)->get();
            $totalPresences = count($allPresences);
            
            $hadir = 0;
            foreach ($allPresences as $presence) {
                if ($presence->status == 'hadir') {
                    $hadir++;
                }
            }
            
            if ($totalPresences > 0) {
                $percentage = round(($hadir / $totalPresences) * 100, 2);
            } else {
                $percentage = 0;
            }
            
            $attendanceStats[$matkul->id] = $percentage;
            $chartLabels[] = $matkul->nama;
            $chartData[] = $percentage;
        }

        // determine today's mata kuliah based on 'hari' column (if set)
        $todayName = strtolower(now()->locale('id')->isoFormat('dddd'));
        $todaysMatkuls = $matkuls->filter(function ($m) use ($todayName) {
            return $m->hari && strtolower($m->hari) === $todayName;
        });

    return view('dosen.dashboard', compact('matkuls', 'totalMahasiswa', 'attendanceStats', 'chartLabels', 'chartData', 'mahasiswaCounts', 'todaysMatkuls', 'notifications'));
    }

    public function qrCode()
    {
        $user = auth()->user();
        $matkuls = Matkul::where('dosen_id', $user->id)->get();

        $matkulId = request()->get('matkul_id') ?? ($matkuls->first()->id ?? null);

        if (!$matkulId) {
            return redirect()->route('dosen.dashboard')->with('error', 'Tidak ada mata kuliah untuk membuat QR');
        }

        $token = bin2hex(random_bytes(16));
        $expiresAt = now()->addMinutes(15);

        // create db session
        \App\Models\QrSession::create([
            'matkul_id' => $matkulId,
            'token' => $token,
            'expires_at' => $expiresAt,
            'created_by' => $user->id,
        ]);

        $expiresAtTimestamp = $expiresAt->timestamp * 1000;

        return view('dosen.qr-code', compact('token', 'expiresAt', 'expiresAtTimestamp', 'matkulId', 'matkuls'));
    }

    public function enrollmentRequests()
    {
        $user = auth()->user();
        $matkuls = \App\Models\Matkul::where('dosen_id', $user->id)->get();
        
        $enrollmentRequests = \App\Models\Enrollment::whereHas('matkul', function($query) use ($user) {
            $query->where('dosen_id', $user->id);
        })
        ->where('status', 'pending')
        ->with(['mahasiswa.user', 'matkul'])
        ->get();

        return view('dosen.enrollment-requests', compact('enrollmentRequests', 'matkuls'));
    }

    public function approveEnrollment(\App\Models\Enrollment $enrollment)
    {
        // Check if the dosen owns this matkul
        $user = auth()->user();
        if ($enrollment->matkul->dosen_id !== $user->id) {
            abort(403);
        }
        
        $enrollment->update(['status' => 'approved']);

        // Send notification to student
        $mahasiswa = $enrollment->mahasiswa;
        if ($mahasiswa && $mahasiswa->user) {
            $mahasiswa->user->notify(new EnrollmentApproved($enrollment));
        }
        
        return back()->with('success', 'Enrollment request approved');
    }

    public function rejectEnrollment(\App\Models\Enrollment $enrollment)
    {
        // Check if the dosen owns this matkul
        $user = auth()->user();
        if ($enrollment->matkul->dosen_id !== $user->id) {
            abort(403);
        }
        
        $enrollment->update(['status' => 'rejected']);
        
        return back()->with('success', 'Enrollment request rejected');
    }
}

