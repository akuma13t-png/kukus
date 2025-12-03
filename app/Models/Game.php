<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory; // Pastikan baris ini berwarna beda di editor (artinya aktif)

    protected $guarded = [];
}