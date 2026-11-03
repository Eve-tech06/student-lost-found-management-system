<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_name',
        'description',
    ];

    // A location can have many items
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}