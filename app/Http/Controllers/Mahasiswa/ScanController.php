<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\QrSession;
use App\Models\Presence;
use App\Models\Matkul;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function index()
    {
        return view('mahasiswa.scan');
    }

    public function store(Request $request)
    {

        $request->validate([
            'token' => 'required|string',
        ]);


        $token = $request->token;

        // Validasi QR session benar-benar dari aplikasi
        $session = QrSession::where('token', $token)
            ->where('expires_at', '>=', now())
            ->first();

        if (!$session) {
            \Log::warning('Scan QR gagal: token tidak valid atau kadaluarsa', ['token' => $token, 'user_id' => auth()->id()]);
            return back()->with('error', 'QR tidak valid atau sudah kadaluarsa');
        }

        $mahasiswa = auth()->user()->mahasiswa;

        // check enrollment
        $matkul = Matkul::find($session->matkul_id);
        if (!$matkul || !$mahasiswa->matkuls->contains($matkul->id)) {
            \Log::warning('Scan QR gagal: mahasiswa tidak terdaftar di matkul', ['matkul_id' => $session->matkul_id, 'user_id' => auth()->id()]);
            return back()->with('error', 'Anda tidak terdaftar di mata kuliah ini');
        }

        $tanggal = now()->toDateString();

        $presence = Presence::firstOrNew([
            'matkul_id' => $matkul->id,
            'mahasiswa_id' => $mahasiswa->id,
            'tanggal' => $tanggal,
        ]);

        // if locked, cannot mark
        if ($presence->exists && $presence->locked) {
            \Log::info('Scan QR gagal: presensi sudah dikunci', ['presence_id' => $presence->id, 'user_id' => auth()->id()]);
            return back()->with('error', 'Presensi hari ini sudah dikunci dan tidak dapat diubah');
        }

        $presence->status = 'hadir';
        $presence->recorded_by = auth()->id();
        $presence->locked = false; // scanning does not lock; only dosen approval/reject locks
        $presence->save();

        \Log::info('Presensi berhasil diubah menjadi hadir via QR', [
            'presence_id' => $presence->id,
            'matkul_id' => $matkul->id,
            'mahasiswa_id' => $mahasiswa->id,
            'user_id' => auth()->id(),
            'tanggal' => $tanggal,
        ]);

        return redirect()->route('mahasiswa.dashboard')->with('success', 'Absensi berhasil (QR)');
    }
}
