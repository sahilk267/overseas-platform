<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExecutionProof extends Model
{
    use HasFactory;

    protected $fillable = [
        'execution_id',
        'uploaded_by',
        'proof_type',
        'media_id',
        'description',
        'latitude',
        'longitude',
        'captured_at',
        'status',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'captured_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    // Relationships
    public function execution(): BelongsTo
    {
        return $this->belongsTo(AdExecution::class, 'execution_id');
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'uploaded_by');
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(MediaFile::class, 'media_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'verified_by');
    }
}
