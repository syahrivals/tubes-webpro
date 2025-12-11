<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Matkul;
use App\Models\Izin;

class IzinApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_dosen_approving_izin_marks_presence_as_izin_and_locks()
    {
        $dosen = User::factory()->create(['role' => 'dosen']);
        $matkul = Matkul::factory()->create(['dosen_id' => $dosen->id]);

    $mahasiswa = \App\Models\Mahasiswa::factory()->create();
    $mahasiswaUser = $mahasiswa->user;
    $mahasiswa->matkuls()->attach($matkul->id);

        $izin = Izin::create([
            'mahasiswa_id' => $mahasiswa->id,
            'matkul_id' => $matkul->id,
            'tanggal' => now()->toDateString(),
            'alasan' => 'Sakit',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($dosen)
            ->post(route('dosen.izin.approve', ['id' => $izin->id]));

        $response->assertRedirect();

        $this->assertDatabaseHas('presences', [
            'matkul_id' => $matkul->id,
            'mahasiswa_id' => $mahasiswa->id,
            // sqlite stores date as datetime
            'tanggal' => now()->toDateString() . ' 00:00:00',
            'status' => 'izin',
            'locked' => 1,
        ]);
    }
}
