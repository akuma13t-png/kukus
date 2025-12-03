<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    /**
     * Nama Model yang sesuai dengan factory.
     *
     * @var string
     */
    protected $model = Game::class;

    /**
     * Definisikan state default Model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Daftar Genre dan Publisher fiksi
        $genres = ['Action', 'RPG', 'Strategy', 'Simulation', 'Horror', 'Indie', 'Adventure'];
        $publishers = ['MegaCorp Games', 'IndieDev Studio', 'Quantum Logic', 'Skyline Ent.'];

        return [
            // Judul Fiksi
            'title' => $this->faker->unique()->words(3, true),

            // Deskripsi Fiksi (3-5 paragraf)
            'description' => $this->faker->paragraphs(rand(3, 5), true),

            // Harga Acak antara 50.000 hingga 300.000
            'price' => $this->faker->randomFloat(2, 50000, 300000),

            // Memilih genre dan publisher acak dari daftar
            'genre' => $this->faker->randomElement($genres),
            'publisher' => $this->faker->randomElement($publishers),

            // Tanggal Rilis Acak (sejak 3 tahun lalu hingga hari ini)
            'release_date' => $this->faker->dateTimeBetween('-3 years', 'now'),

            // Gambar Placeholder Unik dari Picsum (Gunakan ID unik)
            'cover_image' => 'https://picsum.photos/400/200?random=' . rand(1, 1000),

            // Peluang 30% menjadi featured
            'is_featured' => $this->faker->boolean(30),

            // Peluang 40% mendapat diskon 10-50%
            'discount_percent' => $this->faker->boolean(40) ? $this->faker->numberBetween(10, 50) : 0,
        ];
    }
}