<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EscrowHold extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'amount',
        'currency',
        'status',
        'hold_reason',
        'release_date',
        'released_at',
        'released_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'release_date' => 'date',
        'released_at' => 'datetime',
    ];

    // Relationships
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function releaser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'released_by');
    }
}
