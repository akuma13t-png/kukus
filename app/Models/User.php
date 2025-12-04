<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'publisher_request_status',
        'profile_photo_path', // Tambahkan ini agar bisa di-save
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- ACCESSOR: Untuk mempermudah pemanggilan gambar ---
    // Cara pakai di blade: {{ Auth::user()->profile_photo_url }}
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return Storage::url($this->profile_photo_path);
        }

        // Fallback ke UI Avatars jika tidak ada foto
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&background=1b2838&color=66c0f4&bold=true";
    }

    // --- HELPER ROLES ---
    public function isAdmin() {
        return $this->role === 'admin';
    }

    public function isPublisher() {
        return $this->role === 'publisher' || $this->role === 'admin';
    }
}