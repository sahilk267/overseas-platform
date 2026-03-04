<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityBreachAffectedUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'breach_id',
        'user_id',
        'affected_data_fields',
        'notified',
        'notified_at',
    ];

    protected $casts = [
        'affected_data_fields' => 'array',
        'notified' => 'boolean',
        'notified_at' => 'datetime',
    ];

    // Relationships
    public function breach(): BelongsTo
    {
        return $this->belongsTo(SecurityBreach::class, 'breach_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
