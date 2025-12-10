<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Matkul;

class MatkulController extends Controller
{
    public function destroy($id)
    {
        $matkul = Matkul::findOrFail($id);

        // Hapus presensi terkait
        if ($matkul->presences()->exists()) {
            $matkul->presences()->delete();
        }

        // Hapus relasi mahasiswa di tabel enrollments (pivot)
        if ($matkul->mahasiswas()->exists()) {
            $matkul->mahasiswas()->detach();
        }

        // Hapus mata kuliah
        $matkul->delete();

        return redirect()->back()->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
