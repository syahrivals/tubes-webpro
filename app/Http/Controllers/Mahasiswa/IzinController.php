<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Izin;
use App\Models\Matkul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Notifications\IzinSubmitted;

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

        // Validasi hari izin harus sesuai hari matkul dan tidak boleh hari yang sudah lewat
        $matkul = Matkul::find($request->matkul_id);
        if (!$matkul) {
            return back()->withErrors(['matkul_id' => 'Mata kuliah tidak valid']);
        }
        $izinDate = date('Y-m-d', strtotime($request->tanggal));
        $izinDay = strtolower(date('l', strtotime($izinDate))); // e.g. 'wednesday'
        $matkulDay = strtolower($matkul->hari); // e.g. 'rabu' or 'wednesday'

        // Mapping hari Indonesia ke English
        $hariMap = [
            'senin' => 'monday',
            'selasa' => 'tuesday',
            'rabu' => 'wednesday',
            'kamis' => 'thursday',
            'jumat' => 'friday',
            'sabtu' => 'saturday',
            'minggu' => 'sunday',
        ];
        if (isset($hariMap[$matkulDay])) {
            $matkulDay = $hariMap[$matkulDay];
        }
        if ($izinDay !== $matkulDay) {
            return back()->withErrors(['tanggal' => 'Izin hanya bisa diajukan di hari ' . ucfirst($matkul->hari) . ' sesuai jadwal matkul.']);
        }
        if ($izinDate < date('Y-m-d')) {
            return back()->withErrors(['tanggal' => 'Tidak bisa mengajukan izin untuk hari yang sudah lewat.']);
        }

        $user = auth()->user();
        $mahasiswa = $user->mahasiswa;
        if (!$mahasiswa->matkuls->contains($matkul->id)) {
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

        // notify dosen
        $izin = \App\Models\Izin::where('mahasiswa_id', $mahasiswa->id)
            ->where('matkul_id', $request->matkul_id)
            ->where('tanggal', $request->tanggal)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($izin && $izin->matkul && $izin->matkul->dosen) {
            $dosenUser = $izin->matkul->dosen; // Matkul->dosen returns User
            if ($dosenUser) {
                $dosenUser->notify(new IzinSubmitted($izin));
            }
        }

        return redirect()->route('mahasiswa.izin.create')->with('success', 'Izin berhasil diajukan');
    }
}
