<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'profile_type',
        'business_name',
        'display_name',
        'bio',
        'avatar',
        'city',
        'country',
        'location_id',
        'website',
        'social_links',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'rating',
        'review_count',
    ];

    protected $casts = [
        'social_links' => 'array',
        'rating' => 'decimal:2',
        'review_count' => 'integer',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function categories()
    {
        return $this->belongsToMany(AdCategory::class , 'category_profiles', 'profile_id', 'category_id')
            ->withPivot('role_level')
            ->withTimestamps();
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class , 'approved_by');
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(ProfilePermission::class);
    }

    public function mediaFiles(): HasMany
    {
        return $this->hasMany(MediaFile::class);
    }

    public function adInventory(): HasMany
    {
        return $this->hasMany(AdInventory::class , 'vendor_profile_id');
    }

    public function adCampaigns(): HasMany
    {
        return $this->hasMany(AdCampaign::class , 'advertiser_profile_id');
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class);
    }

    public function talentProfile(): HasOne
    {
        return $this->hasOne(TalentProfile::class);
    }

    public function availabilitySlots(): HasMany
    {
        return $this->hasMany(AvailabilitySlot::class);
    }

    public function appointmentsAsProvider(): HasMany
    {
        return $this->hasMany(Appointment::class , 'provider_profile_id');
    }

    public function appointmentsAsRequester(): HasMany
    {
        return $this->hasMany(Appointment::class , 'requester_profile_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class , 'organizer_profile_id');
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public function invoicesAsIssuer(): HasMany
    {
        return $this->hasMany(Invoice::class , 'issuer_profile_id');
    }

    public function invoicesAsRecipient(): HasMany
    {
        return $this->hasMany(Invoice::class , 'recipient_profile_id');
    }

    public function paymentsAsPayer(): HasMany
    {
        return $this->hasMany(Payment::class , 'payer_profile_id');
    }

    public function paymentsAsRecipient(): HasMany
    {
        return $this->hasMany(Payment::class , 'recipient_profile_id');
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(Commission::class , 'recipient_profile_id');
    }

    public function contractsAsPartyA(): HasMany
    {
        return $this->hasMany(Contract::class , 'party_a_profile_id');
    }

    public function contractsAsPartyB(): HasMany
    {
        return $this->hasMany(Contract::class , 'party_b_profile_id');
    }

    public function messagesAsSender(): HasMany
    {
        return $this->hasMany(Message::class , 'sender_profile_id');
    }

    public function messagesAsReceiver(): HasMany
    {
        return $this->hasMany(Message::class , 'receiver_profile_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function reviewsAsReviewer(): HasMany
    {
        return $this->hasMany(Review::class , 'reviewer_profile_id');
    }

    public function reviewsAsReviewed(): HasMany
    {
        return $this->hasMany(Review::class , 'reviewed_profile_id');
    }

    public function disputesAsComplainant(): HasMany
    {
        return $this->hasMany(Dispute::class , 'complainant_profile_id');
    }

    public function disputesAsRespondent(): HasMany
    {
        return $this->hasMany(Dispute::class , 'respondent_profile_id');
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class , 'requested_by_profile_id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    // Helper Methods

    /**
     * Check if the profile has a specific permission.
     */
    public function hasPermission(string $permissionSlug): bool
    {
        return $this->permissions()
            ->whereHas('permission', function ($query) use ($permissionSlug) {
            $query->where('slug', $permissionSlug);
        })
            ->whereNull('revoked_at')
            ->where(function ($query) {
            $query->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        })
            ->exists();
    }

    /**
     * Check if the profile has any of the given permissions.
     */
    public function hasAnyPermission(array $permissionSlugs): bool
    {
        return $this->permissions()
            ->whereHas('permission', function ($query) use ($permissionSlugs) {
            $query->whereIn('slug', $permissionSlugs);
        })
            ->whereNull('revoked_at')
            ->where(function ($query) {
            $query->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        })
            ->exists();
    }

    /**
     * Check if the profile has all of the given permissions.
     */
    public function hasAllPermissions(array $permissionSlugs): bool
    {
        $count = $this->permissions()
            ->whereHas('permission', function ($query) use ($permissionSlugs) {
            $query->whereIn('slug', $permissionSlugs);
        })
            ->whereNull('revoked_at')
            ->where(function ($query) {
            $query->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        })
            ->distinct('permission_id')
            ->count();

        return $count === count($permissionSlugs);
    }

    /**
     * Grant a permission to this profile.
     */
    public function grantPermission(int $permissionId, ?int $grantedBy = null, ?\DateTime $expiresAt = null): ProfilePermission
    {
        // Check if permission already exists and is active
        $existing = $this->permissions()
            ->where('permission_id', $permissionId)
            ->whereNull('revoked_at')
            ->first();

        if ($existing) {
            return $existing;
        }

        return ProfilePermission::create([
            'profile_id' => $this->id,
            'permission_id' => $permissionId,
            'granted_by' => $grantedBy ?? auth()->id(),
            'granted_at' => now(),
            'expires_at' => $expiresAt,
        ]);
    }

    /**
     * Revoke a permission from this profile.
     */
    public function revokePermission(int $permissionId, ?int $revokedBy = null): bool
    {
        $profilePermission = $this->permissions()
            ->where('permission_id', $permissionId)
            ->whereNull('revoked_at')
            ->first();

        if (!$profilePermission) {
            return false;
        }

        $profilePermission->update([
            'revoked_by' => $revokedBy ?? auth()->id(),
            'revoked_at' => now(),
        ]);

        return true;
    }

    /**
     * Get all active permissions for this profile.
     */
    public function activePermissions()
    {
        return $this->permissions()
            ->with('permission')
            ->whereNull('revoked_at')
            ->where(function ($query) {
            $query->whereNull('expires_at')
                ->orWhere('expires_at', '>', now());
        })
            ->get();
    }

    /**
     * Check if the profile is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the profile is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'active' && !is_null($this->approved_at);
    }

    /**
     * Check if the profile is of a specific type.
     */
    public function isType(string $type): bool
    {
        return $this->profile_type === $type;
    }

    /**
     * Check if the profile is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->profile_type === 'admin';
    }
}
