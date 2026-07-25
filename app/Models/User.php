<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'alamat',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* ============ RELASI ============ */
    public function pemesanans(): HasMany
    {
        return $this->hasMany(Pemesanan::class);
    }

    /* ============ HELPER ROLE ============ */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKlien(): bool
    {
        return $this->role === 'klien';
    }
}
