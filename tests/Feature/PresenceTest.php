<?php

namespace Tests\Feature;

use App\Models\Mahasiswa;
use App\Models\Matkul;
use App\Models\Presence;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PresenceTest extends TestCase
{
    use RefreshDatabase;

    public function test_dosen_can_access_presence_form(): void
    {
        $dosen = User::factory()->create(['role' => 'dosen']);
        $matkul = Matkul::factory()->create(['dosen_id' => $dosen->id]);

        $response = $this->actingAs($dosen)
            ->get(route('presences.index', ['matkul_id' => $matkul->id]));

        $response->assertStatus(200);
    }

    public function test_mahasiswa_cannot_access_presence_form(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);

        $response = $this->actingAs($mahasiswa)
            ->get(route('presences.index'));

        $response->assertStatus(403);
    }

    public function test_presence_can_be_saved(): void
    {
        $dosen = User::factory()->create(['role' => 'dosen']);
        $matkul = Matkul::factory()->create(['dosen_id' => $dosen->id]);
        $mahasiswa = Mahasiswa::factory()->create();
        
        $matkul->mahasiswas()->attach($mahasiswa->id);

        $response = $this->actingAs($dosen)
            ->post(route('presences.store'), [
                'matkul_id' => $matkul->id,
                'tanggal' => now()->format('Y-m-d'),
                'presences' => [
                    [
                        'mahasiswa_id' => $mahasiswa->id,
                        'status' => 'hadir',
                    ],
                ],
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('presences', [
            'matkul_id' => $matkul->id,
            'mahasiswa_id' => $mahasiswa->id,
            'status' => 'hadir',
        ]);
    }
}

