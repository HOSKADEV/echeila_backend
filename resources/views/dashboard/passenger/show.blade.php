@extends('layouts/contentNavbarLayout')

@section('title', __('passenger.show'))

@section('content')
  <div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">{{ __('passenger.profile') }}</h4>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('passengers.index') }}">{{ __('app.passengers') }}</a></li>
            <li class="breadcrumb-item active">{{ $passenger->fullname }}</li>
          </ol>
        </nav>
      </div>
      <div class="d-flex gap-2">
        @can(\App\Support\Enum\Permissions::PASSENGER_CHANGE_USER_STATUS)
          @if($passenger->user->status === \App\Constants\UserStatus::BANNED)
            <button type="button" class="btn btn-label-success" data-bs-toggle="modal" data-bs-target="#user-status-activate-modal" data-id="{{ $passenger->user->id }}">
              <i class="bx bx-lock-open me-1"></i>{{ __('app.activate') }}
            </button>
          @elseif($passenger->user->status === \App\Constants\UserStatus::ACTIVE)
            <button type="button" class="btn btn-label-danger" data-bs-toggle="modal" data-bs-target="#user-status-suspend-modal" data-id="{{ $passenger->user->id }}">
              <i class="bx bx-lock me-1"></i>{{ __('app.suspend') }}
            </button>
          @endif
        @endcan
        @can(\App\Support\Enum\Permissions::PASSENGER_CHARGE_WALLET)
          <button type="button" class="btn btn-label-blue" data-bs-toggle="modal" data-bs-target="#charge-wallet-modal" data-id="{{ $passenger->user->id }}" data-wallet-balance="{{ $passenger->user->wallet?->balance ?? 0 }}">
            <i class="bx bx-wallet me-1"></i>{{ __('app.charge_wallet') }}
          </button>
        @endcan
        @can(\App\Support\Enum\Permissions::PASSENGER_WITHDRAW_SUM)
          <button type="button" class="btn btn-label-teal" data-bs-toggle="modal" data-bs-target="#withdraw-sum-modal" data-id="{{ $passenger->user->id }}" data-wallet-balance="{{ $passenger->user->wallet?->balance ?? 0 }}">
            <i class="bx bx-money me-1"></i>{{ __('app.withdraw') }}
          </button>
        @endcan
        <a href="{{ url()->previous() }}" class="btn btn-label-secondary">
          <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
        </a>
      </div>
    </div>

    <div class="row">
      <!-- Left Column - Profile Card -->
      <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <!-- Avatar Section -->
            <div class="mb-4">
              <div class="position-relative d-inline-block">
                <img class="img-fluid rounded-circle shadow-sm" 
                     src="{{ $passenger->avatar_url }}" 
                     height="120" 
                     width="120" 
                     alt="User avatar"
                     style="object-fit: cover; border: 4px solid #fff;" />
                @if($passenger->user->status === \App\Constants\UserStatus::ACTIVE)
                  <span class="badge bg-success rounded-pill position-absolute" 
                        style="bottom: 5px; right: 5px; width: 20px; height: 20px; padding: 0; border: 3px solid #fff;">
                  </span>
                @endif
              </div>
              <div class="d-flex align-items-center justify-content-center gap-1">
                <h5 class="mb-1 mt-3">{{ $passenger->fullname }}</h5>
              </div>
              <p class="text-primary mb-1">{{ '@' . $passenger->user->username }}</p>
              <p class="text-muted small mb-0">
                <i class="bx bx-phone me-1"></i>{{ $passenger->user->phone }}
              </p>
            </div>

            <!-- Quick Stats -->
                          <div class="row g-3 mb-4">
              <div class="col-6">
                <div class="border rounded p-3 h-100">
                  <div class="d-flex align-items-center justify-content-center mb-2">
                    <div class="avatar avatar-sm bg-label-primary rounded me-2 d-flex align-items-center justify-content-center">
                      <i class="bx bx-trip"></i>
                    </div>
                    <h4 class="mb-0">{{ $stats['trips_count'] }}</h4>
                  </div>
                  <small class="text-muted">{{ __('passenger.trips') }}</small>
                </div>
              </div>
              <div class="col-6">
                <div class="border rounded p-3 h-100">
                  <div class="d-flex align-items-center justify-content-center mb-2">
                    <div class="avatar avatar-sm bg-label-warning rounded me-2 d-flex align-items-center justify-content-center">
                      <i class="bx bx-star"></i>
                    </div>
                    <h4 class="mb-0">{{ number_format($stats['avg_rating'], 1) }}</h4>
                  </div>
                  <small class="text-muted">{{ __('passenger.avg_rating') }}</small>
                </div>
              </div>
            </div>

            <hr class="my-4">

            <!-- Personal Information -->
            <div class="text-start">
              <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                {{ __('passenger.personal_info') }}
              </h6>
              <ul class="list-unstyled mb-0">
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-user text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('passenger.first_name') }}</small>
                    <span class="fw-medium">{{ $passenger->first_name ?? 'N/A' }}</span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-user text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('passenger.last_name') }}</small>
                    <span class="fw-medium">{{ $passenger->last_name ?? 'N/A' }}</span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-check-circle text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('user.status') }}</small>
                    @if($passenger->user->status === \App\Constants\UserStatus::ACTIVE)
                      <span class="badge bg-label-success">{{ __('user.statuses.' . \App\Constants\UserStatus::ACTIVE) }}</span>
                    @elseif($passenger->user->status === \App\Constants\UserStatus::BANNED)
                      <span class="badge bg-label-danger">{{ __('user.statuses.' . \App\Constants\UserStatus::BANNED) }}</span>
                    @else
                      <span class="badge bg-label-secondary">{{ __('user.statuses.' . $passenger->user->status) }}</span>
                    @endif
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-phone text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('passenger.phone') }}</small>
                    <span class="fw-medium">{{ $passenger->phone }}</span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-calendar text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('passenger.birth_date') }}</small>
                    <span class="fw-medium">{{ $passenger->birth_date ? $passenger->birth_date->format('Y-m-d') : 'N/A' }}</span>
                  </div>
                </li>
                <li class="mb-0 d-flex align-items-center">
                  <i class="bx bx-time-five text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('passenger.joined_date') }}</small>
                    <span class="fw-medium">{{ $passenger->user->created_at->format('Y-m-d') }}</span>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column - Statistics & Data -->
      <div class="col-xl-8 col-lg-7">
        <!-- Wallet Card -->
        <div class="card shadow-sm mb-4">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div class="d-flex align-items-center mb-2">
                  <i class="bx bx-wallet text-success me-2" style="font-size: 1.5rem;"></i>
                  <h6 class="mb-0">{{ __('passenger.wallet') }}</h6>
                </div>
                <h3 class="mb-0 text-success fw-bold">{{ number_format($passenger->user->wallet->balance ?? 0, 2) }} {{ __('app.DZD') }}</h3>
                <small class="text-muted">{{ __('passenger.wallet_balance') }}</small>
              </div>
              <div class="avatar avatar-lg bg-label-success rounded">
                <div class="avatar-initial bg-label-success rounded">
                  <i class="bx bx-wallet" style="font-size: 2rem;"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Statistics Grid -->
        <div class="row g-3 mb-4">
          <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <div class="avatar me-2">
                    <div class="avatar-initial bg-label-primary rounded">
                      <i class="bx bx-message-square-detail"></i>
                    </div>
                  </div>
                  <h5 class="mb-0">{{ $stats['reviews_count'] }}</h5>
                </div>
                <small class="text-muted">{{ __('passenger.total_reviews') }}</small>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <div class="avatar me-2">
                    <div class="avatar-initial bg-label-warning rounded">
                      <i class="bx bx-package"></i>
                    </div>
                  </div>
                  <h5 class="mb-0">{{ $stats['cargos_count'] }}</h5>
                </div>
                <small class="text-muted">{{ __('passenger.total_cargos') }}</small>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <div class="avatar me-2">
                    <div class="avatar-initial bg-label-danger rounded">
                      <i class="bx bx-search"></i>
                    </div>
                  </div>
                  <h5 class="mb-0">{{ $stats['lost_and_founds_count'] }}</h5>
                </div>
                <small class="text-muted">{{ __('passenger.total_lost_and_founds') }}</small>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <div class="avatar me-2">
                    <div class="avatar-initial bg-label-success rounded">
                      <i class="bx bx-money"></i>
                    </div>
                  </div>
                  <h5 class="mb-0 text-truncate" title="{{ number_format($stats['total_spent'], 2) }} {{ __('app.DZD') }}">
                    {{ number_format($stats['total_spent'], 0) }} {{ __('app.DZD') }}
                  </h5>
                </div>
                <small class="text-muted">{{ __('passenger.total_amount_spent') }}</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabs for organized content -->
        <ul class="nav nav-pills mb-3" role="tablist">
          <li class="nav-item">
            <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#transactions" role="tab">
              <i class="bx bx-transfer me-1"></i>{{ __('passenger.recent_transactions') }}
            </button>
          </li>
          <li class="nav-item">
            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#trips" role="tab">
              <i class="bx bx-trip me-1"></i>{{ __('passenger.recent_trips') }}
            </button>
          </li>
          <li class="nav-item">
            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews" role="tab">
              <i class="bx bx-star me-1"></i>{{ __('passenger.reviews') }}
            </button>
          </li>
        </ul>

        <div class="tab-content">
          <!-- Transactions Tab -->
          <div class="tab-pane fade show active" id="transactions" role="tabpanel">
            <div class="card shadow-sm">
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead class="table-light">
                    <tr>
                      <th>{{ __('transaction.amount') }}</th>
                      <th>{{ __('transaction.type') }}</th>
                      <th>{{ __('transaction.date') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($transactions as $transaction)
                      <tr>
                        <td>
                          <span class="fw-bold {{ $transaction->amount > 0 ? 'text-success' : 'text-danger' }}">
                            {{ number_format($transaction->amount, 2) }} @lang('app.DZD')
                          </span>
                        </td>
                        <td>
                            <span class="{{ 'badge bg-label-' . \App\Constants\TransactionType::get_color($transaction->type)}}">
                              {{ \App\Constants\TransactionType::get_name($transaction->type) }}
                            </span>
                        </td>
                        <td>
                          <small class="text-muted">{{ $transaction->created_at->format('Y-m-d H:i') }}</small>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="3" class="text-center py-5">
                          <i class="bx bx-info-circle text-muted mb-2" style="font-size: 2rem;"></i>
                          <p class="text-muted mb-0">{{ __('app.no_data_available') }}</p>
                        </td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
              @if($transactions->hasPages())
                <div class="card-footer">
                  {{ $transactions->links() }}
                </div>
              @endif
            </div>
          </div>

          <!-- Trips Tab -->
          <div class="tab-pane fade" id="trips" role="tabpanel">
            <div class="card shadow-sm">
              <div class="table-responsive">
                <table class="table table-hover mb-0">
                  <thead class="table-light">
                    <tr>
                      <th>{{ __('trip.id') }}</th>
                      <th>{{ __('trip.driver') }}</th>
                      <th>{{ __('trip.type') }}</th>
                      <th>{{ __('trip.date') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($recentTrips as $trip)
                      <tr>
                        <td>
                          <a href="{{ route('trips.show', $trip->id) }}" class="text-decoration-none">
                          <span class="fw-medium">#{{ $trip->identifier }}</span>
                          </a>
                        </td>
                        <td>
                          @if($trip->driver)
                            <a href="{{ route('drivers.show', $trip->driver->user->id) }}" class="text-decoration-none">
                              <div class="d-flex align-items-center">
                                <i class="bx bx-user-circle me-1"></i>
                                <span>{{ $trip->driver->fullname }}</span>
                              </div>
                            </a>
                          @else
                            <span class="text-muted">N/A</span>
                          @endif
                        </td>
                        <td>
                          <span class="{{ 'badge bg-label-' . \App\Constants\TripType::get_color($trip->type)}}">
                              {{ \App\Constants\TripType::get_name($trip->type) }}
                            </span>
                        </td>
                        <td>
                          <small class="text-muted">{{ $trip->created_at->format('Y-m-d H:i') }}</small>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="3" class="text-center py-5">
                          <i class="bx bx-trip text-muted mb-2" style="font-size: 2rem;"></i>
                          <p class="text-muted mb-0">{{ __('app.no_data_available') }}</p>
                        </td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Reviews Tab -->
          <div class="tab-pane fade" id="reviews" role="tabpanel">
            <div class="card shadow-sm">
              <div class="card-body">
                @forelse($reviews as $review)
                  <div class="d-flex mb-4 pb-4 border-bottom">
                    <div class="flex-shrink-0">
                      <div class="avatar">
                        @if($review->reviewer)
                          <img src="{{ $review->reviewer->avatar_url }}" alt="Avatar" class="rounded-circle">
                        @else
                          <div class="avatar-initial bg-label-primary rounded">
                            <i class="bx bx-star"></i>
                          </div>
                        @endif
                      </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                      <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                          <h6 class="mb-1">
                            @if($review->reviewer)
                              {{ $review->reviewer->fullname }}
                              <span class="badge bg-label-{{ $review->reviewer_type === 'App\Models\Driver' ? 'info' : 'primary' }} ms-2">
                                {{ $review->reviewer_type === 'App\Models\Driver' ? __('app.driver') : __('app.passenger') }}
                              </span>
                            @endif
                            -
                            <a href="{{ route('trips.show', $review->trip->id) }}" class="text-decoration-none">
                              <span class="fw-medium">#{{ $review->trip->identifier }}</span>
                          </a>
                          </h6>
                          <div class="mb-2">
                            @for($i = 0; $i < 5; $i++)
                              @if($i < $review->rating)
                                <i class="bx bxs-star text-warning"></i>
                              @else
                                <i class="bx bx-star text-muted"></i>
                              @endif
                            @endfor
                            <span class="ms-2 fw-bold text-warning">{{ $review->rating }}.0</span>
                          </div>
                        </div>
                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                      </div>
                      @if($review->comment)
                        <p class="text-muted mb-0">{{ $review->comment }}</p>
                      @else
                        <p class="text-muted fst-italic mb-0">No review text provided</p>
                      @endif
                    </div>
                  </div>
                @empty
                  <div class="text-center py-5">
                    <i class="bx bx-message-square-detail text-muted mb-2" style="font-size: 3rem;"></i>
                    <p class="text-muted mb-0">{{ __('app.no_data_available') }}</p>
                  </div>
                @endforelse
              </div>
              @if($reviews->hasPages())
                <div class="card-footer">
                  {{ $reviews->links() }}
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modals for User Actions -->
  <x-modal.confirmation
    id="user-status-activate-modal"
    title="{{ __('user.modals.activate') }}"
    action="{{ route('users.status.update') }}"
    method="POST"
    inputs='
    <input type="hidden" name="id" value="">
    <input type="hidden" name="status" value="active">
    <input type="hidden" name="type" value="passenger">
  '
    theme="success"
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
    <input type="hidden" name="type" value="passenger">
  '
    theme="danger"
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
    <input type="hidden" name="type" value="passenger">
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
    <input type="hidden" name="type" value="passenger">
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
      // Handle activate modal
      $(document).on('click', '[data-bs-target="#user-status-activate-modal"]', function() {
        const userId = $(this).data('id');
        $('#user-status-activate-modal').find('input[name="id"]').val(userId);
      });

      // Handle suspend modal
      $(document).on('click', '[data-bs-target="#user-status-suspend-modal"]', function() {
        const userId = $(this).data('id');
        $('#user-status-suspend-modal').find('input[name="id"]').val(userId);
      });

      // Handle charge wallet modal
      $(document).on('click', '[data-bs-target="#charge-wallet-modal"]', function() {
        const userId = $(this).data('id');
        const walletBalance = parseFloat($(this).data('wallet-balance')) || 0;
        
        // Store wallet data in modal
        $('#charge-wallet-modal').data('walletBalance', walletBalance);
        $('#charge-wallet-modal').find('input[name="id"]').val(userId);
        $('#current_balance').text(walletBalance.toFixed(2));
        $('#new_charge_balance').text(walletBalance.toFixed(2));
        $('#charge_amount').val('');
      });

      // Handle withdraw modal
      $(document).on('click', '[data-bs-target="#withdraw-sum-modal"]', function() {
        const userId = $(this).data('id');
        const walletBalance = parseFloat($(this).data('wallet-balance')) || 0;
        
        // Store wallet data in modal
        $('#withdraw-sum-modal').data('walletBalance', walletBalance);
        $('#withdraw-sum-modal').find('input[name="id"]').val(userId);
        $('#current_balance_withdraw').text(walletBalance.toFixed(2));
        $('#new_withdraw_balance').text(walletBalance.toFixed(2));
        $('#withdraw_amount').val('');
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