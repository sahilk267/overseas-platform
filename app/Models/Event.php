<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organizer_profile_id',
        'name',
        'description',
        'event_type',
        'start_datetime',
        'end_datetime',
        'location_id',
        'venue_name',
        'venue_address',
        'expected_attendees',
        'budget',
        'currency',
        'status',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'expected_attendees' => 'integer',
        'budget' => 'decimal:2',
    ];

    // Relationships
    public function organizerProfile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'organizer_profile_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(EventService::class, 'event_id');
    }
}
