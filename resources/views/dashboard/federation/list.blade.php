@extends('layouts/contentNavbarLayout')

@section('title', __('app.federations'))

@section('content')
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">{{ __('app.federations') }}</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
          <li class="breadcrumb-item active">{{ __('app.federations') }}</li>
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
              <span>{{ __('federation.stats.total') }}</span>
              <div class="d-flex align-items-end mt-2">
                <h4 class="mb-0 me-2">{{ $stats['total'] }}</h4>
              </div>
              <p class="mb-0">{{ __('federation.stats.registered') }}</p>
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-purple">
                <i class="bx bx-building bx-sm"></i>
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
              <span>{{ __('federation.stats.active') }}</span>
              <div class="d-flex align-items-end mt-2">
                <h4 class="mb-0 me-2">{{ $stats['active'] }}</h4>
              </div>
              <p class="mb-0">{{ __('federation.stats.active_label') }}</p>
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-success">
                <i class="bx bx-check-circle bx-sm"></i>
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
              <span>{{ __('federation.stats.banned') }}</span>
              <div class="d-flex align-items-end mt-2">
                <h4 class="mb-0 me-2">{{ $stats['banned'] }}</h4>
              </div>
              <p class="mb-0">{{ __('federation.stats.banned_label') }}</p>
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-danger">
                <i class="bx bx-x-circle bx-sm"></i>
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
              <span>{{ __('federation.stats.new') }}</span>
              <div class="d-flex align-items-end mt-2">
                <h4 class="mb-0 me-2">{{ $stats['new'] }}</h4>
              </div>
              <p class="mb-0">{{ __('federation.stats.new_label') }}</p>
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-info">
                <i class="bx bx-plus-circle bx-sm"></i>
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
          'label' => __('federation.user_status'),
          'options' => App\Constants\UserStatus::all(true),
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
              <div class="dt-buttons btn-group flex-wrap">
                <!-- Custom buttons can be added here if needed -->
                @permission(\App\Support\Enum\Permissions::FEDERATION_CREATE)
                <a href="{{ route('federations.create') }}" class="text-white text-decoration-none">
                  <button type="button" class="btn btn-primary">
                    <span class="tf-icons bx bx-plus"></span> @lang('federation.add-new')
                  </button>
                </a>
                @endpermission
              </div>
            </div>
          </div>
        </div>
        <x-table.datatable :columns="$columns" table-id="laravel_datatable" translation-prefix="federation" />
        <x-table.custom-datatable-pagination table-id="laravel_datatable" />
      </div>
    </div>
  </div>
  <!--/ Bootstrap Table with Header - Light -->

      <!-- Modals for User Actions -->
  <x-modal.confirmation
    id="user-status-activate-modal"
    title="{{ __('user.modals.activate') }}"
    action="{{ route('users.status.update') }}"
    method="POST"
    inputs='
    <input type="hidden" name="id" value="">
    <input type="hidden" name="status" value="active">
    <input type="hidden" name="type" value="federation">
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
    <input type="hidden" name="type" value="federation">
  '
    theme="danger"
    {{--    Optional --}}
    confirmationTitle="{{ __('user.suspend.confirmation') }}"
    confirmationText="{{ __('user.suspend.notice') }}"
    checkboxLabel="{{ __('user.suspend.confirm_checkbox') }}"
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
    <input type="hidden" name="type" value="federation">
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
    <input type="hidden" name="type" value="federation">
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

@endsection

@section('page-script')
  <script>
    $(document).ready(function() {
      let filters = {
        user_status_filter: $('#user_status_filter').val()
      };

      let table = initializeDataTable(
        "{{ route('federations.index') }}",
        @json($columns),
        filters
      );


      // Reload DataTable when the filter value changes
      $('.filter-input').on('change', function() {
        let filterName = $(this).attr('id');
        filters[filterName] = $(this).val();
        table.ajax.reload();
      });

            $(document).on('click', '[data-bs-target="#user-status-activate-modal"]', function() {
        const userId = $(this).data('id');
        $('#user-status-activate-modal').find('input[name="id"]').val(userId);
      });

      $(document).on('click', '[data-bs-target="#user-status-suspend-modal"]', function() {
        const userId = $(this).data('id');
        $('#user-status-suspend-modal').find('input[name="id"]').val(userId);
      });

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


    });
</script>
@endsection