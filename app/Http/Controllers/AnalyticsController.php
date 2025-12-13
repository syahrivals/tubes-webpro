<?php

namespace App\Http\Controllers;

use App\Models\Matkul;
use App\Models\Presence;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isDosen()) {
            return $this->dosenAnalytics();
        } else {
            return $this->mahasiswaAnalytics();
        }
    }

    private function dosenAnalytics()
    {
        $user = auth()->user();
        $matkuls = Matkul::where('dosen_id', $user->id)->with(['presences', 'enrollments'])->get();

        // Overall Statistics
        $totalMatkuls = $matkuls->count();
        $totalEnrollments = Enrollment::whereIn('matkul_id', $matkuls->pluck('id'))->where('status', 'approved')->count();
        $totalPresences = Presence::whereIn('matkul_id', $matkuls->pluck('id'))->count();

        // Attendance Rate
        $attendanceStats = $this->calculateAttendanceStats($matkuls);

        // Monthly Trends (last 6 months)
        $monthlyTrends = $this->getMonthlyTrends($matkuls);

        // Top Performing Courses
        $topCourses = $this->getTopPerformingCourses($matkuls);

        // Student Performance Distribution
        $performanceDistribution = $this->getPerformanceDistribution($matkuls);

        // Recent Activity
        $recentActivity = $this->getRecentActivity($matkuls);

        return view('analytics.dosen-dashboard', compact(
            'totalMatkuls',
            'totalEnrollments',
            'totalPresences',
            'attendanceStats',
            'monthlyTrends',
            'topCourses',
            'performanceDistribution',
            'recentActivity'
        ));
    }

    private function mahasiswaAnalytics()
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;

        $enrollments = $mahasiswa->enrollments()->where('status', 'approved')->with('matkul')->get();
        $matkuls = $enrollments->pluck('matkul');

        // Personal Attendance Statistics
        $personalStats = $this->calculatePersonalStats($mahasiswa, $matkuls);

        // Course-wise Performance
        $coursePerformance = $this->getCoursePerformance($mahasiswa, $matkuls);

        // Attendance Trends
        $attendanceTrends = $this->getAttendanceTrends($mahasiswa, $matkuls);

        // Comparison with Class Average
        $classComparison = $this->getClassComparison($mahasiswa, $matkuls);

        return view('analytics.mahasiswa-dashboard', compact(
            'personalStats',
            'coursePerformance',
            'attendanceTrends',
            'classComparison'
        ));
    }

    private function calculateAttendanceStats($matkuls)
    {
        $totalPresences = 0;
        $totalHadir = 0;

        foreach ($matkuls as $matkul) {
            $presences = $matkul->presences;
            $totalPresences += $presences->count();
            $totalHadir += $presences->where('status', 'hadir')->count();
        }

        $attendanceRate = $totalPresences > 0 ? round(($totalHadir / $totalPresences) * 100, 2) : 0;

        return [
            'total_presences' => $totalPresences,
            'total_hadir' => $totalHadir,
            'attendance_rate' => $attendanceRate,
            'absent_rate' => 100 - $attendanceRate
        ];
    }

    private function getMonthlyTrends($matkuls)
    {
        $trends = [];
        $matkulIds = $matkuls->pluck('id');

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');

            $presences = Presence::whereIn('matkul_id', $matkulIds)
                ->whereYear('tanggal', $date->year)
                ->whereMonth('tanggal', $date->month)
                ->get();

            $hadir = $presences->where('status', 'hadir')->count();
            $total = $presences->count();
            $rate = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

            $trends[] = [
                'month' => $monthName,
                'hadir' => $hadir,
                'total' => $total,
                'rate' => $rate
            ];
        }

        return $trends;
    }

    private function getTopPerformingCourses($matkuls)
    {
        $courses = [];

        foreach ($matkuls as $matkul) {
            $presences = $matkul->presences;
            $hadir = $presences->where('status', 'hadir')->count();
            $total = $presences->count();
            $rate = $total > 0 ? round(($hadir / $total) * 100, 2) : 0;

            $courses[] = [
                'name' => $matkul->nama,
                'attendance_rate' => $rate,
                'total_students' => $matkul->enrollments->where('status', 'approved')->count(),
                'total_presences' => $total
            ];
        }

        // Sort by attendance rate descending
        usort($courses, function($a, $b) {
            return $b['attendance_rate'] <=> $a['attendance_rate'];
        });

        return array_slice($courses, 0, 5);
    }

    private function getPerformanceDistribution($matkuls)
    {
        $distribution = [
            'excellent' => 0, // >= 90%
            'good' => 0,      // 75-89%
            'average' => 0,   // 60-74%
            'poor' => 0       // < 60%
        ];

        $matkulIds = $matkuls->pluck('id');
        $mahasiswaIds = Enrollment::whereIn('matkul_id', $matkulIds)
            ->where('status', 'approved')
            ->pluck('mahasiswa_id')
            ->unique();

        foreach ($mahasiswaIds as $mahasiswaId) {
            $presences = Presence::whereIn('matkul_id', $matkulIds)
                ->where('mahasiswa_id', $mahasiswaId)
                ->get();

            $hadir = $presences->where('status', 'hadir')->count();
            $total = $presences->count();

            if ($total > 0) {
                $rate = ($hadir / $total) * 100;

                if ($rate >= 90) {
                    $distribution['excellent']++;
                } elseif ($rate >= 75) {
                    $distribution['good']++;
                } elseif ($rate >= 60) {
                    $distribution['average']++;
                } else {
                    $distribution['poor']++;
                }
            }
        }

        return $distribution;
    }

    private function getRecentActivity($matkuls)
    {
        $matkulIds = $matkuls->pluck('id');

        $recentPresences = Presence::whereIn('matkul_id', $matkulIds)
            ->with(['mahasiswa.user', 'matkul'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return $recentPresences->map(function($presence) {
            return [
                'type' => 'attendance',
                'description' => "Presensi {$presence->mahasiswa->user->name} untuk {$presence->matkul->nama}",
                'status' => $presence->status,
                'date' => $presence->created_at
            ];
        });
    }

    private function calculatePersonalStats($mahasiswa, $matkuls)
    {
        $totalPresences = 0;
        $totalHadir = 0;
        $totalIzin = 0;
        $totalSakit = 0;
        $totalAlpha = 0;

        foreach ($matkuls as $matkul) {
            $presences = Presence::where('matkul_id', $matkul->id)
                ->where('mahasiswa_id', $mahasiswa->id)
                ->get();

            $totalPresences += $presences->count();
            $totalHadir += $presences->where('status', 'hadir')->count();
            $totalIzin += $presences->where('status', 'izin')->count();
            $totalSakit += $presences->where('status', 'sakit')->count();
            $totalAlpha += $presences->where('status', 'alpha')->count();
        }

        $attendanceRate = $totalPresences > 0 ? round(($totalHadir / $totalPresences) * 100, 2) : 0;

        return [
            'total_presences' => $totalPresences,
            'hadir' => $totalHadir,
            'izin' => $totalIzin,
            'sakit' => $totalSakit,
            'alpha' => $totalAlpha,
            'attendance_rate' => $attendanceRate
        ];
    }

    private function getCoursePerformance($mahasiswa, $matkuls)
    {
        $performance = [];

        foreach ($matkuls as $matkul) {
            $presences = Presence::where('matkul_id', $matkul->id)
                ->where('mahasiswa_id', $mahasiswa->id)
                ->get();

            $hadir = $presences->where('status', 'hadir')->count();
            $total = $presences->count();
            $rate = $total > 0 ? round(($hadir / $total) * 100, 2) : 0;

            $performance[] = [
                'course_name' => $matkul->nama,
                'attendance_rate' => $rate,
                'hadir' => $hadir,
                'total' => $total,
                'grade' => $this->getGradeFromRate($rate)
            ];
        }

        return $performance;
    }

    private function getAttendanceTrends($mahasiswa, $matkuls)
    {
        $trends = [];
        $matkulIds = $matkuls->pluck('id');

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M Y');

            $presences = Presence::whereIn('matkul_id', $matkulIds)
                ->where('mahasiswa_id', $mahasiswa->id)
                ->whereYear('tanggal', $date->year)
                ->whereMonth('tanggal', $date->month)
                ->get();

            $hadir = $presences->where('status', 'hadir')->count();
            $total = $presences->count();

            $trends[] = [
                'month' => $monthName,
                'hadir' => $hadir,
                'total' => $total
            ];
        }

        return $trends;
    }

    private function getClassComparison($mahasiswa, $matkuls)
    {
        $comparison = [];

        foreach ($matkuls as $matkul) {
            // Student performance
            $studentPresences = Presence::where('matkul_id', $matkul->id)
                ->where('mahasiswa_id', $mahasiswa->id)
                ->get();

            $studentHadir = $studentPresences->where('status', 'hadir')->count();
            $studentTotal = $studentPresences->count();
            $studentRate = $studentTotal > 0 ? ($studentHadir / $studentTotal) * 100 : 0;

            // Class average
            $allPresences = Presence::where('matkul_id', $matkul->id)->get();
            $classHadir = $allPresences->where('status', 'hadir')->count();
            $classTotal = $allPresences->count();
            $classRate = $classTotal > 0 ? ($classHadir / $classTotal) * 100 : 0;

            $comparison[] = [
                'course_name' => $matkul->nama,
                'student_rate' => round($studentRate, 1),
                'class_rate' => round($classRate, 1),
                'difference' => round($studentRate - $classRate, 1)
            ];
        }

        return $comparison;
    }

    private function getGradeFromRate($rate)
    {
        if ($rate >= 90) return 'A';
        if ($rate >= 80) return 'B';
        if ($rate >= 70) return 'C';
        if ($rate >= 60) return 'D';
        return 'E';
    }
}
