<?php

namespace App\Http\Controllers;

use App\Models\Matkul;
use App\Models\Presence;
use App\Models\Mahasiswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isDosen()) {
            $matkuls = Matkul::where('dosen_id', $user->id)->get();
            return view('reports.index', compact('matkuls'));
        } else {
            // For mahasiswa, show their own reports
            $mahasiswa = $user->mahasiswa;
            $matkuls = $mahasiswa->matkuls;
            return view('reports.student-reports', compact('matkuls'));
        }
    }

    public function generate(Request $request)
    {
        $request->validate([
            'matkul_id' => 'required|exists:matkuls,id',
            'month' => 'nullable|date_format:Y-m',
            'type' => 'required|in:pdf,excel'
        ]);

        $matkul = Matkul::with(['mahasiswas.user', 'presences', 'dosen'])->findOrFail($request->matkul_id);

        // Check if user has access to this matkul
        $user = auth()->user();
        if ($user->isDosen() && $matkul->dosen_id !== $user->id) {
            abort(403);
        }
        if ($user->isMahasiswa() && !$user->mahasiswa->matkuls->contains($matkul)) {
            abort(403);
        }

        $month = $request->month ?: now()->format('Y-m');
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        // Get attendance data
        $attendanceData = [];
        foreach ($matkul->mahasiswas as $mahasiswa) {
            $presences = Presence::where('matkul_id', $matkul->id)
                ->where('mahasiswa_id', $mahasiswa->id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->get()
                ->keyBy('tanggal');

            $stats = [
                'hadir' => 0,
                'izin' => 0,
                'sakit' => 0,
                'alpha' => 0,
                'total' => 0
            ];

            foreach ($presences as $presence) {
                $stats[$presence->status]++;
                $stats['total']++;
            }

            $attendanceData[] = [
                'mahasiswa' => $mahasiswa,
                'presences' => $presences,
                'stats' => $stats,
                'percentage' => $stats['total'] > 0 ? round(($stats['hadir'] / $stats['total']) * 100, 2) : 0
            ];
        }

        $data = [
            'matkul' => $matkul,
            'month' => $month,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'attendanceData' => $attendanceData,
            'generated_at' => now()
        ];

        if ($request->type === 'pdf') {
            $pdf = Pdf::loadView('reports.attendance-pdf', $data);
            return $pdf->download("laporan-presensi-{$matkul->nama}-{$month}.pdf");
        }

        // For Excel, we'll implement later
        return back()->with('error', 'Export Excel belum diimplementasikan');
    }

    public function studentReport(Request $request)
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        $matkuls = $mahasiswa->matkuls()->with('dosen')->get();

        $matkulId = $request->get('matkul_id');
        if (!$matkulId && $matkuls->count() > 0) {
            $matkulId = $matkuls->first()->id;
        }

        $matkul = null;
        $presences = collect();
        $stats = ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0, 'total' => 0];

        if ($matkulId) {
            $matkul = $matkuls->find($matkulId);
            if ($matkul) {
                $presences = Presence::where('matkul_id', $matkul->id)
                    ->where('mahasiswa_id', $mahasiswa->id)
                    ->orderBy('tanggal', 'desc')
                    ->get();

                foreach ($presences as $presence) {
                    $stats[$presence->status]++;
                    $stats['total']++;
                }
            }
        }

        return view('reports.student-reports', compact('mahasiswa', 'matkuls', 'matkul', 'presences', 'stats'));
    }
}