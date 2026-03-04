<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PromotionAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'promotion_id',
        'target_type',
        'target_id',
        'assigned_at',
        'status',
        'cost',
        'currency',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'cost' => 'decimal:2',
    ];

    // Relationships
    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class, 'promotion_id');
    }

    public function target(): MorphTo
    {
        return $this->morphTo('target');
    }
}
