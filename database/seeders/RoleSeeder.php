<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('role')->insert([
            ['id'=>1,'role_name' => 'Staff Kemahasiswaan', 'created_at' => now(), 'updated_at' => now()],
            ['id'=>2,'role_name' => 'Ketua Jurusan', 'created_at' => now(), 'updated_at' => now()],
            ['id'=>4,'role_name' => 'Wakil Direktur 3', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
