<?php

namespace Database\Factories;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MahasiswaFactory extends Factory
{
    protected $model = Mahasiswa::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'nim' => $this->faker->unique()->numerify('##########'),
            'jurusan' => $this->faker->randomElement(['Teknik Informatika', 'Sistem Informasi', 'Teknik Komputer']),
            'angkatan' => $this->faker->numberBetween(2020, 2024),
            'phone' => $this->faker->phoneNumber(),
            'photo' => null,
        ];
    }
}

