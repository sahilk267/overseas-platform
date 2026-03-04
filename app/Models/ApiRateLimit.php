<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiRateLimit extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'identifier_type',
        'endpoint',
        'hits',
        'window_start',
        'window_end',
    ];

    protected $casts = [
        'hits' => 'integer',
        'window_start' => 'datetime',
        'window_end' => 'datetime',
    ];
}
