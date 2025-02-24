<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Professeur;
use App\Models\Administrateur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TestSeeder extends Seeder
{
    public function run()
    {

        // Créer des professeurs
        for ($i = 12; $i <= 17; $i++) {
            $professeur = Professeur::create(['identifiant'=>'titi'.$i]);

            User::create([
                'login' => 'prof' . $i,
                'nom' => 'Professeur ' . $i,
                'prenom' => 'Prenom ' . $i,
                'usereable_id' => $professeur->id,
                'usereable_type' => Professeur::class,
                'ville_id' => 3039154,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'adresse' => 'Adresse ' . $i,
                'date_naissance' => now()->subYears(30 + $i),
                'telephone_1' => '066100000' . $i,
                'telephone_2' => '055200000' . $i,
                'telephone_3' => '077300000' . $i,
                'remember_token' => null
            ]);
        }

        // Créer des administrateurs
        for ($i = 12; $i <= 17; $i++) {
            $admin = Administrateur::create(['droit'=>1]);

            User::create([
                'login' => 'admin' . $i,
                'nom' => 'Admin ' . $i,
                'prenom' => 'Prenom ' . $i,
                'usereable_id' => $admin->id,
                'usereable_type' => Administrateur::class,
                'ville_id' => 3039154,
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'adresse' => 'Adresse Admin ' . $i,
                'date_naissance' => now()->subYears(40 + $i),
                'telephone_1' => '06610000A' . $i,
                'telephone_2' => '05520000A' . $i,
                'telephone_3' => '07730000A' . $i,
                'remember_token' => null
            ]);
        }
    }
}
