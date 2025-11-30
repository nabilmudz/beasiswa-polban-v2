<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mahasiswa')->insert([
            [
                'user_id' => 16,
                'nim' => '231511099',
                'semester' => 3,
                'no_hp' => '08812345678',
                'tgl_lahir' => '2001-07-20',
                'prodi_id' => 24,
                'angkatan' => 2023,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
