<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dosen_can_access_dosen_dashboard(): void
    {
        $dosen = User::factory()->create(['role' => 'dosen']);

        $response = $this->actingAs($dosen)
            ->get(route('dosen.dashboard'));

        $response->assertStatus(200);
    }

    public function test_mahasiswa_cannot_access_dosen_dashboard(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);

        $response = $this->actingAs($mahasiswa)
            ->get(route('dosen.dashboard'));

        $response->assertStatus(403);
    }

    public function test_mahasiswa_can_access_mahasiswa_dashboard(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswa->mahasiswa()->create([
            'nim' => '1234567890',
            'jurusan' => 'Teknik Informatika',
            'angkatan' => 2023,
        ]);

        $response = $this->actingAs($mahasiswa)
            ->get(route('mahasiswa.dashboard'));

        $response->assertStatus(200);
    }
}

