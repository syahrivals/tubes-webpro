<?php

namespace App\Http\Controllers;

use App\Models\Matkul;
use App\Models\Presence;
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    public function index(Request $request)
    {
        // Ambil matkul_id dan tanggal dari request
        $matkulId = $request->get('matkul_id');
        $tanggal = $request->get('tanggal', date('Y-m-d'));

        // Ambil semua mata kuliah yang diajar dosen ini
        $user = auth()->user();
        $matkuls = Matkul::where('dosen_id', $user->id)->get();
        
        // Jika belum pilih mata kuliah, ambil yang pertama
        if (!$matkulId && count($matkuls) > 0) {
            $matkulId = $matkuls[0]->id;
        }

        // Ambil data mata kuliah yang dipilih
        $matkul = null;
        if ($matkulId) {
            $matkul = Matkul::find($matkulId);
        }
        
        // Ambil semua mahasiswa yang terdaftar di mata kuliah ini
        $mahasiswas = collect();
        if ($matkul) {
            $mahasiswas = $matkul->mahasiswas;
        }
        
        // Ambil semua presensi yang sudah ada untuk tanggal ini
        $presences = [];
        if ($matkul) {
            $allPresences = Presence::where('matkul_id', $matkul->id)
                ->where('tanggal', $tanggal)
                ->get();
            
            // Buat array dengan key mahasiswa_id
            foreach ($allPresences as $presence) {
                $presences[$presence->mahasiswa_id] = $presence;
            }
        }

        return view('dosen.presences', compact('matkuls', 'matkul', 'mahasiswas', 'presences', 'tanggal'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'matkul_id' => 'required|exists:matkuls,id',
            'tanggal' => 'required|date',
            'presences' => 'required|array',
            'presences.*.mahasiswa_id' => 'required|exists:mahasiswas,id',
            'presences.*.status' => 'required|in:alpha,izin,sakit,hadir',
        ]);

        // Cek apakah mata kuliah ini milik dosen yang login
        $matkul = Matkul::find($request->matkul_id);
        if (!$matkul) {
            return redirect()->back()->with('error', 'Mata kuliah tidak ditemukan');
        }
        
        if ($matkul->dosen_id != auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke mata kuliah ini');
        }

        // Simpan presensi untuk setiap mahasiswa
        foreach ($request->presences as $presenceData) {
            // Cek apakah sudah ada presensi untuk mahasiswa ini di tanggal ini
            $existingPresence = Presence::where('matkul_id', $request->matkul_id)
                ->where('mahasiswa_id', $presenceData['mahasiswa_id'])
                ->where('tanggal', $request->tanggal)
                ->first();
            
            if ($existingPresence) {
                // Update jika sudah ada
                $existingPresence->status = $presenceData['status'];
                if (isset($presenceData['note'])) {
                    $existingPresence->note = $presenceData['note'];
                }
                $existingPresence->recorded_by = auth()->id();
                $existingPresence->save();
            } else {
                // Buat baru jika belum ada
                $newPresence = new Presence();
                $newPresence->matkul_id = $request->matkul_id;
                $newPresence->mahasiswa_id = $presenceData['mahasiswa_id'];
                $newPresence->tanggal = $request->tanggal;
                $newPresence->status = $presenceData['status'];
                if (isset($presenceData['note'])) {
                    $newPresence->note = $presenceData['note'];
                }
                $newPresence->recorded_by = auth()->id();
                $newPresence->save();
            }
        }

        return redirect()->route('presences.index', [
            'matkul_id' => $request->matkul_id,
            'tanggal' => $request->tanggal,
        ])->with('success', 'Presensi berhasil disimpan');
    }
}

