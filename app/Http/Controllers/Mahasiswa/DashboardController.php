<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $mahasiswa = auth()->user()->mahasiswa;
        $matkuls = $mahasiswa->matkuls;

        $attendanceData = [];
        foreach ($matkuls as $matkul) {
            $presences = $matkul->presences()
                ->where('mahasiswa_id', $mahasiswa->id)
                ->orderBy('tanggal', 'desc')
                ->get();
            $hadir = $presences->where('status', 'hadir')->count();
            $izin = $presences->where('status', 'izin')->count();
            $sakit = $presences->where('status', 'sakit')->count();
            $alpha = $presences->where('status', 'alpha')->count();
            $total = $presences->count();
            $percentage = $total > 0 ? round(($hadir / $total) * 100, 2) : 0;

            $attendanceData[$matkul->id] = [
                'matkul' => $matkul,
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpha' => $alpha,
                'total' => $total,
                'percentage' => $percentage,
                'presences' => $presences,
            ];
        }

        return view('mahasiswa.dashboard', compact('mahasiswa', 'attendanceData'));
    }

    public function profile()
    {
        $mahasiswa = auth()->user()->mahasiswa;
        return view('mahasiswa.profile', compact('mahasiswa'));
    }

    public function updateProfile(Request $request)
    {
        $mahasiswa = auth()->user()->mahasiswa;
        
        $request->validate([
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|string|max:255',
        ]);

        $mahasiswa->update($request->only('phone', 'photo'));

        return redirect()->route('mahasiswa.profile')->with('success', 'Profil berhasil diperbarui');
    }
}

