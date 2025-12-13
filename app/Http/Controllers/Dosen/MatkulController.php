<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Matkul;

class MatkulController extends Controller
{
    public function create()
    {
        return view('dosen.matkul-form');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'kode' => 'required',
            'nama' => 'required',
            'hari' => 'required',
            'jam' => 'required',
            'semester' => 'required|integer',
            'credits' => 'required|integer',
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
        return redirect()->route('dosen.dashboard')->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $matkul = \App\Models\Matkul::findOrFail($id);
        return view('dosen.matkul-form', compact('matkul'));
    }

    public function update(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'kode' => 'required',
            'nama' => 'required',
            'hari' => 'required',
            'jam' => 'required',
            'semester' => 'required|integer',
            'credits' => 'required|integer',
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
