<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\Mahasiswa;
use App\Models\Matkul;
use App\Models\Presence;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $dosen = User::create([
            'name' => 'Dr. Dosen',
            'email' => 'dosen@example.com',
            'password' => Hash::make('password123'),
            'role' => 'dosen',
        ]);

        $mahasiswa1 = User::create([
            'name' => 'Mahasiswa Satu',
            'email' => 'mahasiswa@example.com',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa',
        ]);

        Mahasiswa::create([
            'user_id' => $mahasiswa1->id,
            'nim' => '1234567890',
            'jurusan' => 'Teknik Informatika',
            'angkatan' => 2023,
            'phone' => '081234567890',
        ]);

        for ($i = 2; $i <= 10; $i++) {
            $user = User::create([
                'name' => "Mahasiswa $i",
                'email' => "mahasiswa$i@example.com",
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
            ]);

            Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => '123456789' . $i,
                'jurusan' => fake()->randomElement(['Teknik Informatika', 'Sistem Informasi', 'Teknik Komputer']),
                'angkatan' => fake()->numberBetween(2020, 2024),
                'phone' => fake()->phoneNumber(),
            ]);
        }

        $matkul1 = Matkul::create([
            'kode' => 'MK001',
            'nama' => 'Pemrograman Web',
            'dosen_id' => $dosen->id,
            'semester' => 5,
            'credits' => 3,
        ]);

        $matkul2 = Matkul::create([
            'kode' => 'MK002',
            'nama' => 'Basis Data',
            'dosen_id' => $dosen->id,
            'semester' => 4,
            'credits' => 3,
        ]);

        $mahasiswas = Mahasiswa::all();
        foreach ($mahasiswas as $mhs) {
            Enrollment::create([
                'mahasiswa_id' => $mhs->id,
                'matkul_id' => $matkul1->id,
            ]);
            Enrollment::create([
                'mahasiswa_id' => $mhs->id,
                'matkul_id' => $matkul2->id,
            ]);
        }

        $tanggalStart = now()->subDays(14);
        foreach ($mahasiswas as $mhs) {
            foreach ([$matkul1, $matkul2] as $matkul) {
                for ($day = 0; $day < 14; $day++) {
                    if (fake()->boolean(70)) {
                        Presence::create([
                            'matkul_id' => $matkul->id,
                            'mahasiswa_id' => $mhs->id,
                            'tanggal' => $tanggalStart->copy()->addDays($day),
                            'status' => fake()->randomElement(['hadir', 'hadir', 'hadir', 'izin', 'sakit', 'alpha']),
                            'recorded_by' => $dosen->id,
                        ]);
                    }
                }
            }
        }
    }
}
