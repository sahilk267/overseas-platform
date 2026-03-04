<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Dispute extends Model
{
    use HasFactory;

    protected $fillable = [
        'complainant_profile_id',
        'respondent_profile_id',
        'related_type',
        'related_id',
        'dispute_type',
        'description',
        'disputed_amount',
        'currency',
        'status',
        'resolution',
        'resolution_notes',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'disputed_amount' => 'decimal:2',
        'resolved_at' => 'datetime',
    ];

    // Relationships
    public function complainantProfile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'complainant_profile_id');
    }

    public function respondentProfile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'respondent_profile_id');
    }

    public function related(): MorphTo
    {
        return $this->morphTo('related');
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(DisputeMessage::class, 'dispute_id');
    }

    public function escalations(): HasMany
    {
        return $this->hasMany(Escalation::class, 'dispute_id');
    }
}
