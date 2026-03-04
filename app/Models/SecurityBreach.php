<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SecurityBreach extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_id',
        'detected_at',
        'breach_type',
        'severity',
        'description',
        'affected_data_types',
        'affected_users_count',
        'status',
        'authority_notified',
        'authority_notified_at',
        'users_notified',
        'users_notified_at',
        'response_actions',
        'reported_by',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
        'affected_data_types' => 'array',
        'affected_users_count' => 'integer',
        'authority_notified' => 'boolean',
        'authority_notified_at' => 'datetime',
        'users_notified' => 'boolean',
        'users_notified_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    // Relationships
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function affectedUsers(): HasMany
    {
        return $this->hasMany(SecurityBreachAffectedUser::class, 'breach_id');
    }
}
