@extends('layouts/contentNavbarLayout')

@section('title', __('driver.show'))

@section('content')
  <div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">{{ __('driver.profile') }}</h4>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('drivers.index') }}">{{ __('app.drivers') }}</a></li>
            <li class="breadcrumb-item active">{{ $driver->fullname }}</li>
          </ol>
        </nav>
      </div>
      <div class="d-flex gap-2 flex-wrap">
        @can(\App\Support\Enum\Permissions::DRIVER_CHANGE_USER_STATUS)
          @if($driver->user->status === \App\Constants\UserStatus::BANNED)
            <button type="button" class="btn btn-label-success" data-bs-toggle="modal" data-bs-target="#user-status-activate-modal" data-id="{{ $driver->user->id }}">
              <i class="bx bx-lock-open me-1"></i>{{ __('app.activate') }}
            </button>
          @elseif($driver->user->status === \App\Constants\UserStatus::ACTIVE)
            <button type="button" class="btn btn-label-danger" data-bs-toggle="modal" data-bs-target="#user-status-suspend-modal" data-id="{{ $driver->user->id }}">
              <i class="bx bx-lock me-1"></i>{{ __('app.suspend') }}
            </button>
          @endif
        @endcan
        @can(\App\Support\Enum\Permissions::DRIVER_CHANGE_STATUS)
          @if($driver->status !== \App\Constants\DriverStatus::APPROVED)
            <button type="button" class="btn btn-label-green" data-bs-toggle="modal" data-bs-target="#driver-status-approve-modal" data-id="{{ $driver->user->id }}">
              <i class="bx bx-check-circle me-1"></i>{{ __('app.approve') }}
            </button>
          @endif
          @if($driver->status !== \App\Constants\DriverStatus::DENIED)
            <button type="button" class="btn btn-label-warning" data-bs-toggle="modal" data-bs-target="#driver-status-deny-modal" data-id="{{ $driver->user->id }}">
              <i class="bx bx-x-circle me-1"></i>{{ __('app.deny') }}
            </button>
          @endif
        @endcan
        @can(\App\Support\Enum\Permissions::DRIVER_CHARGE_WALLET)
          <button type="button" class="btn btn-label-blue" data-bs-toggle="modal" data-bs-target="#charge-wallet-modal" data-id="{{ $driver->user->id }}" data-wallet-balance="{{ $driver->user->wallet?->balance ?? 0 }}">
            <i class="bx bx-wallet me-1"></i>{{ __('app.charge_wallet') }}
          </button>
        @endcan
        @can(\App\Support\Enum\Permissions::DRIVER_WITHDRAW_SUM)
          <button type="button" class="btn btn-label-teal" data-bs-toggle="modal" data-bs-target="#withdraw-sum-modal" data-id="{{ $driver->user->id }}" data-wallet-balance="{{ $driver->user->wallet?->balance ?? 0 }}">
            <i class="bx bx-money me-1"></i>{{ __('app.withdraw') }}
          </button>
        @endcan
        @can(\App\Support\Enum\Permissions::DRIVER_PURCHASE_SUBSCRIPTION)
          <button type="button" class="btn btn-label-purple" data-bs-toggle="modal" data-bs-target="#purchase-subscription-modal" data-id="{{ $driver->user->id }}" data-subscription-end-date="{{ $driver->subscription?->end_date }}" data-monthly-fee="{{ App\Models\Setting::getValue('subscription_month_price') ?? 0 }}">
            <i class="bx bx-calendar me-1"></i>{{ __('app.purchase_subscription') }}
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
                     src="{{ $driver->avatar_url }}" 
                     height="120" 
                     width="120" 
                     alt="User avatar"
                     style="object-fit: cover; border: 4px solid #fff;" />
                @if($driver->user->status === \App\Constants\UserStatus::ACTIVE)
                  <span class="badge bg-success rounded-pill position-absolute" 
                        style="bottom: 5px; right: 5px; width: 20px; height: 20px; padding: 0; border: 3px solid #fff;">
                  </span>
                @endif
              </div>
              <div class="d-flex align-items-center justify-content-center gap-1">
                <h5 class="mb-1 mt-3">{{ $driver->fullname }}</h5>
              </div>
              <p class="text-primary mb-1">{{ '@' . $driver->user->username }}</p>
              <p class="text-muted small mb-0">
                <i class="bx bx-phone me-1"></i>{{ $driver->user->phone }}
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
                  <small class="text-muted">{{ __('driver.trips') }}</small>
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
                  <small class="text-muted">{{ __('driver.avg_rating') }}</small>
                </div>
              </div>
            </div>

            <hr class="my-4">

            <!-- Personal Information -->
            <div class="text-start">
              <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                {{ __('driver.personal_info') }}
              </h6>
              <ul class="list-unstyled mb-0">
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-user text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('driver.first_name') }}</small>
                    <span class="fw-medium">{{ $driver->first_name ?? 'N/A' }}</span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-user text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('driver.last_name') }}</small>
                    <span class="fw-medium">{{ $driver->last_name ?? 'N/A' }}</span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-envelope text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('driver.email') }}</small>
                    <span class="fw-medium">{{ $driver->email ?? 'N/A' }}</span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-check-circle text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('user.status') }}</small>
                    @if($driver->user->status === \App\Constants\UserStatus::ACTIVE)
                      <span class="badge bg-label-success">{{ __('user.statuses.' . \App\Constants\UserStatus::ACTIVE) }}</span>
                    @elseif($driver->user->status === \App\Constants\UserStatus::BANNED)
                      <span class="badge bg-label-danger">{{ __('user.statuses.' . \App\Constants\UserStatus::BANNED) }}</span>
                    @else
                      <span class="badge bg-label-secondary">{{ __('user.statuses.' . $driver->user->status) }}</span>
                    @endif
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-briefcase text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('driver.driver_status') }}</small>
                    @if($driver->status === \App\Constants\DriverStatus::APPROVED)
                      <span class="badge bg-label-success">{{ __('constants.approved') }}</span>
                    @elseif($driver->status === \App\Constants\DriverStatus::DENIED)
                      <span class="badge bg-label-danger">{{ __('constants.denied') }}</span>
                    @else
                      <span class="badge bg-label-warning">{{ __('constants.pending') }}</span>
                    @endif
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-phone text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('driver.phone') }}</small>
                    <span class="fw-medium">{{ $driver->phone }}</span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-calendar text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('driver.birth_date') }}</small>
                    <span class="fw-medium">{{ $driver->birth_date ? $driver->birth_date->format('Y-m-d') : 'N/A' }}</span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-building text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('driver.federation') }}</small>
                    <span class="fw-medium">{{ $driver->federation->name ?? 'N/A' }}</span>
                  </div>
                </li>
                <li class="mb-0 d-flex align-items-center">
                  <i class="bx bx-time-five text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('driver.joined_date') }}</small>
                    <span class="fw-medium">{{ $driver->user->created_at->format('Y-m-d') }}</span>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column - Statistics & Data -->
      <div class="col-xl-8 col-lg-7">
        <!-- Wallet & Subscription Row -->
        <div class="row g-3 mb-4">
          <!-- Wallet Card -->
          <div class="col-md-6">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                  <div>
                    <div class="d-flex align-items-center mb-2">
                      <i class="bx bx-wallet text-success me-2" style="font-size: 1.5rem;"></i>
                      <h6 class="mb-0">{{ __('driver.wallet') }}</h6>
                    </div>
                    <h3 class="mb-0 text-success fw-bold">{{ number_format($driver->user->wallet->balance ?? 0, 2) }} {{ __('app.DZD') }}</h3>
                    <small class="text-muted">{{ __('driver.wallet_balance') }}</small>
                  </div>
                  <div class="avatar avatar-lg bg-label-success rounded">
                    <div class="avatar-initial bg-label-success rounded">
                      <i class="bx bx-wallet" style="font-size: 2rem;"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Subscription Card -->
          <div class="col-md-6">
            @if($driver->subscription)
              <div class="card shadow-sm h-100">
                <div class="card-body">
                  <div class="d-flex align-items-center justify-content-between">
                    <div>
                      <div class="d-flex align-items-center mb-2">
                        <i class="bx bx-calendar-check text-primary me-2" style="font-size: 1.5rem;"></i>
                        <h6 class="mb-0">{{ __('driver.subscription') }}</h6>
                      </div>
                      <p class="mb-1">
                        <strong>{{ __('driver.subscription_status') }}:</strong>
                        @if($driver->subscription->end_date >= now())
                          <span class="badge bg-label-success ms-2">{{ __('driver.subscription_active') }}</span>
                        @else
                          <span class="badge bg-label-danger ms-2">{{ __('driver.subscription_expired') }}</span>
                        @endif
                      </p>
                      <p class="mb-0 text-muted small">
                        <strong>{{ __('driver.subscription_end_date') }}:</strong> {{ $driver->subscription->end_date->format('Y-m-d') }}
                      </p>
                    </div>
                    <div class="avatar avatar-lg bg-label-primary rounded">
                      <div class="avatar-initial bg-label-primary rounded">
                        <i class="bx bx-calendar" style="font-size: 2rem;"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @else
              <div class="card shadow-sm h-100">
                <div class="card-body text-center py-4">
                  <i class="bx bx-calendar-x text-muted mb-2" style="font-size: 3rem;"></i>
                  <p class="text-muted mb-0">{{ __('driver.no_active_subscription') }}</p>
                </div>
              </div>
            @endif
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
                <small class="text-muted">{{ __('driver.total_reviews') }}</small>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <div class="avatar me-2">
                    <div class="avatar-initial bg-label-info rounded">
                      <i class="bx bx-transfer-alt"></i>
                    </div>
                  </div>
                  <h5 class="mb-0">{{ $stats['transactions_count'] }}</h5>
                </div>
                <small class="text-muted">{{ __('driver.total_transactions') }}</small>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-3">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <div class="avatar me-2">
                    <div class="avatar-initial bg-label-warning rounded">
                      <i class="bx bx-briefcase"></i>
                    </div>
                  </div>
                  <h5 class="mb-0">{{ $stats['services_count'] }}</h5>
                </div>
                <small class="text-muted">{{ __('driver.total_services') }}</small>
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
                  <h5 class="mb-0 text-truncate" title="{{ number_format($stats['total_earned'], 2) }} {{ __('app.DZD') }}">
                    {{ number_format($stats['total_earned'], 0) }} {{ __('app.DZD') }}
                  </h5>
                </div>
                <small class="text-muted">{{ __('driver.total_amount_earned') }}</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Vehicle Information Card -->
        @if($driver->vehicle)
          <div class="card shadow-sm mb-4">
            <div class="card-header pb-3">
              <h6 class="m-0"><i class="bx bx-car me-2"></i>{{ __('driver.vehicle_info') }}</h6>
            </div>
            <div class="card-body">
              <div class="row">
                <!-- Vehicle Details -->
                <div class="col-md-6 mb-3">
                  <div class="d-flex align-items-center">
                    <i class="bx bx-shape-circle text-muted me-2"></i>
                    <div>
                      <small class="text-muted d-block">{{ __('vehicle.model') }}</small>
                      <span class="fw-medium">{{ $driver->vehicle->model->brand->name ?? 'N/A' }} {{ $driver->vehicle->model->name ?? '' }}</span>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <div class="d-flex align-items-center">
                    <i class="bx bx-palette text-muted me-2"></i>
                    <div>
                      <small class="text-muted d-block">{{ __('vehicle.color') }}</small>
                      <div class="d-flex align-items-center gap-2">
                        @if($driver->vehicle->color)
                          <span class="d-inline-block rounded" 
                                style="width: 24px; height: 24px; background-color: {{ $driver->vehicle->color->code }}; border: 2px solid #ddd;"
                                title="{{ $driver->vehicle->color->code }}"></span>
                        @endif
                        <span class="fw-medium">{{ $driver->vehicle->color->name ?? 'N/A' }}</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <div class="d-flex align-items-center">
                    <i class="bx bx-calendar text-muted me-2"></i>
                    <div>
                      <small class="text-muted d-block">{{ __('vehicle.production_year') }}</small>
                      <span class="fw-medium">{{ $driver->vehicle->production_year ?? 'N/A' }}</span>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <div class="d-flex align-items-center">
                    <i class="bx bx-hash text-muted me-2"></i>
                    <div>
                      <small class="text-muted d-block">{{ __('vehicle.plate_number') }}</small>
                      <span class="fw-medium">{{ $driver->vehicle->plate_number ?? 'N/A' }}</span>
                    </div>
                  </div>
                </div>

                <!-- Vehicle Images -->
                @php
                  $vehicleImage = $driver->vehicle->getFirstMediaUrl('image');
                  $vehiclePermit = $driver->vehicle->getFirstMediaUrl('permit');
                @endphp

                @if($vehicleImage || $vehiclePermit)
                  <div class="col-12 mt-3">
                    <hr class="my-3">
                    <div id="vehicleImagesGallery" class="d-none">
                      @if($vehicleImage)
                        <img src="{{ $vehicleImage }}" alt="{{ __('vehicle.image') }}" data-title="{{ __('vehicle.image') }}">
                      @endif
                      @if($vehiclePermit)
                        <img src="{{ $vehiclePermit }}" alt="{{ __('vehicle.permit') }}" data-title="{{ __('vehicle.permit') }}">
                      @endif
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                      @if($vehicleImage)
                        <button type="button" class="btn btn-outline-primary btn-sm view-vehicle-image" data-index="0">
                          <i class="bx bx-image me-1"></i>{{ __('vehicle.image') }}
                        </button>
                      @endif
                      @if($vehiclePermit)
                        <button type="button" class="btn btn-outline-primary btn-sm view-vehicle-image" data-index="{{ $vehicleImage ? '1' : '0' }}">
                          <i class="bx bx-file me-1"></i>{{ __('vehicle.permit') }}
                        </button>
                      @endif
                    </div>
                  </div>
                @endif
              </div>
            </div>
          </div>
        @endif

        <!-- Cards Information -->
        @if($driver->cards->isNotEmpty())
          <div class="card shadow-sm mb-4">
            <div class="card-header pb-3">
              <h6 class="m-0"><i class="bx bx-id-card me-2"></i>{{ __('driver.cards') }}</h6>
            </div>
            <div class="card-body">
              <div class="row">
                @foreach($driver->cards as $card)
                  <div class="col-md-6 mb-4">
                    <div class="border rounded p-3 h-100">
                      <div class="d-flex align-items-start mb-3">
                        <i class="bx bx-credit-card text-primary me-2" style="font-size: 1.5rem;"></i>
                        <div class="flex-grow-1">
                          <h6 class="mb-1">{{ \App\Constants\CardType::get_name($card->type) }}</h6>
                          <p class="mb-1 small text-muted">
                            <strong>{{ __('card.number') }}:</strong> {{ $card->number }}
                          </p>
                          <p class="mb-0 small text-muted">
                            <strong>{{ __('card.expiration_date') }}:</strong> {{ $card->expiration_date ? $card->expiration_date->format('Y-m-d') : 'N/A' }}
                          </p>
                        </div>
                      </div>

                      @php
                        $frontImage = $card->getFirstMediaUrl('front_image');
                        $backImage = $card->getFirstMediaUrl('back_image');
                      @endphp

                      @if($frontImage || $backImage)
                        <div id="cardImagesGallery{{ $card->id }}" class="d-none">
                          @if($frontImage)
                            <img src="{{ $frontImage }}" alt="{{ __('card.front_image') }}" data-title="{{ \App\Constants\CardType::get_name($card->type) }} - {{ __('card.front_image') }}">
                          @endif
                          @if($backImage)
                            <img src="{{ $backImage }}" alt="{{ __('card.back_image') }}" data-title="{{ \App\Constants\CardType::get_name($card->type) }} - {{ __('card.back_image') }}">
                          @endif
                        </div>
                        <div class="d-flex gap-2 flex-wrap mt-2">
                          @if($frontImage)
                            <button type="button" class="btn btn-outline-primary btn-sm view-card-image" data-card-id="{{ $card->id }}" data-index="0">
                              <i class="bx bx-image me-1"></i>{{ __('card.front_image') }}
                            </button>
                          @endif
                          @if($backImage)
                            <button type="button" class="btn btn-outline-primary btn-sm view-card-image" data-card-id="{{ $card->id }}" data-index="{{ $frontImage ? '1' : '0' }}">
                              <i class="bx bx-image me-1"></i>{{ __('card.back_image') }}
                            </button>
                          @endif
                        </div>
                      @endif
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @endif

        <!-- Services Information -->
        @if($driver->services->isNotEmpty())
          <div class="card shadow-sm mb-4">
            <div class="card-header pb-3">
              <h6 class="m-0"><i class="bx bx-briefcase me-2"></i>{{ __('driver.services') }}</h6>
            </div>
            <div class="card-body">
              <div class="d-flex flex-wrap gap-2">
                @foreach($driver->services as $service)
                  <span class="{{ 'badge bg-label-' .  \App\Constants\TripType::get_color($service->trip_type)}}" style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                    {{ \App\Constants\TripType::get_name($service->trip_type) }}
                  </span>
                @endforeach
              </div>
            </div>
          </div>
        @endif

        <!-- Tabs for organized content -->
        <ul class="nav nav-pills mb-3" role="tablist">
          <li class="nav-item">
            <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#transactions" role="tab">
              <i class="bx bx-transfer me-1"></i>{{ __('driver.recent_transactions') }}
            </button>
          </li>
          <li class="nav-item">
            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#trips" role="tab">
              <i class="bx bx-trip me-1"></i>{{ __('driver.recent_trips') }}
            </button>
          </li>
          <li class="nav-item">
            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews" role="tab">
              <i class="bx bx-star me-1"></i>{{ __('driver.reviews') }}
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
                      <th>{{ __('trip.type') }}</th>
                      <th>{{ __('trip.status') }}</th>
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
                          <span class="{{ 'badge bg-label-' . \App\Constants\TripType::get_color($trip->type)}}">
                            {{ \App\Constants\TripType::get_name($trip->type) }}
                          </span>
                        </td>
                        <td>
                          <span class="{{ 'badge bg-label-' . \App\Constants\TripStatus::get_color($trip->status)}}">
                            {{ \App\Constants\TripStatus::get_name($trip->status) }}
                          </span>
                        </td>
                        <td>
                          <small class="text-muted">{{ $trip->created_at->format('Y-m-d H:i') }}</small>
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="4" class="text-center py-5">
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
                        <p class="text-muted fst-italic mb-0">{{ __('driver.no_review_text') }}</p>
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
    <input type="hidden" name="type" value="driver">
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
    <input type="hidden" name="type" value="driver">
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

