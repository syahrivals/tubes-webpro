<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Izin;
use App\Models\Matkul;
use Illuminate\Http\Request;

class IzinController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $matkuls = Matkul::where('dosen_id', $user->id)->get();
        
        $selectedMatkulId = $request->get('matkul_id');
        
        $query = Izin::whereIn('matkul_id', $matkuls->pluck('id'))
            ->with(['mahasiswa.user', 'matkul'])
            ->orderBy('created_at', 'desc');
        
        if ($selectedMatkulId) {
            $query->where('matkul_id', $selectedMatkulId);
        }
        
        $izins = $query->get();
        
        return view('dosen.izin', compact('matkuls', 'izins', 'selectedMatkulId'));
    }

    public function approve($id)
    {
        $user = auth()->user();
        $izin = Izin::with('matkul')->findOrFail($id);
        
        if ($izin->matkul->dosen_id != $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk validasi izin ini');
        }
        
        $izin->status = 'approved';
        $izin->validated_by = $user->id;
        $izin->validated_at = now();
        $izin->save();
        
        return redirect()->back()->with('success', 'Izin berhasil disetujui');
    }

    public function reject($id)
    {
        $user = auth()->user();
        $izin = Izin::with('matkul')->findOrFail($id);
        
        if ($izin->matkul->dosen_id != $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk validasi izin ini');
        }
        
        $izin->status = 'rejected';
        $izin->validated_by = $user->id;
        $izin->validated_at = now();
        $izin->save();
        
        return redirect()->back()->with('success', 'Izin berhasil ditolak');
    }
}
