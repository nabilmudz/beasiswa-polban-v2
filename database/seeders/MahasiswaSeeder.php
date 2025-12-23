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
        $mahasiswa = [
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
            ],
            [
                'user_id' => 17,
                'nim' => '231511100',
                'semester' => 5,
                'no_hp' => '08887654321',
                'tgl_lahir' => '2000-05-15',
                'prodi_id' => 25,
                'angkatan' => 2022,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 18,
                'nim' => '231511101',
                'semester' => 7,
                'no_hp' => '08911223344',
                'tgl_lahir' => '1999-03-10',
                'prodi_id' => 26,
                'angkatan' => 2021,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        foreach ($mahasiswa as $mhs) {
            DB::table('mahasiswa')->updateOrInsert(
                ['nim' => $mhs['nim']], // Kondisi cek: berdasarkan NIM
                $mhs // Data yang akan diinsert atau diupdate
            );
        }
    }
}
