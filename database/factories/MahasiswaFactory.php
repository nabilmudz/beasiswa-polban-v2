<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Prodi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mahasiswa>
 */
class MahasiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Menghubungkan ke pengguna baru
            'nim' => $this->faker->numerify('#########'), // 9-digit NIM
            'semester' => $this->faker->numberBetween(1, 8),
            'tgl_lahir' => $this->faker->date(),
            'prodi_id' => Prodi::factory(), // Sesuaikan jika Prodi sudah ada
            'angkatan' => $this->faker->year('2024'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
