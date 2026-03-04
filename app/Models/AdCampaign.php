<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdCampaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'advertiser_profile_id',
        'category_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'budget',
        'currency',
        'status',
        'target_city',
        'address_details',
        'campaign_goal',
        'brief',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'progress_percentage' => 'integer',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function advertiser()
    {
        return $this->belongsTo(Profile::class , 'advertiser_profile_id');
    }

    public function advertiserProfile(): BelongsTo
    {
        return $this->belongsTo(Profile::class , 'advertiser_profile_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(AdCategory::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(Profile::class , 'approved_by');
    }

    public function executions(): HasMany
    {
        return $this->hasMany(AdExecution::class , 'campaign_id');
    }
}
