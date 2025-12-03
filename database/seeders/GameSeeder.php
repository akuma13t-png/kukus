<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game; // Import Model Game

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Hapus semua data Game yang sudah ada
        // Ini penting agar Anda tidak memiliki duplikasi setiap kali menjalankan seeder
        Game::truncate();

        // 2. Buat 50 Data Game BARU menggunakan Factory
        // Factory akan menggunakan Faker untuk mengisi semua kolom secara acak
        Game::factory()->count(50)->create();

        // 3. (Opsional) Buat satu game khusus untuk memastikan selalu ada di featured
        // Ini memastikan tampilan Banner Featured Anda selalu memiliki data yang konsisten
        Game::factory()->create([
            'title' => 'Galactic Frontier 7 (Spesial)',
            'description' => 'Sebuah game RPG sci-fi yang epik tentang penjelajahan luar angkasa dan konflik antar galaksi.',
            'price' => 125000.00,
            'is_featured' => true,
            'discount_percent' => 50, // Diskon 50%
            'genre' => 'RPG',
            'publisher' => 'IndieDev Studio',
            'release_date' => now(),
            'cover_image' => 'https://picsum.photos/400/200?random=1', // Gambar utama
        ]);

    }
}