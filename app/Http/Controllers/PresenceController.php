<?php

namespace App\Http\Controllers;

use App\Models\Matkul;
use App\Models\Presence;
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    public function index(Request $request)
    {
        $matkulId = $request->get('matkul_id');
        $tanggal = $request->get('tanggal', date('Y-m-d'));

        $matkuls = auth()->user()->matkuls;
        
        if (!$matkulId && $matkuls->count() > 0) {
            $matkulId = $matkuls->first()->id;
        }

        $matkul = $matkuls->find($matkulId);
        $mahasiswas = $matkul ? $matkul->mahasiswas : collect();
        
        $presences = [];
        if ($matkul) {
            $presences = Presence::where('matkul_id', $matkul->id)
                ->where('tanggal', $tanggal)
                ->get()
                ->keyBy('mahasiswa_id');
        }

        return view('dosen.presences', compact('matkuls', 'matkul', 'mahasiswas', 'presences', 'tanggal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'matkul_id' => 'required|exists:matkuls,id',
            'tanggal' => 'required|date',
            'presences' => 'required|array',
            'presences.*.mahasiswa_id' => 'required|exists:mahasiswas,id',
            'presences.*.status' => 'required|in:alpha,izin,sakit,hadir',
        ]);

        $matkul = Matkul::findOrFail($request->matkul_id);
        
        if ($matkul->dosen_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke mata kuliah ini');
        }

        foreach ($request->presences as $presenceData) {
            Presence::updateOrCreate(
                [
                    'matkul_id' => $request->matkul_id,
                    'mahasiswa_id' => $presenceData['mahasiswa_id'],
                    'tanggal' => $request->tanggal,
                ],
                [
                    'status' => $presenceData['status'],
                    'note' => $presenceData['note'] ?? null,
                    'recorded_by' => auth()->id(),
                ]
            );
        }

        return redirect()->route('presences.index', [
            'matkul_id' => $request->matkul_id,
            'tanggal' => $request->tanggal,
        ])->with('success', 'Presensi berhasil disimpan')->withInput();
    }
}

