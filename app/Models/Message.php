<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sender_profile_id',
        'receiver_profile_id',
        'subject',
        'body',
        'is_read',
        'read_at',
        'parent_message_id',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // Relationships
    public function senderProfile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'sender_profile_id');
    }

    public function receiverProfile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'receiver_profile_id');
    }

    public function parentMessage(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'parent_message_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Message::class, 'parent_message_id');
    }
}
