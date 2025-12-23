<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(ReviewerSeeder::class);
        $this->call([
            JurusanSeeder::class,
        ]);

        $this->call([
            ProdiSeeder::class
        ]);

        $this->call(MahasiswaSeeder::class);
        $this->call(BeasiswaSeeder::class);
        $this->call(NotifikasiSeeder::class);
    }
}
