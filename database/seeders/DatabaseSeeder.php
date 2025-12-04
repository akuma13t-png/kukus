<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil Admin Seeder agar akun admin terbentuk
        $this->call(AdminSeeder::class);
        
        // Panggil Game Seeder (Jika ada, untuk data dummy game)
        $this->call(GameSeeder::class);
    }
}
