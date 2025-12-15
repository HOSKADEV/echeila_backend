<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'action_type',
        'target_id',
        'target_type',
        'old_values',
        'new_values',
        'amount',
        'note',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'amount' => 'decimal:2',
    ];

    // Action type constants
    const WALLET_CHARGE = 'wallet_charge';
    const WITHDRAW_SUM = 'withdraw_sum';
    const PURCHASE_SUBSCRIPTION = 'purchase_subscription';
    const CHANGE_USER_STATUS = 'change_user_status';
    const CHANGE_DRIVER_STATUS = 'change_driver_status';

    /**
     * Get all action types
     */
    public static function actionTypes(): array
    {
        return [
            self::WALLET_CHARGE,
            self::WITHDRAW_SUM,
            self::PURCHASE_SUBSCRIPTION,
            self::CHANGE_USER_STATUS,
            self::CHANGE_DRIVER_STATUS,
        ];
    }

    /**
     * Get action type labels
     */
    public static function actionTypeLabels(): array
    {
        return [
            self::WALLET_CHARGE => __('Wallet Charge'),
            self::WITHDRAW_SUM => __('Withdraw Sum'),
            self::PURCHASE_SUBSCRIPTION => __('Purchase Subscription'),
            self::CHANGE_USER_STATUS => __('Change User Status'),
            self::CHANGE_DRIVER_STATUS => __('Change Driver Status'),
        ];
    }

    // Relationships

    /**
     * Get the admin who performed the action
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * Get the target model (Driver, Passenger, User)
     */
    public function target()
    {
        return $this->morphTo();
    }

    // Helper methods

    /**
     * Get the action type label
     */
    public function getActionTypeLabelAttribute(): string
    {
        return self::actionTypeLabels()[$this->action_type] ?? $this->action_type;
    }

    /**
     * Create a log entry for wallet charge
     */
    public static function logWalletCharge(int $adminId, $target, float $amount, ?string $note = null): self
    {
        return self::create([
            'admin_id' => $adminId,
            'action_type' => self::WALLET_CHARGE,
            'target_id' => $target->id,
            'target_type' => get_class($target),
            'amount' => $amount,
            'old_values' => [
                'balance' => $target->user->wallet->balance ?? 0,
            ],
            'new_values' => [
                'balance' => ($target->user->wallet->balance ?? 0) + $amount,
            ],
            'note' => $note,
        ]);
    }

    /**
     * Create a log entry for withdraw sum
     */
    public static function logWithdrawSum(int $adminId, $target, float $amount, ?string $note = null): self
    {
        return self::create([
            'admin_id' => $adminId,
            'action_type' => self::WITHDRAW_SUM,
            'target_id' => $target->id,
            'target_type' => get_class($target),
            'amount' => $amount,
            'old_values' => [
                'balance' => $target->user->wallet->balance ?? 0,
            ],
            'new_values' => [
                'balance' => ($target->user->wallet->balance ?? 0) - $amount,
            ],
            'note' => $note,
        ]);
    }

    /**
     * Create a log entry for purchase subscription
     */
    public static function logPurchaseSubscription(int $adminId, $target, array $subscriptionData, ?string $note = null): self
    {
        return self::create([
            'admin_id' => $adminId,
            'action_type' => self::PURCHASE_SUBSCRIPTION,
            'target_id' => $target->id,
            'target_type' => get_class($target),
            'amount' => $subscriptionData['amount'] ?? null,
            'old_values' => [
                'end_date' => $subscriptionData['old_date'],
            ],
            'new_values' => [
                'end_date' => $subscriptionData['new_date'] ?? null,
            ],
            'note' => $note,
        ]);
    }

    /**
     * Create a log entry for user status change
     */
    public static function logChangeUserStatus(int $adminId, $target, string $oldStatus, string $newStatus, ?string $note = null): self
    {
        return self::create([
            'admin_id' => $adminId,
            'action_type' => self::CHANGE_USER_STATUS,
            'target_id' => $target->id,
            'target_type' => get_class($target),
            'old_values' => [
                'status' => $oldStatus,
            ],
            'new_values' => [
                'status' => $newStatus,
            ],
            'note' => $note,
        ]);
    }

    /**
     * Create a log entry for driver status change
     */
    public static function logChangeDriverStatus(int $adminId, $target, string $oldStatus, string $newStatus, ?string $note = null): self
    {
        return self::create([
            'admin_id' => $adminId,
            'action_type' => self::CHANGE_DRIVER_STATUS,
            'target_id' => $target->id,
            'target_type' => get_class($target),
            'old_values' => [
                'status' => $oldStatus,
            ],
            'new_values' => [
                'status' => $newStatus,
            ],
            'note' => $note,
        ]);
    }

    // Scopes

    /**
     * Scope to filter by action type
     */
    public function scopeByActionType($query, string $actionType)
    {
        return $query->where('action_type', $actionType);
    }

    /**
     * Scope to filter by admin
     */
    public function scopeByAdmin($query, int $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    /**
     * Scope to filter by target
     */
    public function scopeByTarget($query, $target)
    {
        return $query->where('target_id', $target->id)
                     ->where('target_type', get_class($target));
    }

    /**
     * Scope to filter by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}
