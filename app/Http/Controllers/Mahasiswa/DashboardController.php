<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        
        $matkuls = $mahasiswa->matkuls;
        
        $attendanceData = [];
        
        foreach ($matkuls as $matkul) {
            $allPresences = Presence::where('matkul_id', $matkul->id)
                ->where('mahasiswa_id', $mahasiswa->id)
                ->orderBy('tanggal', 'desc')
                ->get();
            
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
            
            $total = count($allPresences);
            if ($total > 0) {
                $percentage = round(($hadir / $total) * 100, 2);
            } else {
                $percentage = 0;
            }
            
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
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        
        return view('mahasiswa.profile', compact('mahasiswa'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'phone' => 'nullable|string|max:20',
            'photo' => 'nullable|string|max:255',
        ]);
        
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        
        $mahasiswa->phone = $request->phone;
        $mahasiswa->photo = $request->photo;
        $mahasiswa->save();

        return redirect()->route('mahasiswa.profile')->with('success', 'Profil berhasil diperbarui');
    }
}

