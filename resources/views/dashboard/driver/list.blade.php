@extends('layouts/contentNavbarLayout')

@section('title', __('app.drivers'))

@section('content')
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">{{ __('app.drivers') }}</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
          <li class="breadcrumb-item active">{{ __('app.drivers') }}</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Statistics -->
  <div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
              <span>{{ __('driver.stats.total') }}</span>
              <div class="d-flex align-items-end mt-2">
                <h4 class="mb-0 me-2">{{ $stats['total'] }}</h4>
              </div>
              <p class="mb-0">{{ __('driver.stats.registered') }}</p>
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-purple">
                <i class="bx bx-car bx-sm"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
              <span>{{ __('driver.stats.active') }}</span>
              <div class="d-flex align-items-end mt-2">
                <h4 class="mb-0 me-2">{{ $stats['active'] }}</h4>
              </div>
              <p class="mb-0">{{ __('driver.stats.active_label') }}</p>
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-success">
                <i class="bx bx-user-check bx-sm"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
              <span>{{ __('driver.stats.banned') }}</span>
              <div class="d-flex align-items-end mt-2">
                <h4 class="mb-0 me-2">{{ $stats['banned'] }}</h4>
              </div>
              <p class="mb-0">{{ __('driver.stats.banned_label') }}</p>
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-danger">
                <i class="bx bx-user-x bx-sm"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
              <span>{{ __('driver.stats.new') }}</span>
              <div class="d-flex align-items-end mt-2">
                <h4 class="mb-0 me-2">{{ $stats['new'] }}</h4>
              </div>
              <p class="mb-0">{{ __('driver.stats.new_label') }}</p>
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-info">
                <i class="bx bx-user-plus bx-sm"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Statistics -->

  <!-- Bootstrap Table with Header - Light -->
  <div class="card">
    <div class="card-header border-bottom">
      <x-table.filters :filters="[
        [
          'id' => 'user_status_filter',
          'name' => 'user_status_filter',
          'label' => __('driver.user_status'),
          'options' => App\Constants\UserStatus::all(true),
        ],
        [
          'id' => 'driver_status_filter',
          'name' => 'driver_status_filter',
          'label' => __('driver.driver_status'),
          'options' => App\Constants\DriverStatus::all(true),
        ],
        [
          'id' => 'federation_filter',
          'name' => 'federation_filter',
          'label' => __('driver.federation'),
          'options' => App\Models\Federation::pluck('name', 'id')->toArray(),
        ],
      ]" />
    </div>
        <div class="card-datatable table-responsive">
      <div class="dataTables_wrapper dt-bootstrap5 no-footer">
        <div class="row mx-2">
          <div class="col-md-2">
            <x-table.custom-datatable-length />
          </div>
          <div class="col-md-10">
            <div
              class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">
              <x-table.custom-datatable-search />
            </div>
          </div>
        </div>
        <x-table.datatable :columns="$columns" table-id="laravel_datatable" translation-prefix="driver" />
        <x-table.custom-datatable-pagination table-id="laravel_datatable" />
      </div>
    </div>
  </div>
  <!--/ Bootstrap Table with Header - Light -->

  <!-- Modals for Driver Actions -->
  <x-modal.confirmation
    id="user-status-activate-modal"
    title="{{ __('user.modals.activate') }}"
    action="{{ route('users.status.update') }}"
    method="POST"
    inputs='
    <input type="hidden" name="id" value="">
    <input type="hidden" name="status" value="active">
    <input type="hidden" name="type" value="driver">
  '
    theme="success"
    Optional
    confirmationTitle="{{ __('user.activate.confirmation') }}"
    confirmationText="{{ __('user.activate.notice') }}"
    checkboxLabel="{{ __('user.activate.confirm_checkbox') }}"
    submitLabel="{{ __('app.submit') }}"
    cancelLabel="{{ __('app.cancel') }}"
  />
  <x-modal.confirmation
    id="user-status-suspend-modal"
    title="{{ __('user.modals.suspend') }}"
    action="{{ route('users.status.update') }}"
    method="POST"
    inputs='
    <input type="hidden" name="id" value="">
    <input type="hidden" name="status" value="banned">
  '
    theme="danger"
    confirmationTitle="{{ __('user.suspend.confirmation') }}"
    confirmationText="{{ __('user.suspend.notice') }}"
    checkboxLabel="{{ __('user.suspend.confirm_checkbox') }}"
    submitLabel="{{ __('app.submit') }}"
    cancelLabel="{{ __('app.cancel') }}"
  />
  <x-modal.confirmation
    id="driver-status-approve-modal"
    title="{{ __('driver.modals.approve') }}"
    action="{{ route('drivers.status.update') }}"
    method="POST"
    inputs='
    <input type="hidden" name="id" value="">
    <input type="hidden" name="status" value="approved">
    <input type="hidden" name="type" value="driver">
  '
    theme="green"
    Optional
    confirmationTitle="{{ __('driver.approve.confirmation') }}"
    confirmationText="{{ __('driver.approve.notice') }}"
    checkboxLabel="{{ __('driver.approve.confirm_checkbox') }}"
    submitLabel="{{ __('app.submit') }}"
    cancelLabel="{{ __('app.cancel') }}"
  />
  <x-modal.confirmation
    id="driver-status-deny-modal"
    title="{{ __('driver.modals.suspend') }}"
    action="{{ route('drivers.status.update') }}"
    method="POST"
    inputs='
    <input type="hidden" name="id" value="">
    <input type="hidden" name="status" value="denied">
  '
    theme="warning"
    confirmationTitle="{{ __('driver.suspend.confirmation') }}"
    confirmationText="{{ __('driver.suspend.notice') }}"
    checkboxLabel="{{ __('driver.suspend.confirm_checkbox') }}"
    submitLabel="{{ __('app.submit') }}"
    cancelLabel="{{ __('app.cancel') }}"
  />
    <x-modal.form
    id="charge-wallet-modal"
    title="{{ __('app.charge_wallet') }}"
    action="{{ route('users.wallet.charge') }}"
    method="POST"
    inputs='
    <input type="hidden" name="id" value="">
    <input type="hidden" name="type" value="driver">
    <!-- Amount Input -->
    <div class="mb-4">
      <label class="form-label fw-bold" for="charge_amount">
        <i class="bx bx-money me-2"></i>{{ __("app.amount_to_charge") }}
      </label>
      <div class="input-group">
        <input type="number" name="amount" id="charge_amount" class="form-control form-control-lg" step="0.01" placeholder="0.00" required>
        <span class="input-group-text fw-bold">{{ __("app.DZD") }}</span>
      </div>
      <small class="text-muted d-block mt-2">{{ __("app.enter_amount_to_add") }}</small>
    </div>

    <!-- Current vs New Balance Side by Side -->
    <div class="row mb-4">
      <div class="col-md-6 mb-3">
        <div class="card border-info h-100">
          <div class="card-body text-center">
            <small class="text-muted d-block mb-2">
              <i class="bx bx-wallet me-1"></i>{{ __("app.current_balance") }}
            </small>
            <div class="d-flex align-items-center justify-content-center gap-1">
              <h5 class="card-title mb-0 fw-bold" id="current_balance">0.00</h5>
              <span class="small text-muted">{{ __("app.DZD") }}</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="card border-success h-100">
          <div class="card-body text-center">
            <small class="text-muted d-block mb-2">
              <i class="bx bx-check-circle me-1"></i>{{ __("app.new_balance") }}
            </small>
            <div class="d-flex align-items-center justify-content-center gap-1">
              <h5 class="card-title mb-0 fw-bold text-success" id="new_charge_balance">0.00</h5>
              <span class="small text-muted">{{ __("app.DZD") }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="text-center mt-3">
      <p class="text-muted small">{{ __("app.charge_wallet_info") }}</p>
    </div>
  '
    theme="blue"
    submitLabel="{{ __('app.submit') }}"
    cancelLabel="{{ __('app.cancel') }}"
  />
    <x-modal.form
    id="withdraw-sum-modal"
    title="{{ __('app.withdraw') }}"
    action="{{ route('users.wallet.withdraw') }}"
    method="POST"
    inputs='
    <input type="hidden" name="id" value="">
    <input type="hidden" name="type" value="driver">
    <!-- Amount Input -->
    <div class="mb-4">
      <label class="form-label fw-bold" for="withdraw_amount">
        <i class="bx bx-money me-2"></i>{{ __("app.amount_to_withdraw") }}
      </label>
      <div class="input-group">
        <input type="number" name="amount" id="withdraw_amount" class="form-control form-control-lg" step="0.01" placeholder="0.00" required>
        <span class="input-group-text fw-bold">{{ __("app.DZD") }}</span>
      </div>
      <small class="text-muted d-block mt-2">{{ __("app.enter_amount_to_withdraw") }}</small>
    </div>

    <!-- Current vs Remaining Balance Side by Side -->
    <div class="row mb-4">
      <div class="col-md-6 mb-3">
        <div class="card border-info h-100">
          <div class="card-body text-center">
            <small class="text-muted d-block mb-2">
              <i class="bx bx-wallet me-1"></i>{{ __("app.current_balance") }}
            </small>
            <div class="d-flex align-items-center justify-content-center gap-1">
              <h5 class="card-title mb-0 fw-bold" id="current_balance_withdraw">0.00</h5>
              <span class="small text-muted">{{ __("app.DZD") }}</span>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="card border-warning h-100">
          <div class="card-body text-center">
            <small class="text-muted d-block mb-2">
              <i class="bx bx-minus-circle me-1"></i>{{ __("app.remaining_balance") }}
            </small>
            <div class="d-flex align-items-center justify-content-center gap-1">
              <h5 class="card-title mb-0 fw-bold text-warning" id="new_withdraw_balance">0.00</h5>
              <span class="small text-muted">{{ __("app.DZD") }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="text-center mt-3">
      <p class="text-muted small">{{ __("app.withdraw_wallet_info") }}</p>
    </div>
  '
    theme="teal"
    submitLabel="{{ __('app.submit') }}"
    cancelLabel="{{ __('app.cancel') }}"
  />
    <x-modal.form
    id="purchase-subscription-modal"
    title="{{ __('app.purchase_subscription') }}"
    action="{{ route('drivers.subscription.purchase') }}"
    method="POST"
    inputs='
    <input type="hidden" name="id" value="">
    
    <!-- Subscription Period Selection -->
    <div class="mb-4">
      <label class="form-label fw-bold" for="subscription_months">
        <i class="bx bx-time me-2"></i>{{ __("app.subscription_period") }}
      </label>
      <div class="input-group input-group-lg">
        <button class="btn btn-outline-secondary" type="button" onclick="decrementMonths()">
          <i class="bx bx-minus"></i>
        </button>
        <input type="number" name="months" id="subscription_months" class="form-control text-center fw-bold" min="1" value="1" required>
        <button class="btn btn-outline-secondary" type="button" onclick="incrementMonths()">
          <i class="bx bx-plus"></i>
        </button>
        <span class="input-group-text fw-bold">{{ __("app.months") }}</span>
      </div>
      <small class="text-muted d-block mt-2">{{ __("app.select_subscription_months") }}</small>
    </div>

    <!-- Pricing Summary -->
    <div class="row mb-4">
      <div class="col-md-6 mb-3">
        <div class="card border-primary h-100">
          <div class="card-body text-center">
            <small class="text-muted d-block mb-2">{{ __("app.monthly_fee") }}</small>
            <div class="d-flex align-items-center justify-content-center gap-1">
              <h5 class="card-title mb-0 fw-bold" id="monthly_fee">0.00</h5>
              <span class="small">{{ __("app.DZD") }}</span>
            </div>
            <small class="text-muted">{{ __("app.per_month") }}</small>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="card border-success h-100">
          <div class="card-body text-center">
            <small class="text-muted d-block mb-2">{{ __("app.total_amount") }}</small>
            <div class="d-flex align-items-center justify-content-center gap-1">
              <h5 class="card-title mb-0 fw-bold text-success" id="total_subscription_fee">0.00</h5>
              <span class="small">{{ __("app.DZD") }}</span>
            </div>
            <small class="text-muted">{{ __("app.for_selected_period") }}</small>
          </div>
        </div>
      </div>
    </div>

    <!-- Current vs New Subscription End Dates Side by Side -->
    <div class="row mb-4">
      <div class="col-md-6 mb-3">
        <div class="card border-info h-100">
          <div class="card-body text-center">
            <small class="text-muted d-block mb-2">
              <i class="bx bx-calendar me-1"></i>{{ __("app.current_subscription_end") }}
            </small>
            <div class="d-flex align-items-center justify-content-center gap-1">
              <h6 class="card-title mb-0 fw-bold" id="current_subscription_end">-</h6>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-3">
        <div class="card border-success h-100">
          <div class="card-body text-center">
            <small class="text-muted d-block mb-2">
              <i class="bx bx-calendar-check me-1"></i>{{ __("app.new_subscription_end") }}
            </small>
            <div class="d-flex align-items-center justify-content-center gap-1">
              <h6 class="card-title mb-0 fw-bold text-success" id="new_subscription_end">-</h6>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="text-center">
      <p class="text-muted small">
        <i class="bx bx-info-circle me-1"></i>
        {{ __("app.subscription_info_text") }}
      </p>
    </div>
  '
    theme="purple"
    submitLabel="{{ __('app.confirm_purchase') }}"
    cancelLabel="{{ __('app.cancel') }}"
  />

@endsection
@section('page-script')
  <script>
    // Helper functions for subscription months
    function incrementMonths() {
      const input = $('#subscription_months');
      input.val(parseInt(input.val()) + 1);
      input.trigger('input');
    }

    function decrementMonths() {
      const input = $('#subscription_months');
      const current = parseInt(input.val());
      if (current > 1) {
        input.val(current - 1);
        input.trigger('input');
      }
    }

    $(document).ready(function() {
      let filters = {
        federation_filter: $('#federation_filter').val(),
        user_status_filter: $('#user_status_filter').val(),
        driver_status_filter: $('#driver_status_filter').val()
      };

      let table = initializeDataTable(
        "{{ route('drivers.index') }}",
        @json($columns),
        filters
      );


      // Reload DataTable when the filter value changes
      $('.filter-input').on('change', function() {
        let filterName = $(this).attr('id');
        filters[filterName] = $(this).val();
        table.ajax.reload();
      });

      // Driver user status modal handlers
      $(document).on('click', '[data-bs-target="#user-status-activate-modal"]', function() {
        const userId = $(this).data('id');
        $('#driver-user-activate-modal').find('input[name="id"]').val(userId);
      });

      $(document).on('click', '[data-bs-target="#user-status-suspend-modal"]', function() {
        const userId = $(this).data('id');
        $('#driver-user-suspend-modal').find('input[name="id"]').val(userId);
      });

      // Driver status modal handlers
      $(document).on('click', '[data-bs-target="#driver-status-approve-modal"]', function() {
        const driverId = $(this).data('id');
        $('#driver-status-approve-modal').find('input[name="id"]').val(driverId);
      });

      $(document).on('click', '[data-bs-target="#driver-status-deny-modal"]', function() {
        const driverId = $(this).data('id');
        $('#driver-status-deny-modal').find('input[name="id"]').val(driverId);
      });

      // Wallet modal handlers
      $(document).on('click', '[data-bs-target="#charge-wallet-modal"]', function() {
        const userId = $(this).data('id');
        const walletBalance = parseFloat($(this).data('wallet-balance')) || 0;
        
        // Store wallet data in modal
        $('#charge-wallet-modal').data('walletBalance', walletBalance);
        $('#charge-wallet-modal').find('input[name="id"]').val(userId);
        $('#current_balance').text(walletBalance.toFixed(2));
        $('#new_charge_balance').text(walletBalance.toFixed(2));
      });

      $(document).on('click', '[data-bs-target="#withdraw-sum-modal"]', function() {
        const userId = $(this).data('id');
        const walletBalance = parseFloat($(this).data('wallet-balance')) || 0;
        
        // Store wallet data in modal
        $('#withdraw-sum-modal').data('walletBalance', walletBalance);
        $('#withdraw-sum-modal').find('input[name="id"]').val(userId);
        $('#current_balance_withdraw').text(walletBalance.toFixed(2));
        $('#new_withdraw_balance').text(walletBalance.toFixed(2));
      });

      // Subscription modal handler
      $(document).on('click', '[data-bs-target="#purchase-subscription-modal"]', function() {
        const userId = $(this).data('id');
        const subscriptionEndDate = $(this).data('subscription-end-date') || null;
        const monthlyFee = $(this).data('monthly-fee') || 0;
        
        // Initialize subscription modal
        $('#purchase-subscription-modal').find('input[name="id"]').val(userId);
        $('#subscription_months').val(1);
        
        // Store data in modal for calculations
        $('#purchase-subscription-modal').data('subscriptionEndDate', subscriptionEndDate);
        $('#monthly_fee').data('monthlyFee', monthlyFee);
        
        // Display current subscription end date
        const endDateDisplay = subscriptionEndDate 
          ? new Date(subscriptionEndDate).toLocaleDateString('{{ app()->getLocale() === "ar" ? "ar-DZ" : "en-US" }}') 
          : '-';
        $('#current_subscription_end').text(endDateDisplay);
        $('#monthly_fee').text(parseFloat(monthlyFee).toFixed(2));
        
        // Update calculations
        updateSubscriptionCalculations();
      });

      // Dynamic balance calculation for charge wallet
      $(document).on('input', '#charge_amount', function() {
        const chargeAmount = parseFloat($(this).val()) || 0;
        const currentBalance = $('#charge-wallet-modal').data('walletBalance') || 0;
        const newBalance = currentBalance + chargeAmount;
        $('#new_charge_balance').text(newBalance.toFixed(2));
      });

      // Dynamic balance calculation for withdraw
      $(document).on('input', '#withdraw_amount', function() {
        const withdrawAmount = parseFloat($(this).val()) || 0;
        const currentBalance = $('#withdraw-sum-modal').data('walletBalance') || 0;
        const newBalance = Math.max(0, currentBalance - withdrawAmount);
        $('#new_withdraw_balance').text(newBalance.toFixed(2));
      });

      // Dynamic calculations for subscription
      $(document).on('input', '#subscription_months', function() {
        updateSubscriptionCalculations();
      });

      function updateSubscriptionCalculations() {
        const months = parseInt($('#subscription_months').val()) || 1;
        const monthlyFee = parseFloat($('#monthly_fee').data('monthlyFee')) || 0;
        
        // Calculate total fee
        const totalFee = months * monthlyFee;
        $('#total_subscription_fee').text(totalFee.toFixed(2));

        // Calculate new subscription end date
        const currentEndDateStr = $('#purchase-subscription-modal').data('subscriptionEndDate');
        let startDate = new Date();
        
        if (currentEndDateStr) {
          startDate = new Date(currentEndDateStr);
        }
        
        const newEndDate = new Date(startDate);
        newEndDate.setMonth(newEndDate.getMonth() + months);
        
        const locale = '{{ app()->getLocale() === "ar" ? "ar-DZ" : "en-US" }}';
        const formattedDate = newEndDate.toLocaleDateString(locale);
        $('#new_subscription_end').text(formattedDate);
      }

    });
</script>
@endsection