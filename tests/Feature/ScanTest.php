<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Matkul;
use App\Models\QrSession;

class ScanTest extends TestCase
{
    use RefreshDatabase;

    public function test_mahasiswa_can_scan_valid_qr_and_create_presence()
    {
        // create dosen
        $dosen = User::factory()->create(['role' => 'dosen']);

        // create matkul
        $matkul = Matkul::factory()->create(['dosen_id' => $dosen->id]);

    // create mahasiswa (factory will create associated user)
    $mahasiswa = \App\Models\Mahasiswa::factory()->create();
    $mahasiswaUser = $mahasiswa->user;
    $mahasiswa->matkuls()->attach($matkul->id);

        // create qr session
        $qr = QrSession::create([
            'matkul_id' => $matkul->id,
            'token' => 'testtoken123',
            'expires_at' => now()->addMinutes(15),
            'created_by' => $dosen->id,
        ]);

        // act as mahasiswa and post scan
        $response = $this->actingAs($mahasiswaUser)
            ->post(route('mahasiswa.scan.store'), ['token' => $qr->token]);

        $response->assertRedirect(route('mahasiswa.dashboard'));
        $this->assertDatabaseHas('presences', [
            'matkul_id' => $matkul->id,
            'mahasiswa_id' => $mahasiswa->id,
            'status' => 'hadir',
            // sqlite stores date as datetime, include time portion
            'tanggal' => now()->toDateString() . ' 00:00:00',
        ]);
    }
}
