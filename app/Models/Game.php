<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Casting kolom screenshots agar otomatis jadi Array/JSON
    protected $casts = [
        'screenshots' => 'array',
        'is_approved' => 'boolean',
        'is_featured' => 'boolean',
        'release_date' => 'date',
    ];
}