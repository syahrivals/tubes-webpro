<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Matkul;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $matkuls = Matkul::where('dosen_id', $user->id)->get();

        // Group matkuls by day for schedule view
        $schedule = [
            'senin' => [],
            'selasa' => [],
            'rabu' => [],
            'kamis' => [],
            'jumat' => [],
            'sabtu' => [],
            'minggu' => []
        ];

        foreach ($matkuls as $matkul) {
            $hari = strtolower($matkul->hari);
            if (isset($schedule[$hari])) {
                $schedule[$hari][] = $matkul;
            }
        }

        // Sort by time within each day
        foreach ($schedule as $day => $dayMatkuls) {
            usort($dayMatkuls, function($a, $b) {
                return strcmp($a->jam, $b->jam);
            });
            $schedule[$day] = $dayMatkuls;
        }

        return view('dosen.schedule.index', compact('schedule', 'matkuls'));
    }

    public function create()
    {
        $user = auth()->user();
        return view('dosen.schedule.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'jam' => 'required|date_format:H:i',
            'ruangan' => 'nullable|string|max:100',
        ]);

        $user = auth()->user();

        Matkul::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'hari' => $request->hari,
            'jam' => $request->jam,
            'ruangan' => $request->ruangan,
            'dosen_id' => $user->id,
        ]);

        return redirect()->route('dosen.schedule.index')->with('success', 'Jadwal mata kuliah berhasil ditambahkan');
    }

    public function edit(Matkul $matkul)
    {
        $user = auth()->user();
        if ($matkul->dosen_id !== $user->id) {
            abort(403);
        }

        return view('dosen.schedule.edit', compact('matkul'));
    }

    public function update(Request $request, Matkul $matkul)
    {
        $user = auth()->user();
        if ($matkul->dosen_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'hari' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'jam' => 'required|date_format:H:i',
            'ruangan' => 'nullable|string|max:100',
        ]);

        $matkul->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'hari' => $request->hari,
            'jam' => $request->jam,
            'ruangan' => $request->ruangan,
        ]);

        return redirect()->route('dosen.schedule.index')->with('success', 'Jadwal mata kuliah berhasil diperbarui');
    }

    public function destroy(Matkul $matkul)
    {
        $user = auth()->user();
        if ($matkul->dosen_id !== $user->id) {
            abort(403);
        }

        // Check if matkul has enrollments
        if ($matkul->enrollments()->where('status', 'approved')->exists()) {
            return back()->with('error', 'Tidak dapat menghapus mata kuliah yang masih memiliki mahasiswa terdaftar');
        }

        $matkul->delete();

        return redirect()->route('dosen.schedule.index')->with('success', 'Jadwal mata kuliah berhasil dihapus');
    }

    public function calendar()
    {
        $user = auth()->user();
        $matkuls = Matkul::where('dosen_id', $user->id)->get();

        return view('dosen.schedule.calendar', compact('matkuls'));
    }

    public function getEvents()
    {
        $user = auth()->user();
        $matkuls = Matkul::where('dosen_id', $user->id)->get();

        $events = [];
        $daysMap = [
            'minggu' => 0,
            'senin' => 1,
            'selasa' => 2,
            'rabu' => 3,
            'kamis' => 4,
            'jumat' => 5,
            'sabtu' => 6,
        ];

        foreach ($matkuls as $matkul) {
            $dayOfWeek = $daysMap[strtolower($matkul->hari)] ?? 1;

            // Create recurring events for the next 12 weeks
            for ($week = 0; $week < 12; $week++) {
                $startDate = now()->startOfWeek()->addDays($dayOfWeek)->addWeeks($week);
                $endDate = $startDate->copy()->addMinutes(90); // Assume 90 minutes duration

                // Parse time from jam field
                $timeParts = explode(':', $matkul->jam);
                $startDate->setTime($timeParts[0], $timeParts[1]);
                $endDate->setTime($timeParts[0], $timeParts[1])->addMinutes(90);

                $events[] = [
                    'id' => $matkul->id . '_' . $week,
                    'title' => $matkul->nama,
                    'start' => $startDate->toISOString(),
                    'end' => $endDate->toISOString(),
                    'description' => $matkul->deskripsi,
                    'location' => $matkul->ruangan,
                    'backgroundColor' => '#007bff',
                    'borderColor' => '#0056b3',
                    'textColor' => '#ffffff',
                ];
            }
        }

        return response()->json($events);
    }
}
