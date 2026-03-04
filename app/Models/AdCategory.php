<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
    ];

    // Relationships
    public function parent(): BelongsTo
    {
        return $this->belongsTo(AdCategory::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(AdCategory::class, 'parent_id');
    }

    public function inventory(): HasMany
    {
        return $this->hasMany(AdInventory::class, 'category_id');
    }
}
