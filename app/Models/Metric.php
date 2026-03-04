<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Metric extends Model
{
    use HasFactory;

    protected $fillable = [
        'metric_type',
        'category',
        'entity_type',
        'entity_id',
        'value',
        'unit',
        'metadata',
        'recorded_at',
    ];

    protected $casts = [
        'value' => 'decimal:4',
        'metadata' => 'array',
        'recorded_at' => 'datetime',
    ];

    // Relationships
    public function entity(): MorphTo
    {
        return $this->morphTo('entity');
    }
}
