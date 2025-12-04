<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Izin;
use App\Models\Matkul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IzinController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        $matkuls = $mahasiswa->matkuls;
        
        return view('mahasiswa.izin', compact('matkuls'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'matkul_id' => 'required|exists:matkuls,id',
            'tanggal' => 'required|date',
            'alasan' => 'nullable|string|max:500',
            'bukti_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;

        $matkul = Matkul::find($request->matkul_id);
        if (!$matkul || !$mahasiswa->matkuls->contains($matkul->id)) {
            return back()->withErrors(['matkul_id' => 'Mata kuliah tidak valid']);
        }

        $file = $request->file('bukti_file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('izins', $fileName, 'public');

        Izin::create([
            'mahasiswa_id' => $mahasiswa->id,
            'matkul_id' => $request->matkul_id,
            'tanggal' => $request->tanggal,
            'alasan' => $request->alasan,
            'bukti_file' => $filePath,
            'status' => 'pending',
        ]);

        return redirect()->route('mahasiswa.izin.create')->with('success', 'Izin berhasil diajukan');
    }
}
