<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'requester_profile_id',
        'provider_profile_id',
        'scheduled_at',
        'end_at',
        'location_id',
        'meeting_type',
        'meeting_url',
        'notes',
        'status',
        'confirmed_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'end_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Relationships
    public function requesterProfile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'requester_profile_id');
    }

    public function providerProfile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'provider_profile_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
