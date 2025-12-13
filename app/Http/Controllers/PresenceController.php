<?php

namespace App\Http\Controllers;

use App\Models\Matkul;
use App\Models\Presence;
use App\Notifications\AttendanceMarked;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class PresenceController extends Controller
{
    public function index(Request $request)
    {
        $matkulId = $request->get('matkul_id');
        $tanggal = $request->get('tanggal', date('Y-m-d'));

        $user = auth()->user();
        $matkuls = Matkul::where('dosen_id', $user->id)->get();
        
        if (!$matkulId && count($matkuls) > 0) {
            $matkulId = $matkuls[0]->id;
        }

        $matkul = null;
        if ($matkulId) {
            $matkul = Matkul::find($matkulId);
        }
        
        $mahasiswas = collect();
        if ($matkul) {
            $mahasiswas = $matkul->mahasiswas;
        }
        
        $presences = [];
        if ($matkul) {
            $allPresences = Presence::where('matkul_id', $matkul->id)
                ->where('tanggal', $tanggal)
                ->get();
            
            foreach ($allPresences as $presence) {
                $presences[$presence->mahasiswa_id] = $presence;
            }
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

        $matkul = Matkul::find($request->matkul_id);
        if (!$matkul) {
            return redirect()->back()->with('error', 'Mata kuliah tidak ditemukan');
        }
        
        if ($matkul->dosen_id != auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke mata kuliah ini');
        }

        foreach ($request->presences as $presenceData) {
            $existingPresence = Presence::where('matkul_id', $request->matkul_id)
                ->where('mahasiswa_id', $presenceData['mahasiswa_id'])
                ->where('tanggal', $request->tanggal)
                ->first();
            
            $isNew = false;
            if ($existingPresence) {
                // if locked (e.g. validated by dosen), do not overwrite
                if ($existingPresence->locked) {
                    continue;
                }

                $oldStatus = $existingPresence->status;
                $existingPresence->status = $presenceData['status'];
                if (isset($presenceData['note'])) {
                    $existingPresence->note = $presenceData['note'];
                }
                $existingPresence->recorded_by = auth()->id();
                $existingPresence->save();
                $presence = $existingPresence;
            } else {
                $newPresence = new Presence();
                $newPresence->matkul_id = $request->matkul_id;
                $newPresence->mahasiswa_id = $presenceData['mahasiswa_id'];
                $newPresence->tanggal = $request->tanggal;
                $newPresence->status = $presenceData['status'];
                if (isset($presenceData['note'])) {
                    $newPresence->note = $presenceData['note'];
                }
                $newPresence->recorded_by = auth()->id();
                $newPresence->locked = false;
                $newPresence->save();
                $presence = $newPresence;
                $isNew = true;
            }

            // Send notification to student if status changed or new attendance
            if ($isNew || ($existingPresence && $oldStatus !== $presenceData['status'])) {
                $mahasiswa = $presence->mahasiswa;
                if ($mahasiswa && $mahasiswa->user) {
                    $mahasiswa->user->notify(new AttendanceMarked($presence, $matkul));
                }
            }
        }

        return redirect()->route('presences.index', [
            'matkul_id' => $request->matkul_id,
            'tanggal' => $request->tanggal,
        ])->with('success', 'Presensi berhasil disimpan');
    }
}

