<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdiSeeder extends Seeder
{
    public function run()
    {
        DB::table('prodi')->insert([
            // Teknik Sipil
            ['id' => 1, 'nama_prodi' => 'D-3 Teknik Konstruksi Sipil', 'jurusan_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama_prodi' => 'D-3 Teknik Konstruksi Gedung', 'jurusan_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama_prodi' => 'D-4 Teknik Perancangan Jalan dan Jembatan', 'jurusan_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama_prodi' => 'D-4 Teknik Perawatan dan Perbaikan Gedung', 'jurusan_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nama_prodi' => 'S-2 Rekayasa Infrastruktur', 'jurusan_id' => 1, 'created_at' => now(), 'updated_at' => now()],

            // Teknik Mesin
            ['id' => 6, 'nama_prodi' => 'D-3 Teknik Mesin', 'jurusan_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'nama_prodi' => 'D-3 Teknik Aeronautika', 'jurusan_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'nama_prodi' => 'D-4 Teknik Perancangan dan Konstruksi Mesin', 'jurusan_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'nama_prodi' => 'D-4 Proses Manufaktur', 'jurusan_id' => 2, 'created_at' => now(), 'updated_at' => now()],

            // Teknik Refrigerasi dan Tata Udara
            ['id' => 10, 'nama_prodi' => 'D-3 Teknik Pendingin dan Tata Udara', 'jurusan_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'nama_prodi' => 'D-4 Teknik Pendingin dan Tata Udara', 'jurusan_id' => 3, 'created_at' => now(), 'updated_at' => now()],

            // Teknik Konversi Energi
            ['id' => 12, 'nama_prodi' => 'D-3 Teknik Konversi Energi', 'jurusan_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'nama_prodi' => 'D-4 Teknologi Pembangkit Tenaga Listrik', 'jurusan_id' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'nama_prodi' => 'D-4 Teknik Konservasi Energi', 'jurusan_id' => 4, 'created_at' => now(), 'updated_at' => now()],

            // Teknik Elektro
            ['id' => 15, 'nama_prodi' => 'D-3 Teknik Elektro', 'jurusan_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 16, 'nama_prodi' => 'D-3 Teknik Listrik', 'jurusan_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 17, 'nama_prodi' => 'D-3 Teknik Telekomunikasi', 'jurusan_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 18, 'nama_prodi' => 'D-4 Teknik Elektronika', 'jurusan_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 19, 'nama_prodi' => 'D-4 Teknik Otomasi Industri', 'jurusan_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 20, 'nama_prodi' => 'D-4 Teknik Telekomunikasi', 'jurusan_id' => 5, 'created_at' => now(), 'updated_at' => now()],

            // Teknik Kimia
            ['id' => 21, 'nama_prodi' => 'D-3 Teknik Kimia', 'jurusan_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 22, 'nama_prodi' => 'D-3 Analis Kimia', 'jurusan_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 23, 'nama_prodi' => 'D-4 Teknik Kimia Produksi Bersih', 'jurusan_id' => 6, 'created_at' => now(), 'updated_at' => now()],


            // Teknik Komputer dan Informatika
            ['id' => 24, 'nama_prodi' => 'D-3 Teknik Informatika', 'jurusan_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 25, 'nama_prodi' => 'D-4 Teknik Informatika', 'jurusan_id' => 7, 'created_at' => now(), 'updated_at' => now()],

            // Akuntansi
            ['id' => 26, 'nama_prodi' => 'D-3 Akuntansi', 'jurusan_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 27, 'nama_prodi' => 'D-3 Keuangan Perbangkan', 'jurusan_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 28, 'nama_prodi' => 'D-4 Akuntansi', 'jurusan_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 29, 'nama_prodi' => 'D-4 Akuntansi Manajemen Pemerintahan', 'jurusan_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 30, 'nama_prodi' => 'D-4 Keuangan Syariah', 'jurusan_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 31, 'nama_prodi' => 'S-2 Keuangan dan Perbangkan Syariah', 'jurusan_id' => 8, 'created_at' => now(), 'updated_at' => now()],

            // Administrasi Niaga
            ['id' => 32, 'nama_prodi' => 'D-3 Administrasi Bisnis', 'jurusan_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 33, 'nama_prodi' => 'D-3 Manajemen Pemasaran', 'jurusan_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 34, 'nama_prodi' => 'D-3 Usaha Perjalanan Wisata', 'jurusan_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 35, 'nama_prodi' => 'D-4 Administrasi Bisnis', 'jurusan_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 36, 'nama_prodi' => 'D-4 Manajemen Aset', 'jurusan_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 37, 'nama_prodi' => 'D-4 Manajemen Pemasaran', 'jurusan_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 38, 'nama_prodi' => 'D-4 Destinasi Pariwisata', 'jurusan_id' => 9, 'created_at' => now(), 'updated_at' => now()],

            // Bahasa Inggris
            ['id' => 39, 'nama_prodi' => 'D-3 Bahasa Inggris', 'jurusan_id' => 10, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
