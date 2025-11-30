<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data mahasiswa dari user yang login
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        
        // Ambil semua mata kuliah yang diambil mahasiswa ini
        $matkuls = $mahasiswa->matkuls;
        
        // Siapkan array untuk menyimpan data kehadiran
        $attendanceData = [];
        
        // Loop untuk setiap mata kuliah
        foreach ($matkuls as $matkul) {
            // Ambil semua presensi mahasiswa ini untuk mata kuliah ini
            $allPresences = Presence::where('matkul_id', $matkul->id)
                ->where('mahasiswa_id', $mahasiswa->id)
                ->orderBy('tanggal', 'desc')
                ->get();
            
            // Hitung jumlah untuk setiap status
            $hadir = 0;
            $izin = 0;
            $sakit = 0;
            $alpha = 0;
            
            foreach ($allPresences as $presence) {
                if ($presence->status == 'hadir') {
                    $hadir++;
                } elseif ($presence->status == 'izin') {
                    $izin++;
                } elseif ($presence->status == 'sakit') {
                    $sakit++;
                } elseif ($presence->status == 'alpha') {
                    $alpha++;
                }
            }
            
            // Hitung total dan persentase
            $total = count($allPresences);
            if ($total > 0) {
                $percentage = round(($hadir / $total) * 100, 2);
            } else {
                $percentage = 0;
            }
            
            // Simpan data ke array
            $attendanceData[$matkul->id] = [
                'matkul' => $matkul,
                'hadir' => $hadir,
                'izin' => $izin,
                'sakit' => $sakit,
                'alpha' => $alpha,
                'total' => $total,
                'percentage' => $percentage,
                'presences' => $allPresences,
            ];
        }

        return view('mahasiswa.dashboard', compact('mahasiswa', 'attendanceData'));
    }

    public function profile()
    {
        // Ambil data mahasiswa dari user yang login
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        
        return view('mahasiswa.profile', compact('mahasiswa'));
    }

    public function updateProfile(Request $request)
    {
        // Validasi input
        $request->validate([
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|string|max:255',
        ]);
        
        // Ambil data mahasiswa dari user yang login
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        
        // Update data
        $mahasiswa->phone = $request->phone;
        $mahasiswa->photo = $request->photo;
        $mahasiswa->save();

        return redirect()->route('mahasiswa.profile')->with('success', 'Profil berhasil diperbarui');
    }
}

