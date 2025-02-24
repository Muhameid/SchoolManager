<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserCentralSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'login' => 'admin1',
                'name' => 'Lounes',
                'email' => 'Amine',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'login' => 'admin2',
                'name' => 'Vincent',
                'email' => 'Vincent',
                'email_verified_at' => now(),
                'password' => Hash::make('adminpass456'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
