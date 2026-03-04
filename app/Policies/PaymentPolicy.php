<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaymentPolicy
{
    /**
     * Determine whether the user can view any payments.
     */
    public function viewAny(User $user): bool
    {
        // Users with active profile can view payments
        $profile = $user->activeProfile();
        
        return $profile && $profile->isActive();
    }

    /**
     * Determine whether the user can view the payment.
     */
    public function view(User $user, Payment $payment): bool
    {
        $profile = $user->activeProfile();
        
        if (!$profile) {
            return false;
        }

        // Payer or recipient can view
        if ($payment->payer_profile_id === $profile->id || $payment->recipient_profile_id === $profile->id) {
            return true;
        }

        // Admins can view any payment
        if ($profile->isAdmin()) {
            return true;
        }

        // Users with view_payments permission can view
        return $profile->hasPermission('view_all_payments');
    }

    /**
     * Determine whether the user can create payments.
     */
    public function create(User $user): bool
    {
        $profile = $user->activeProfile();
        
        if (!$profile || !$profile->isActive()) {
            return false;
        }

        // Any active profile can create payments
        return true;
    }

    /**
     * Determine whether the user can update the payment.
     */
    public function update(User $user, Payment $payment): bool
    {
        $profile = $user->activeProfile();
        
        if (!$profile) {
            return false;
        }

        // Payments cannot be updated after success (immutable)
        if ($payment->status === 'success') {
            return false;
        }

        // Only admins can update payments
        return $profile->isAdmin();
    }

    /**
     * Determine whether the user can delete the payment.
     */
    public function delete(User $user, Payment $payment): bool
    {
        // Payments should not be deleted (use refunds instead)
        // Only admins can delete failed/pending payments
        $profile = $user->activeProfile();
        
        if (!$profile || !$profile->isAdmin()) {
            return false;
        }

        // Can only delete failed or pending payments
        return in_array($payment->status, ['failed', 'pending']);
    }

    /**
     * Determine whether the user can restore the payment.
     */
    public function restore(User $user, Payment $payment): bool
    {
        $profile = $user->activeProfile();
        
        // Only admins can restore
        return $profile && $profile->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the payment.
     */
    public function forceDelete(User $user, Payment $payment): bool
    {
        // Payments should never be permanently deleted (audit trail)
        return false;
    }

    /**
     * Determine whether the user can refund the payment.
     */
    public function refund(User $user, Payment $payment): bool
    {
        $profile = $user->activeProfile();
        
        if (!$profile) {
            return false;
        }

        // Payment must be successful to refund
        if ($payment->status !== 'success') {
            return false;
        }

        // Recipient or admin can initiate refund
        if ($payment->recipient_profile_id === $profile->id || $profile->isAdmin()) {
            return true;
        }

        // Users with refund_payments permission can refund
        return $profile->hasPermission('process_refunds');
    }
}
