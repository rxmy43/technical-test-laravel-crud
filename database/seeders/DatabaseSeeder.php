<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = [];

        for ($i = 51; $i <= 200; $i++) {
            $users[] = [
                'name' => "User $i",
                'email' => "user$i@example.com",
                'age' => rand(18, 60),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('users')->insert($users);
    }
}
