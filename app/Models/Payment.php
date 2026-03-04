<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payer_profile_id',
        'recipient_profile_id',
        'execution_id',
        'invoice_id',
        'amount',
        'fees',
        // net_amount is GENERATED - DO NOT include in fillable
        'currency',
        'payment_method',
        'transaction_id',
        'idempotency_key',
        'status',
        'completed_at',
        'failure_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fees' => 'decimal:2',
        // net_amount is generated - will be available but read-only
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function payerProfile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'payer_profile_id');
    }

    public function recipientProfile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'recipient_profile_id');
    }

    public function execution(): BelongsTo
    {
        return $this->belongsTo(AdExecution::class, 'execution_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class, 'payment_id');
    }

    public function escrowHolds(): HasMany
    {
        return $this->hasMany(EscrowHold::class, 'payment_id');
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class, 'payment_id');
    }
}
