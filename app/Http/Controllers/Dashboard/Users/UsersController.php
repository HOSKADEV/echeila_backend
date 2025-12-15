<?php

namespace App\Http\Controllers\Dashboard\Users;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\File;
use App\Constants\UserType;
use App\Models\Transaction;
use App\Support\Enum\Roles;
use Illuminate\Http\Request;
use App\Traits\LogsAdminActions;
use App\Datatables\UserDatatable;
use App\Support\Enum\Permissions;
use App\Constants\TransactionType;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Constants\NotificationMessages;
use App\Notifications\NewMessageNotification;

class UsersController extends Controller
{
    use LogsAdminActions;

    public function index(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::MANAGE_USERS)) {
            return redirect()->route('unauthorized');
        }

        $users = new UserDatatable;
        if ($request->wantsJson()) {
            return $users->datatables($request);
        }

        return view('dashboard.user.list')->with([
            'columns' => $users::columns(),
        ]);
    }

    public function create()
    {
        if (! auth()->user()->hasPermissionTo(Permissions::MANAGE_USERS)) {
            return redirect()->route('unauthorized');
        }

        return view('dashboard.user.create-edit')->with(['edit' => false]);
    }

    public function edit($id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::MANAGE_USERS)) {
            return redirect()->route('unauthorized');
        }

        return view('dashboard.user.create-edit')->with(['edit' => true, 'user' => User::findOrFail($id)]);
    }

    public function store(Request $request)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::MANAGE_USERS)) {
            return redirect()->route('unauthorized');
        }
        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            // 'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|regex:/^(\+?\d{1,3})?(\d{9})$/|unique:users,phone',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
            'password' => 'required|string|min:8',
            'type' => 'required|string|in:'.implode(',', UserType::all()),
            'role' => 'nullable|string|in:'.implode(',', Roles::all()),
        ]);

        try {
            DB::beginTransaction();
            if ($request->hasFile('avatar')) {
                $data['avatar'] = storeWebP($request->file('avatar'), 'uploads/users/avatars');
            } else {
                $file = new File(public_path('assets/img/avatars/1.png'));
                $data['avatar'] = storeWebP($file, 'uploads/users/avatars');
            }
            $data['password'] = Hash::make($data['password']);
            $user = User::create($data);

            if ($request->role) {
                $user->assignRole($request->role);
            }

            DB::commit();

            return redirect()->route('users.index')->with('success', __('app.created_successfully', ['name' => __('app.user')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::MANAGE_USERS)) {
            return redirect()->route('unauthorized');
        }
        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone' => 'required|regex:/^(\+?\d{1,3})?(\d{9})$/',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
            'type' => 'required|string|in:'.implode(',', UserType::all()),
            'role' => 'nullable|string|in:'.implode(',', Roles::all()),
        ]);

        try {
            DB::beginTransaction();
            $user = User::findOrFail($id);
            if ($request->hasFile('avatar')) {
                $data['avatar'] = storeWebP($request->file('avatar'), 'uploads/users/avatars');
            }
            $user->update($data);

            if ($request->role) {
                $user->syncRoles([$request->role]);
            }

            DB::commit();

            return redirect()->route('users.index')->with('success', __('app.updated_successfully', ['name' => __('app.user')]));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        if (! auth()->user()->hasPermissionTo(Permissions::MANAGE_USERS)) {
            return redirect()->route('unauthorized');
        }

        try {
            $user = User::findOrFail($id);
            $user->syncRoles([]);
            $user->delete();

            return redirect()->route('users.index')->with('success', __('app.deleted_successfully', ['name' => __('app.user')]));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function updateStatus(Request $request)
    {

        $permission = match ($request->input('type')) {
            'driver' => Permissions::DRIVER_CHANGE_USER_STATUS,
            'passenger' => Permissions::PASSENGER_CHANGE_USER_STATUS,
            'federator' => Permissions::FEDERATION_CHANGE_USER_STATUS,
        };
        if (! auth()->user()->hasPermissionTo($permission)) {
            return redirect()->route('unauthorized');
        }

        $data = $request->validate([
            'id' => 'required|exists:users,id',
            'status' => 'required|string|in:active,banned',
            'confirmed' => 'required|accepted',
        ]);

        try {
            DB::beginTransaction();
            $user = User::findOrFail($data['id']);
            $oldStatus = $user->status;

            $user->status = $data['status'];
            $user->save();

            // Log admin action
            $target = $user->driver ?? $user->passenger;
            if ($target) {
                $this->logChangeUserStatus($target, $oldStatus, $data['status'], $request->input('note'));
            }

            // Send notification
            $notificationKey = $data['status'] === 'active'
              ? NotificationMessages::USER_ACTIVATED
              : NotificationMessages::USER_BANNED;

            $user->notify(new NewMessageNotification(
                key: $notificationKey,
                data: ['status' => $data['status']]
            ));

            DB::commit();

            $statusMessage = $data['status'] === 'active'
              ? __('user.activated_successfully')
              : __('user.suspended_successfully');

            return redirect()->back()->with('success', $statusMessage);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function chargeWallet(Request $request)
    {
        $permission = match ($request->input('type')) {
            'driver' => Permissions::DRIVER_CHARGE_WALLET,
            'passenger' => Permissions::PASSENGER_CHARGE_WALLET,
        };
        if (! auth()->user()->hasPermissionTo($permission)) {
            return redirect()->route('unauthorized');
        }

        $data = $request->validate([
            'id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
        ]);

        try {

            $min_charge_amount = Setting::getValue('min_charge_amount', 0);

            if ($data['amount'] <= $min_charge_amount) {
                throw new \Exception(__('app.min_charge_amount', ['amount' => $min_charge_amount]));
            }

            DB::beginTransaction();
            $user = User::findOrFail($data['id']);
            $wallet = $user->wallet;

            // Get target (driver or passenger)
            $target = $user->driver ?? $user->passenger;

            // Log the action BEFORE incrementing
            if ($target) {
                $this->logWalletCharge($target, $data['amount'], $request->input('note'));
            }

            $wallet->increment('balance', $data['amount']);

            $transaction = Transaction::create([
                'wallet_id' => $wallet->id,
                'type' => TransactionType::DEPOSIT,
                'amount' => abs($data['amount']),
            ]);

            $user->notify(new NewMessageNotification(
                key: NotificationMessages::TRANSACTION_DEPOSIT,
                data: ['amount' => $transaction->amount, 'balance' => $wallet->balance]
            ));

            DB::commit();

            return redirect()->back()->with('success', __('app.wallet_charged_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function withdrawSum(Request $request)
    {
        $permission = match ($request->input('type')) {
            'driver' => Permissions::DRIVER_WITHDRAW_SUM,
            'passenger' => Permissions::PASSENGER_WITHDRAW_SUM,
        };
        if (! auth()->user()->hasPermissionTo($permission)) {
            return redirect()->route('unauthorized');
        }

        $data = $request->validate([
            'id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
        ]);

        try {

            $max_withdraw_amount = Setting::getValue('max_withdraw_amount', 0);

            if ($data['amount'] > $max_withdraw_amount) {
                throw new \Exception(__('app.max_withdraw_amount', ['amount' => $max_withdraw_amount]));
            }

            DB::beginTransaction();
            $user = User::findOrFail($data['id']);
            $wallet = $user->wallet;

            if ($wallet->balance < $data['amount']) {
                throw new \Exception('Insufficient balance');
            }

            // Get target (driver or passenger)
            $target = $user->driver ?? $user->passenger;

            // Log the action BEFORE decrementing
            if ($target) {
                $this->logWithdrawSum($target, $data['amount'], $request->input('note'));
            }

            $wallet->decrement('balance', $data['amount']);

            $transaction = Transaction::create([
                'wallet_id' => $wallet->id,
                'type' => TransactionType::WITHDRAW,
                'amount' => -abs($data['amount']),
            ]);

            $user->notify(new NewMessageNotification(
                key: NotificationMessages::TRANSACTION_WITHDRAW,
                data: ['amount' => $transaction->amount, 'balance' => $wallet->balance]
            ));

            DB::commit();

            return redirect()->back()->with('success', __('app.withdrawal_completed_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
