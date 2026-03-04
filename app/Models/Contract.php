<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'party_a_profile_id',
        'party_b_profile_id',
        'contract_type',
        'title',
        'terms',
        'start_date',
        'end_date',
        'value',
        'currency',
        'status',
        'party_a_signed_at',
        'party_b_signed_at',
        'party_a_signature',
        'party_b_signature',
        'version',
        'parent_contract_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'value' => 'decimal:2',
        'version' => 'integer',
        'party_a_signed_at' => 'datetime',
        'party_b_signed_at' => 'datetime',
    ];

    // Relationships
    public function partyAProfile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'party_a_profile_id');
    }

    public function partyBProfile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'party_b_profile_id');
    }

    public function parentContract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'parent_contract_id');
    }

    public function childContracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'parent_contract_id');
    }
}