@section('page-style')
  <!-- ViewerJS CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/viewerjs@1.11.6/dist/viewer.min.css">
@endsection

@section('page-script')
  <!-- ViewerJS -->
  <script src="https://cdn.jsdelivr.net/npm/viewerjs@1.11.6/dist/viewer.min.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize Vehicle Images Gallery
      const vehicleGalleryElement = document.getElementById('vehicleImagesGallery');
      if (vehicleGalleryElement) {
        const vehicleGallery = new Viewer(vehicleGalleryElement, {
          inline: false,
          title: function(image) {
            return image.alt + ' (' + (this.index + 1) + '/' + this.length + ')';
          },
          toolbar: {
            zoomIn: 1,
            zoomOut: 1,
            oneToOne: 1,
            reset: 1,
            prev: 1,
            play: 0,
            next: 1,
            rotateLeft: 1,
            rotateRight: 1,
            flipHorizontal: 1,
            flipVertical: 1,
          },
          navbar: true,
          tooltip: true,
          movable: true,
          zoomable: true,
          rotatable: true,
          scalable: true,
          transition: true,
          fullscreen: true,
          keyboard: true,
        });

        // Vehicle image buttons
        document.querySelectorAll('.view-vehicle-image').forEach(button => {
          button.addEventListener('click', function() {
            const index = parseInt(this.getAttribute('data-index'));
            vehicleGallery.view(index);
          });
        });
      }

      // Initialize Card Images Galleries
      @if($driver->cards->isNotEmpty())
        @foreach($driver->cards as $card)
          const cardGalleryElement{{ $card->id }} = document.getElementById('cardImagesGallery{{ $card->id }}');
          if (cardGalleryElement{{ $card->id }}) {
            const cardGallery{{ $card->id }} = new Viewer(cardGalleryElement{{ $card->id }}, {
              inline: false,
              title: function(image) {
                return image.alt + ' (' + (this.index + 1) + '/' + this.length + ')';
              },
              toolbar: {
                zoomIn: 1,
                zoomOut: 1,
                oneToOne: 1,
                reset: 1,
                prev: 1,
                play: 0,
                next: 1,
                rotateLeft: 1,
                rotateRight: 1,
                flipHorizontal: 1,
                flipVertical: 1,
              },
              navbar: true,
              tooltip: true,
              movable: true,
              zoomable: true,
              rotatable: true,
              scalable: true,
              transition: true,
              fullscreen: true,
              keyboard: true,
            });

            // Card image buttons for card {{ $card->id }}
            document.querySelectorAll('.view-card-image[data-card-id="{{ $card->id }}"]').forEach(button => {
              button.addEventListener('click', function() {
                const index = parseInt(this.getAttribute('data-index'));
                cardGallery{{ $card->id }}.view(index);
              });
            });
          }
        @endforeach
      @endif

      // Modal handlers for driver actions
      // Helper functions for subscription months
      window.incrementMonths = function() {
        const input = $('#subscription_months');
        input.val(parseInt(input.val()) + 1);
        input.trigger('input');
      }

      window.decrementMonths = function() {
        const input = $('#subscription_months');
        const current = parseInt(input.val());
        if (current > 1) {
          input.val(current - 1);
          input.trigger('input');
        }
      }

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

      // Handle approve modal
      $(document).on('click', '[data-bs-target="#driver-status-approve-modal"]', function() {
        const driverId = $(this).data('id');
        $('#driver-status-approve-modal').find('input[name="id"]').val(driverId);
      });

      // Handle deny modal
      $(document).on('click', '[data-bs-target="#driver-status-deny-modal"]', function() {
        const driverId = $(this).data('id');
        $('#driver-status-deny-modal').find('input[name="id"]').val(driverId);
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

      // Handle subscription modal
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
