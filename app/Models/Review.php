<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviewer_profile_id',
        'reviewed_profile_id',
        'related_type',
        'related_id',
        'rating',
        'comment',
        'response',
        'response_at',
        'is_verified',
    ];

    protected $casts = [
        'rating' => 'integer',
        'response_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    // Note: review_related_type_uk and review_related_id_uk are generated columns - not fillable

    // Relationships
    public function reviewerProfile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'reviewer_profile_id');
    }

    public function reviewedProfile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'reviewed_profile_id');
    }

    public function related(): MorphTo
    {
        return $this->morphTo('related');
    }
}
