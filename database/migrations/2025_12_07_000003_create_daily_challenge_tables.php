<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Vouchers Table (Shop Items)
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., DISCOUNT10
            $table->string('name'); // e.g., "10% Off Voucher"
            $table->text('description')->nullable();
            $table->integer('cost_in_coins'); // e.g., 500
            $table->decimal('discount_amount', 10, 2)->default(0); // Fixed amount e.g. 10000
            $table->integer('discount_percent')->default(0); // Percent e.g. 10
            $table->enum('type', ['fixed', 'percent']);
            $table->timestamps();
        });

        // 2. User Vouchers (Inventory)
        Schema::create('user_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('voucher_id')->constrained()->onDelete('cascade');
            $table->boolean('is_used')->default(false);
            $table->timestamps();
        });

        // 3. Daily Completions (Tracking)
        Schema::create('daily_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date'); // To ensure only 1 per day
            $table->string('game_type'); // e.g., 'tictactoe', 'rps'
            $table->integer('coins_earned');
            $table->timestamps();

            $table->unique(['user_id', 'date']); // Prevent multiple completions per day
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_completions');
        Schema::dropIfExists('user_vouchers');
        Schema::dropIfExists('vouchers');
    }
};
