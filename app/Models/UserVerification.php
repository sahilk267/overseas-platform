<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'verification_type',
        'verification_code',
        'last_sent_at',
        'status',
        'document_type',
        'document_path',
        'metadata',
        'verified_by',
        'verified_at',
        'expires_at',
        'rejection_reason',
    ];

    protected $casts = [
        'metadata' => 'array',
        'verified_at' => 'datetime',
        'expires_at' => 'datetime',
        'last_sent_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
