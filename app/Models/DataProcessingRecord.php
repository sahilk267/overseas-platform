<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataProcessingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'processing_purpose',
        'data_categories',
        'legal_basis',
        'recipients',
        'retention_period',
        'security_measures',
        'data_controller_user_id',
    ];

    protected $casts = [
        'data_categories' => 'array',
    ];

    // Relationships
    public function dataController(): BelongsTo
    {
        return $this->belongsTo(User::class, 'data_controller_user_id');
    }
}
