<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JurusanSeeder extends Seeder
{
    public function run()
    {
        DB::table('jurusan')->insert([
            ['id' => 1, 'nama_jurusan' => 'Teknik Sipil', 'kajur_id' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nama_jurusan' => 'Teknik Mesin', 'kajur_id' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nama_jurusan' => 'Teknik Refrigerasi dan Tata Udara', 'kajur_id' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nama_jurusan' => 'Teknik Konversi Energi', 'kajur_id' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nama_jurusan' => 'Teknik Elektro', 'kajur_id' => 9, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'nama_jurusan' => 'Teknik Kimia', 'kajur_id' => 11, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'nama_jurusan' => 'Teknik Komputer dan Informatika', 'kajur_id' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'nama_jurusan' => 'Akuntansi', 'kajur_id' => 12, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'nama_jurusan' => 'Administrasi Niaga', 'kajur_id' => 13, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'nama_jurusan' => 'Bahasa Inggris', 'kajur_id' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
