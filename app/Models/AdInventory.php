<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdInventory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ad_inventory';

    protected $fillable = [
        'vendor_profile_id',
        'category_id',
        'title',
        'description',
        'inventory_type',
        'dimensions',
        'location_id',
        'price_per_day',
        'currency',
        'min_booking_days',
        'requires_approval',
        'status',
    ];

    protected $casts = [
        'price_per_day' => 'decimal:2',
        'min_booking_days' => 'integer',
        'requires_approval' => 'boolean',
    ];

    // Relationships
    public function vendorProfile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'vendor_profile_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(AdCategory::class, 'category_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function executions(): HasMany
    {
        return $this->hasMany(AdExecution::class, 'inventory_id');
    }
}
