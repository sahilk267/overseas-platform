<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'state',
        'country',
        'latitude',
        'longitude',
        'timezone',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    // Relationships
    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class);
    }

    public function adInventory(): HasMany
    {
        return $this->hasMany(AdInventory::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
