<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'report_type',
        'description',
        'requested_by_user_id',
        'requested_by_profile_id',
        'parameters',
        'status',
        'file_path',
        'format',
        'completed_at',
        'error_message',
    ];

    protected $casts = [
        'parameters' => 'array',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function requestedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by_user_id');
    }

    public function requestedByProfile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'requested_by_profile_id');
    }
}
