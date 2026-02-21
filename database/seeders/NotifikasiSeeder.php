<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NotifikasiSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('kode_status')->insert([
            ['id'=>1, 'isi_status' => 'Diajukan'],
            ['id'=>2, 'isi_status' => 'Diproses oleh Ketua Jurusan'],
            ['id'=>3, 'isi_status' => 'Direvisi pada Ketua Jurusan'],
            ['id'=>4, 'isi_status' => 'Diproses oleh Staff Kemahasiswaan'],
            ['id'=>5, 'isi_status' => 'Direvisi pada Pengecekan Staff Kemahasiswaan'],
            ['id'=>6, 'isi_status' => 'Diproses oleh Wakil Direktur 3'],
            ['id'=>7, 'isi_status' => 'Direvisi pada Wakil Direktur 3'],
            ['id'=>8, 'isi_status' => 'Diterima'],
            ['id'=>9, 'isi_status' => 'Ditolak'],
        ]);
    }
}
