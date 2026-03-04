<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronJobLock extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_name',
        'locked_at',
        'expires_at',
        'lock_id',
    ];

    protected $casts = [
        'locked_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
}
