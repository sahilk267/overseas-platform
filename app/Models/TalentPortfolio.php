<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TalentPortfolio extends Model
{
    use HasFactory;

    protected $table = 'talent_portfolio';

    protected $fillable = [
        'talent_profile_id',
        'title',
        'description',
        'media_id',
        'display_order',
        'is_featured',
    ];

    protected $casts = [
        'display_order' => 'integer',
        'is_featured' => 'boolean',
    ];

    // Relationships
    public function talentProfile(): BelongsTo
    {
        return $this->belongsTo(TalentProfile::class, 'talent_profile_id');
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(MediaFile::class, 'media_id');
    }
}
