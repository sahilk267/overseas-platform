<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class EventService extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'linked_type',
        'linked_id',
        'service_description',
        'cost',
        'currency',
        'status',
    ];

    protected $casts = [
        'cost' => 'decimal:2',
    ];

    // Relationships
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function linked(): MorphTo
    {
        return $this->morphTo('linked');
    }
}
