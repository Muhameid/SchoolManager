<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\PaysSeeder;
use Database\Seeders\TestSeeder;
use Database\Seeders\VilleSeeder;
use Database\Seeders\MatiereSeeder;
use Database\Seeders\UserCentralSeeder;

class DatabaseTenantSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // $this->call(TestSeeder::class);
        $this->call(MatiereSeeder::class);
        $this->call(PaysSeeder::class);
        $this->call(VilleSeeder::class);
        $this->call(UserSeeder::class);

    }
}