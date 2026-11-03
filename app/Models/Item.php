<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'description',
        'report_type',
        'status',
        'category_id',
        'location_id',
        'reported_by',
        'date_lost_found',
    ];

    protected function casts(): array
    {
        return [
            'date_lost_found' => 'date',
        ];
    }

    // An item belongs to the user who reported it
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    // An item belongs to one category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // An item belongs to one location
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    // An item can receive many claims
    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    // An item can have multiple images
    public function images(): HasMany
    {
        return $this->hasMany(ItemImage::class);
    }
}
