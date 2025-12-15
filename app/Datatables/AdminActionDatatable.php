<?php

namespace App\Datatables;

use Exception;
use App\Models\Driver;
use App\Models\Passenger;
use App\Models\AdminAction;
use Illuminate\Support\Facades\Log;
use App\Traits\DataTableActionsTrait;

class AdminActionDatatable
{
    use DataTableActionsTrait;

    public static function columns(): array
    {
        return [
            'id',
            'admin',
            'action_type',
            'target',
            'amount',
            //'note',
            'created_at',
        ];
    }

    public function datatables($request)
    {
        try {
            return datatables($this->query($request))
                ->addColumn('id', function ($model) {
                    return '#' . $model->id;
                })
                ->addColumn('admin', function ($model) {
                    return $this->thumbnailTitleMeta(
                        $model->admin->avatar_url,
                        $model->admin->fullname,
                        $model->admin->email
                    );
                })
                ->addColumn('action_type', function ($model) {
                    $colors = [
                        AdminAction::WALLET_CHARGE => 'success',
                        AdminAction::WITHDRAW_SUM => 'warning',
                        AdminAction::PURCHASE_SUBSCRIPTION => 'info',
                        AdminAction::CHANGE_USER_STATUS => 'primary',
                        AdminAction::CHANGE_DRIVER_STATUS => 'purple',
                    ];
                    
                    $icons = [
                        AdminAction::WALLET_CHARGE => 'bx bx-wallet',
                        AdminAction::WITHDRAW_SUM => 'bx bx-money',
                        AdminAction::PURCHASE_SUBSCRIPTION => 'bx bx-calendar',
                        AdminAction::CHANGE_USER_STATUS => 'bx bx-user-check',
                        AdminAction::CHANGE_DRIVER_STATUS => 'bx bx-badge-check',
                    ];

                    return $this->badge(
                        $model->action_type_label,
                        $colors[$model->action_type] ?? 'secondary',
                        $icons[$model->action_type] ?? 'bx bx-info-circle'
                    );
                })
                ->addColumn('target', function ($model) {
                    $targetName = '';
                    $targetMeta = '';
                    $targetAvatar = asset('assets/img/avatars/1.png');
                    
                    if ($model->target) {
                        $targetName = $model->target->fullname ?? 'N/A';
                        $targetMeta = $model->target->user->phone ?? '';
                        $targetAvatar = $model->target->avatar_url ?? asset('assets/img/avatars/1.png');
                        $targetUrl = match($model->target_type) {
                            Passenger::class => route('passengers.show', $model->target->user_id),
                            Driver::class => route('drivers.show', $model->target->user_id),
                            default => '#',
                        };
                    }

                    return $this->thumbnailTitleMeta($targetAvatar, $targetName, $targetMeta, $targetUrl);
                })
                ->addColumn('amount', function ($model) {
                    // For wallet charge and withdraw actions, show balance change
                    if (in_array($model->action_type, [AdminAction::WALLET_CHARGE, AdminAction::WITHDRAW_SUM])) {
                        $oldBalance = $model->old_values['balance'] ?? null;
                        $newBalance = $model->new_values['balance'] ?? null;
                        
                        if ($oldBalance !== null && $newBalance !== null) {
                            return $this->money($oldBalance) . ' <i class="bx bx-right-arrow-alt"></i> ' . $this->money($newBalance);
                        }
                    }
                    
                    // For status changes, show status change
                    if (in_array($model->action_type, [AdminAction::CHANGE_USER_STATUS, AdminAction::CHANGE_DRIVER_STATUS])) {
                        $oldStatus = $model->old_values['status'] ?? null;
                        $newStatus = $model->new_values['status'] ?? null;
                        
                        if ($oldStatus && $newStatus) {
                            return '<span class="badge bg-label-secondary">' . e($oldStatus) . '</span> <i class="bx bx-right-arrow-alt"></i> <span class="badge bg-label-primary">' . e($newStatus) . '</span>';
                        }
                    }
                    
                    // For subscription purchases, show subscription info
                    if ($model->action_type == AdminAction::PURCHASE_SUBSCRIPTION) {

                        $startDate = $model->old_values['end_date'] ?? 'none';
                        $endDate = $model->new_values['end_date'] ?? null;
                        
                        if ($startDate && $endDate) {
                            return '<span class="badge bg-label-secondary">' . e($startDate) . '</span> <i class="bx bx-right-arrow-alt"></i> <span class="badge bg-label-success">' . $endDate . '</span>';
                        }
                    }
                    
                    // Fallback to amount if available
                    if ($model->amount) {
                        return $this->money($model->amount);
                    }
                    
                    return '-';
                })
               /*  ->addColumn('note', function ($model) {
                    if ($model->note) {
                        return '<span class="text-truncate" style="max-width: 200px; display: inline-block;" title="' . e($model->note) . '">' . e($model->note) . '</span>';
                    }
                    return '-';
                }) */
                ->addColumn('created_at', function ($model) {
                    return $this->datetime($model->created_at);
                })
                ->rawColumns(self::columns())
                ->make(true);
        } catch (Exception $e) {
            Log::error(get_class($this) . ' Error ' . $e->getMessage());
        }
    }

    public function query($request)
    {
        $query = AdminAction::with(['admin', 'target']);

        // Filter by action type
        if ($request->action_type_filter) {
            $query->where('action_type', $request->action_type_filter);
        }

        // Filter by admin
        if ($request->admin_filter) {
            $query->where('admin_id', $request->admin_filter);
        }

        // Filter by date range
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query->latest()->get();
    }
}
