<?php

namespace App\Traits;

use App\Models\AdminAction;

trait LogsAdminActions
{
    /**
     * Log wallet charge action
     */
    protected function logWalletCharge($target, float $amount, ?string $note = null): void
    {
        
            AdminAction::logWalletCharge(
                auth()->id(),
                $target,
                $amount,
                $note
            );
        
    }

    /**
     * Log withdraw sum action
     */
    protected function logWithdrawSum($target, float $amount, ?string $note = null): void
    {
        
            AdminAction::logWithdrawSum(
                auth()->id(),
                $target,
                $amount,
                $note
            );
        
    }

    /**
     * Log purchase subscription action
     */
    protected function logPurchaseSubscription($target, array $subscriptionData, ?string $note = null): void
    {
        
            AdminAction::logPurchaseSubscription(
                auth()->id(),
                $target,
                $subscriptionData,
                $note
            );
        
    }

    /**
     * Log change user status action
     */
    protected function logChangeUserStatus($target, string $oldStatus, string $newStatus, ?string $note = null): void
    {
        
            AdminAction::logChangeUserStatus(
                auth()->id(),
                $target,
                $oldStatus,
                $newStatus,
                $note
            );
        
    }

    /**
     * Log change driver status action
     */
    protected function logChangeDriverStatus($target, string $oldStatus, string $newStatus, ?string $note = null): void
    {
        
            AdminAction::logChangeDriverStatus(
                auth()->id(),
                $target,
                $oldStatus,
                $newStatus,
                $note
            );
        
    }
}
