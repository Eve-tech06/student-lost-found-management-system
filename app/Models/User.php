<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // A user can report many items
    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'reported_by');
    }

    // A user can submit many claims
    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    // An administrator can review many claims
    public function reviewedClaims(): HasMany
    {
        return $this->hasMany(Claim::class, 'reviewed_by');
    }
}
