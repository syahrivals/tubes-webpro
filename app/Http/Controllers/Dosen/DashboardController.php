<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Matkul;
use App\Models\Enrollment;
use App\Models\Presence;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua mata kuliah yang diajar dosen ini
        $user = auth()->user();
        $matkuls = Matkul::where('dosen_id', $user->id)->get();
        
        // Hitung total mahasiswa unik dari semua mata kuliah
        $totalMahasiswa = 0;
        $mahasiswaIds = [];
        
        foreach ($matkuls as $matkul) {
            // Ambil semua enrollment untuk mata kuliah ini
            $enrollments = Enrollment::where('matkul_id', $matkul->id)->get();
            
            foreach ($enrollments as $enrollment) {
                // Simpan ID mahasiswa jika belum ada
                if (!in_array($enrollment->mahasiswa_id, $mahasiswaIds)) {
                    $mahasiswaIds[] = $enrollment->mahasiswa_id;
                    $totalMahasiswa++;
                }
            }
        }
        
        // Hitung persentase kehadiran untuk setiap mata kuliah
        $attendanceStats = [];
        $chartLabels = [];
        $chartData = [];
        $mahasiswaCounts = [];
        
        foreach ($matkuls as $matkul) {
            // Hitung jumlah mahasiswa di mata kuliah ini
            $enrollments = Enrollment::where('matkul_id', $matkul->id)->get();
            $mahasiswaCounts[$matkul->id] = count($enrollments);
            
            // Ambil semua presensi untuk mata kuliah ini
            $allPresences = Presence::where('matkul_id', $matkul->id)->get();
            $totalPresences = count($allPresences);
            
            // Hitung yang hadir
            $hadir = 0;
            foreach ($allPresences as $presence) {
                if ($presence->status == 'hadir') {
                    $hadir++;
                }
            }
            
            // Hitung persentase
            if ($totalPresences > 0) {
                $percentage = round(($hadir / $totalPresences) * 100, 2);
            } else {
                $percentage = 0;
            }
            
            $attendanceStats[$matkul->id] = $percentage;
            $chartLabels[] = $matkul->kode;
            $chartData[] = $percentage;
        }

        return view('dosen.dashboard', compact('matkuls', 'totalMahasiswa', 'attendanceStats', 'chartLabels', 'chartData', 'mahasiswaCounts'));
    }
}

