<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdExecution extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'inventory_id',
        'execution_date',
        'end_date',
        'cost',
        'currency',
        'status',
        'notes',
        'idempotency_key',
    ];

    protected $casts = [
        'execution_date' => 'date',
        'end_date' => 'date',
        'cost' => 'decimal:2',
    ];

    // Relationships
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(AdCampaign::class, 'campaign_id');
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(AdInventory::class, 'inventory_id');
    }

    public function proofs(): HasMany
    {
        return $this->hasMany(ExecutionProof::class, 'execution_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'execution_id');
    }

    public function eventServices(): HasMany
    {
        return $this->hasMany(EventService::class, 'linked_id')
            ->where('linked_type', 'ad_execution');
    }
}
