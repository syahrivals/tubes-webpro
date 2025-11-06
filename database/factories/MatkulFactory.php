<?php

namespace Database\Factories;

use App\Models\Matkul;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MatkulFactory extends Factory
{
    protected $model = Matkul::class;

    public function definition(): array
    {
        return [
            'kode' => $this->faker->unique()->bothify('MK###'),
            'nama' => $this->faker->words(3, true),
            'dosen_id' => User::factory()->state(['role' => 'dosen']),
            'semester' => $this->faker->numberBetween(1, 8),
            'credits' => $this->faker->numberBetween(2, 4),
        ];
    }
}

