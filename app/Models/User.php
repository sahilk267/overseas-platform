<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'email',
        'password',
        'phone',
        'status',
        'email_verified_at',
        'phone_verified_at',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'two_factor_enabled' => 'boolean',
        'two_factor_recovery_codes' => 'array',
        'last_login_at' => 'datetime',
    ];

    // Relationships
    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class);
    }

    public function verifications(): HasMany
    {
        return $this->hasMany(UserVerification::class);
    }

    public function approvedProfiles(): HasMany
    {
        return $this->hasMany(Profile::class, 'approved_by');
    }

    public function profilePermissionsGranted(): HasMany
    {
        return $this->hasMany(ProfilePermission::class, 'granted_by');
    }

    public function profilePermissionsRevoked(): HasMany
    {
        return $this->hasMany(ProfilePermission::class, 'revoked_by');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    // Helper Methods

    /**
     * Get the currently active profile from session.
     */
    public function activeProfile(): ?Profile
    {
        $profileId = session('current_profile_id');
        
        if (!$profileId) {
            return null;
        }

        return $this->profiles()->find($profileId);
    }

    /**
     * Check if user has a specific profile type.
     */
    public function hasProfile(string $profileType): bool
    {
        return $this->profiles()
            ->where('profile_type', $profileType)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Get profiles of a specific type.
     */
    public function getProfilesByType(string $profileType)
    {
        return $this->profiles()
            ->where('profile_type', $profileType)
            ->get();
    }

    /**
     * Switch to a specific profile (store in session).
     */
    public function switchProfile(int $profileId): bool
    {
        $profile = $this->profiles()->find($profileId);

        if (!$profile || $profile->status !== 'active') {
            return false;
        }

        session(['current_profile_id' => $profileId]);
        
        return true;
    }

    /**
     * Check if the current active profile has a specific permission.
     */
    public function hasPermission(string $permissionSlug): bool
    {
        $profile = $this->activeProfile();

        if (!$profile) {
            return false;
        }

        return $profile->hasPermission($permissionSlug);
    }

    /**
     * Check if the user can access admin features.
     */
    public function isAdmin(): bool
    {
        return $this->profiles()
            ->where('profile_type', 'admin')
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Get all active profiles for the user.
     */
    public function activeProfiles()
    {
        return $this->profiles()
            ->where('status', 'active')
            ->get();
    }
}
