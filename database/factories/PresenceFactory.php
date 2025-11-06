<?php

namespace Database\Factories;

use App\Models\Matkul;
use App\Models\Mahasiswa;
use App\Models\Presence;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PresenceFactory extends Factory
{
    protected $model = Presence::class;

    public function definition(): array
    {
        return [
            'matkul_id' => Matkul::factory(),
            'mahasiswa_id' => Mahasiswa::factory(),
            'tanggal' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'status' => $this->faker->randomElement(['alpha', 'izin', 'sakit', 'hadir']),
            'note' => $this->faker->optional()->sentence(),
            'recorded_by' => User::factory(),
        ];
    }
}

