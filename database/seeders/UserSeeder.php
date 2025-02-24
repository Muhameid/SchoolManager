<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'login' => 'admin1',
                'nom' => 'Lounes',
                'prenom' => 'Amine',
                'usereable_id' => 1,
                'usereable_type' => 'App\Models\Administrateur',
                'ville_id' => 3039154,
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'adresse' => '10 rue des Admins, Paris',
                'date_naissance' => Carbon::parse('1985-06-15'),
                'telephone_1' => '0612345678',
                'telephone_2' => '0698765432',
                'telephone_3' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'login' => 'admin2',
                'nom' => 'Vincent',
                'prenom' => 'Paul',
                'usereable_id' => 2,
                'usereable_type' => 'App\Models\Professeur',
                'ville_id' => 3039154,
                'email_verified_at' => now(),
                'password' => Hash::make('adminpass456'),
                'adresse' => '25 avenue des Administrateurs, Lyon',
                'date_naissance' => Carbon::parse('1990-03-22'),
                'telephone_1' => '0654321876',
                'telephone_2' => '',
                'telephone_3' => '',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
