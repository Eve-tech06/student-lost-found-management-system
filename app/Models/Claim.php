<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'user_id',
        'claim_description',
        'claim_status',
        'claimed_at',
        'reviewed_at',
        'reviewed_by',
    ];

    protected function casts(): array
    {
        return [
            'claimed_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    // A claim belongs to one item
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    // A claim belongs to the user who submitted it
    public function claimant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // A claim may be reviewed by an administrator
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
