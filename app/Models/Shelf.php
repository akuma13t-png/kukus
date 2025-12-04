<?php

// app/Models/Shelf.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shelf extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Relasi: Satu Shelf punya banyak Game
    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_shelf');
    }
}