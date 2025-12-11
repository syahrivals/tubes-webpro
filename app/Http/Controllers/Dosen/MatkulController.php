<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Matkul;

class MatkulController extends Controller
{
    public function create()
    {
        $allMahasiswas = \App\Models\Mahasiswa::with('user')->get();
        return view('dosen.matkul-form', compact('allMahasiswas'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'kode' => 'required',
            'nama' => 'required',
            'hari' => 'required',
            'semester' => 'required|integer',
            'credits' => 'required|integer',
            'mahasiswas' => 'required|array',
        ]);
        $matkul = \App\Models\Matkul::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'hari' => $request->hari,
            'jam' => $request->jam,
            'semester' => $request->semester,
            'credits' => $request->credits,
            'dosen_id' => auth()->id(),
        ]);
        $matkul->mahasiswas()->sync($request->mahasiswas);
        return redirect()->route('dosen.dashboard')->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $matkul = \App\Models\Matkul::with('mahasiswas')->findOrFail($id);
        $allMahasiswas = \App\Models\Mahasiswa::with('user')->get();
        return view('dosen.matkul-form', compact('matkul', 'allMahasiswas'));
    }

    public function update(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'kode' => 'required',
            'nama' => 'required',
            'hari' => 'required',
            'semester' => 'required|integer',
            'credits' => 'required|integer',
            'mahasiswas' => 'required|array',
        ]);
        $matkul = \App\Models\Matkul::findOrFail($id);
        $matkul->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'hari' => $request->hari,
            'jam' => $request->jam,
            'semester' => $request->semester,
            'credits' => $request->credits,
        ]);
        $matkul->mahasiswas()->sync($request->mahasiswas);
        return redirect()->route('dosen.dashboard')->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $matkul = \App\Models\Matkul::findOrFail($id);
        if ($matkul->presences()->exists()) {
            $matkul->presences()->delete();
        }
        if ($matkul->mahasiswas()->exists()) {
            $matkul->mahasiswas()->detach();
        }
        $matkul->delete();
        return redirect()->back()->with('success', 'Mata kuliah berhasil dihapus.');
    }
}
