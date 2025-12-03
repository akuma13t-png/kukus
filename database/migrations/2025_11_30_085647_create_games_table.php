<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->string('genre');
            $table->string('publisher');
            $table->date('release_date');
            $table->string('cover_image');
            $table->boolean('is_featured')->default(false);

            // KOLOM INI YANG SEBELUMNYA HILANG/ERROR
            $table->integer('discount_percent')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};