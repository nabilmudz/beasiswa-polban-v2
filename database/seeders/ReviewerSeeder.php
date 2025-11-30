<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewerSeeder extends Seeder
{
    public function run()
    {
        DB::table('reviewer')->insert([
            [
                'user_id' => 1,
                'nip' => 'NIP' . rand(1000000000, 9999999999),
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'nip' => 'NIP' . rand(1000000000, 9999999999),
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'nip' => 'NIP' . rand(1000000000, 9999999999),
                'role_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [   'user_id' => 5,
                'nip' => 'NIP' . rand(1000000000, 9999999999),
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [   'user_id' => 6,
                'nip' => 'NIP' . rand(1000000000, 9999999999),
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [   'user_id' => 7,
                'nip' => 'NIP' . rand(1000000000, 9999999999),
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [   'user_id' => 8,
                'nip' => 'NIP' . rand(1000000000, 9999999999),
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [   'user_id' => 9,
                'nip' => 'NIP' . rand(1000000000, 9999999999),
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [   'user_id' => 10,
                'nip' => 'NIP' . rand(1000000000, 9999999999),
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [   'user_id' => 11,
                'nip' => 'NIP' . rand(1000000000, 9999999999),
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [   'user_id' => 12,
                'nip' => 'NIP' . rand(1000000000, 9999999999),
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [   'user_id' => 13,
                'nip' => 'NIP' . rand(1000000000, 9999999999),
                'role_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'user_id' => 14,
                'nip' => 'NIP' . rand(1000000000, 9999999999),
                'role_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 15,
                'nip' => 'NIP' . rand(1000000000, 9999999999),
                'role_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
