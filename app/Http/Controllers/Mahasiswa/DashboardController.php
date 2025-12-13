<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Presence;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        
        $matkuls = $mahasiswa->matkuls;

        // Mata kuliah hari ini
        $todayName = strtolower(now()->locale('id')->isoFormat('dddd'));
        $todaysMatkuls = $matkuls->filter(function ($m) use ($todayName) {
            return $m->hari && strtolower($m->hari) === $todayName;
        });

        $selectedMatkulId = $request->get('matkul_id');
        if (!$selectedMatkulId && $matkuls->count() > 0) {
            $selectedMatkulId = $matkuls->first()->id;
        }

        $selectedData = null;
        if ($selectedMatkulId) {
            $selectedMatkul = $matkuls->firstWhere('id', $selectedMatkulId);
            if ($selectedMatkul) {
                $allPresences = Presence::where('matkul_id', $selectedMatkul->id)
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
                $selectedData = [
                    'matkul' => $selectedMatkul,
                    'hadir' => $hadir,
                    'izin' => $izin,
                    'sakit' => $sakit,
                    'alpha' => $alpha,
                    'total' => $total,
                    'percentage' => $percentage,
                    'presences' => $allPresences,
                ];
            }
        }

        return view('mahasiswa.dashboard', compact('mahasiswa', 'matkuls', 'selectedData', 'selectedMatkulId', 'todaysMatkuls'));
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

    public function enrollments()
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        
        // Get all available matkul
        $availableMatkuls = \App\Models\Matkul::all();
        
        // Get current enrollments (pending, approved, rejected)
        $enrollments = \App\Models\Enrollment::where('mahasiswa_id', $mahasiswa->id)
            ->with('matkul')
            ->get();

        return view('mahasiswa.enrollments', compact('availableMatkuls', 'enrollments'));
    }

    public function requestEnrollment(\App\Models\Matkul $matkul)
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        
        // Check if already enrolled or requested
        $existing = \App\Models\Enrollment::where('mahasiswa_id', $mahasiswa->id)
            ->where('matkul_id', $matkul->id)
            ->first();
            
        if ($existing) {
            return back()->with('error', 'Anda sudah mengajukan permintaan untuk mata kuliah ini');
        }
        
        // Create enrollment request
        \App\Models\Enrollment::create([
            'mahasiswa_id' => $mahasiswa->id,
            'matkul_id' => $matkul->id,
            'status' => 'pending'
        ]);
        
        return back()->with('success', 'Permintaan enrollment berhasil diajukan');
    }
}

