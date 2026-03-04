<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronJobLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_name',
        'status',
        'started_at',
        'completed_at',
        'duration_ms',
        'output',
        'error_message',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'duration_ms' => 'integer',
    ];
}
