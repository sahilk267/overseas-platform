<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MediaFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'media_type',
        'path',
        'filename',
        'mime_type',
        'file_size',
        'width',
        'height',
        'duration',
        'thumbnail_path',
        'metadata',
        'status',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'duration' => 'integer',
        'metadata' => 'array',
    ];

    // Relationships
    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function executionProofs(): HasMany
    {
        return $this->hasMany(ExecutionProof::class);
    }

    public function talentPortfolio(): HasMany
    {
        return $this->hasMany(TalentPortfolio::class);
    }
}
