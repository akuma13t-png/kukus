<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Menambahkan kolom ke tabel 'users' yang SUDAH ADA
        Schema::table('users', function (Blueprint $table) {
            // Role: user, publisher, admin
            // Kita taruh setelah kolom email agar rapi di database
            $table->string('role')->default('user')->after('email'); 
            
            // Status request publisher: null, pending, approved, rejected
            $table->string('publisher_request_status')->nullable()->after('role');
        });

        // Menambahkan kolom ke tabel 'games' yang SUDAH ADA
        Schema::table('games', function (Blueprint $table) {
            // Game harus diapprove admin dulu sebelum muncul di store
            $table->boolean('is_approved')->default(false)->after('price');
        });
    }

    public function down()
    {
        // Kebalikan dari up() untuk rollback
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'publisher_request_status']);
        });

        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn('is_approved');
        });
    }
};