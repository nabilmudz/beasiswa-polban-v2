<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('users')->insert([
            [
                'id'=>1,
                'nama_depan' => 'John',
                'nama_belakang' => 'Doe',
                'email' => 'admin@polban.ac.id',
                'jenis_kelamin' => 'Pria',
                'foto' => 'example.jpg',
                'password' => bcrypt('password123'),
                'emailVerif' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'isActive' => true
            ],
            [
                'id'=>3,
                'nama_depan' => 'Staff Kemahasiswaan',
                'nama_belakang' => 'Satu',
                'email' => 'staff.kema@polban.ac.id',
                'jenis_kelamin' => 'Pria',
                'foto' => 'example.jpg',
                'password' => bcrypt('password123'),
                'emailVerif' => true,
                'created_at' => now(),
                'updated_at' => now(),
                'isActive' => true
            ],
            [
                'id'=>4,
                'nama_depan' => 'Kepala Jurusan',
                'nama_belakang' => 'Teknik Sipil',
                'email' => 'kajur.sipil@polban.ac.id',
                'jenis_kelamin' => 'Pria',
                'password' => bcrypt('password123'),
                'emailVerif' => true,
                'foto' => 'example.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'isActive' => true
            ],
            [
                'id'=>5,
                'nama_depan' => 'Kepala Jurusan',
                'nama_belakang' => 'Teknik Mesin',
                'email' => 'kajur.mesin@polban.ac.id',
                'jenis_kelamin' => 'Wanita',
                'password' => bcrypt('password123'),
                'emailVerif' => true,
                'foto' => 'example.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'isActive' => true
            ],
            [
                'id'=>6,
                'nama_depan' => 'Kepala Jurusan',
                'nama_belakang' => 'Teknik Refrigerasi dan Tata Udara',
                'email' => 'kajur.refri@polban.ac.id',
                'password' => bcrypt('password123'),
                'emailVerif' => true,
                'jenis_kelamin' => 'Pria',
                'foto' => 'example.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'isActive' => true
            ],
            [
                'id' => 7,
                'nama_depan' => 'Kepala Jurusan',
                'nama_belakang' => 'Teknik Konversi Energi',
                'email' => 'kajur.konversi@polban.ac.id',
                'password' => bcrypt('password123'),
                'emailVerif' => true,
                'jenis_kelamin' => 'Pria',
                'foto' => 'example.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'isActive' => true
            ],
            [
                'id' => 8,
                'nama_depan' => 'Kepala Jurusan',
                'nama_belakang' => 'Teknik Elektro',
                'email' => 'kajur.elektro@polban.ac.id',
                'password' => bcrypt('password123'),
                'emailVerif' => true,
                'jenis_kelamin' => 'Pria',
                'foto' => 'example.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'isActive' => true
            ],
            [
                'id' => 9,
                'nama_depan' => 'Kepala Jurusan',
                'nama_belakang' => 'Teknik Kimia',
                'email' => 'kajur.kimia@polban.ac.id',
                'password' => bcrypt('password123'),
                'emailVerif' => true,
                'jenis_kelamin' => 'Pria',
                'foto' => 'example.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'isActive' => true
            ],
            [
                'id' => 10,
                'nama_depan' => 'Kepala Jurusan',
                'nama_belakang' => 'Teknik Komputer dan Informatika',
                'email' => 'kajur.kominfo@polban.ac.id',
                'password' => bcrypt('password123'),
                'emailVerif' => true,
                'jenis_kelamin' => 'Pria',
                'foto' => 'example.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'isActive' => true
            ],
            [
                'id' => 11,
                'nama_depan' => 'Kepala Jurusan',
                'nama_belakang' => 'Akuntansi',
                'email' => 'kajur.akuntansi@polban.ac.id',
                'password' => bcrypt('password123'),
                'emailVerif' => true,
                'jenis_kelamin' => 'Pria',
                'foto' => 'example.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'isActive' => true
            ],
            [
                'id' => 12,
                'nama_depan' => 'Kepala Jurusan',
                'nama_belakang' => 'Administrasi Niaga',
                'email' => 'kajur.niaga@polban.ac.id',
                'password' => bcrypt('password123'),
                'emailVerif' => true,
                'jenis_kelamin' => 'Pria',
                'foto' => 'example.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'isActive' => true
            ],

            [

                'id' => 13,
                'nama_depan' => 'Kepala Jurusan',
                'nama_belakang' => 'Bahasa Inggris',
                'email' => 'kajur.inggris@polban.ac.id',
                'password' => bcrypt('password123'),
                'emailVerif' => true,
                'jenis_kelamin' => 'Pria',
                'foto' => 'example.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'isActive' => true
            ],
            [
                'id'=>14,
                'nama_depan' => 'Koordinator Layanan Eksternal',
                'nama_belakang' => 'Satu',
                'email' => 'kle.satu@polban.ac.id',
                'password' => bcrypt('password123'),
                'emailVerif' => true,
                'jenis_kelamin' => 'Pria',
                'foto' => 'example.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'isActive' => true
            ],
            [
                'id'=>15,
                'nama_depan' => 'Wakil Direktur',
                'nama_belakang' => 'Tiga',
                'email' => 'wd.tiga@polban.ac.id',
                'password' => bcrypt('password123'),
                'emailVerif' => true,
                'jenis_kelamin' => 'Pria',
                'foto' => 'example.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'isActive' => true
            ],
            [
                'id'=>16,
                'nama_depan' => 'Mahasiswa',
                'nama_belakang' => 'JTK',
                'email' => 'mahasiswa.jtk@polban.ac.id',
                'password' => bcrypt('password123'),
                'emailVerif' => true,
                'jenis_kelamin' => 'Pria',
                'foto' => 'example.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                'isActive' => true
            ],
        ]);

    }
}
