<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TalentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'stage_name',
        'specialties',
        'experience_years',
        'hourly_rate',
        'currency',
        'available_for_hire',
        'portfolio_description',
        'languages',
    ];

    protected $casts = [
        'specialties' => 'array',
        'experience_years' => 'integer',
        'hourly_rate' => 'decimal:2',
        'available_for_hire' => 'boolean',
        'languages' => 'array',
    ];

    // Relationships
    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function portfolio(): HasMany
    {
        return $this->hasMany(TalentPortfolio::class, 'talent_profile_id');
    }
}
