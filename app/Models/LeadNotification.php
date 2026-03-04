<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'vendor_profile_id',
        'status',
        'notified_at',
        'responded_at',
    ];

    protected $casts = [
        'notified_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(AdCampaign::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Profile::class , 'vendor_profile_id');
    }
}
