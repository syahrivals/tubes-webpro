<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Matkul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $matkuls = auth()->user()->matkuls()->withCount('mahasiswas')->get();
        $totalMahasiswa = DB::table('enrollments')
            ->join('matkuls', 'enrollments.matkul_id', '=', 'matkuls.id')
            ->where('matkuls.dosen_id', auth()->id())
            ->distinct('enrollments.mahasiswa_id')
            ->count('enrollments.mahasiswa_id');

        $attendanceStats = [];
        $chartLabels = [];
        $chartData = [];
        
        foreach ($matkuls as $matkul) {
            $totalPresences = $matkul->presences()->count();
            $hadir = $matkul->presences()->where('status', 'hadir')->count();
            $percentage = $totalPresences > 0 ? round(($hadir / $totalPresences) * 100, 2) : 0;
            
            $attendanceStats[$matkul->id] = $percentage;
            $chartLabels[] = $matkul->kode;
            $chartData[] = $percentage;
        }

        return view('dosen.dashboard', compact('matkuls', 'totalMahasiswa', 'attendanceStats', 'chartLabels', 'chartData'));
    }
}

