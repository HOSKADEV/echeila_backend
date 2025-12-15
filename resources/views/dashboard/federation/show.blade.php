@extends('layouts/contentNavbarLayout')

@section('title', __('federation.show'))

@section('content')
  <div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">{{ __('federation.profile') }}</h4>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('federations.index') }}">{{ __('federation.federations') }}</a></li>
            <li class="breadcrumb-item active">{{ $federation->name }}</li>
          </ol>
        </nav>
      </div>
      <div class="d-flex gap-2">
        @can(\App\Support\Enum\Permissions::FEDERATION_CHANGE_USER_STATUS)
          @if($federation->user->status === \App\Constants\UserStatus::BANNED)
            <button type="button" class="btn btn-label-success" data-bs-toggle="modal" data-bs-target="#user-status-activate-modal" data-id="{{ $federation->user->id }}">
              <i class="bx bx-lock-open me-1"></i>{{ __('app.activate') }}
            </button>
          @elseif($federation->user->status === \App\Constants\UserStatus::ACTIVE)
            <button type="button" class="btn btn-label-danger" data-bs-toggle="modal" data-bs-target="#user-status-suspend-modal" data-id="{{ $federation->user->id }}">
              <i class="bx bx-lock me-1"></i>{{ __('app.suspend') }}
            </button>
          @endif
        @endcan
        <a href="{{ url()->previous() }}" class="btn btn-label-secondary">
          <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
        </a>
      </div>
    </div>

    <div class="row">
      <!-- Left Column - Federation Card -->
      <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card shadow-sm">
          <div class="card-body text-center">
            <!-- Federation Image Section -->
            <div class="mb-4">
              <div class="position-relative d-inline-block">
                <img class="img-fluid rounded shadow-sm" 
                     src="{{ $federation->avatar_url }}" 
                     height="120" 
                     width="120" 
                     alt="Federation logo"
                     style="object-fit: cover; border: 4px solid #fff;" />
                @if($federation->user->status === \App\Constants\UserStatus::ACTIVE)
                  <span class="badge bg-success rounded-pill position-absolute" 
                        style="bottom: 5px; right: 5px; width: 20px; height: 20px; padding: 0; border: 3px solid #fff;">
                  </span>
                @endif
              </div>
              <div class="d-flex align-items-center justify-content-center gap-1">
              <h5 class="mb-1 mt-3">{{ $federation->name }}</h5>
              </div>
              <p class="text-muted small mb-0">
                <i class="bx bx-calendar me-1"></i>{{ __('federation.creation_date') }}: {{ $federation->creation_date ? $federation->creation_date->format('Y-m-d') : 'N/A' }}
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
                    <h4 class="mb-0">{{ $stats['total_trips'] }}</h4>
                  </div>
                  <small class="text-muted">{{ __('federation.total_trips') }}</small>
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
                  <small class="text-muted">{{ __('federation.avg_rating') }}</small>
                </div>
              </div>
            </div>

            <hr class="my-4">

            <!-- Federation Information -->
            <div class="text-start">
              <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                {{ __('federation.federation_info') }}
              </h6>
              <ul class="list-unstyled mb-0">
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-buildings text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('federation.name') }}</small>
                    <span class="fw-medium">{{ $federation->name }}</span>
                  </div>
                </li>
                @if($federation->description)
                  <li class="mb-3 d-flex align-items-start">
                    <i class="bx bx-info-circle text-muted me-2 mt-1"></i>
                    <div class="flex-grow-1">
                      <small class="text-muted d-block">{{ __('federation.description') }}</small>
                      <span class="fw-medium">{{ $federation->description }}</span>
                    </div>
                  </li>
                @endif
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-check-circle text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('user.status') }}</small>
                    @if($federation->user->status === \App\Constants\UserStatus::ACTIVE)
                      <span class="badge bg-label-success">{{ __('user.statuses.' . \App\Constants\UserStatus::ACTIVE) }}</span>
                    @elseif($federation->user->status === \App\Constants\UserStatus::BANNED)
                      <span class="badge bg-label-danger">{{ __('user.statuses.' . \App\Constants\UserStatus::BANNED) }}</span>
                    @else
                      <span class="badge bg-label-secondary">{{ __('user.statuses.' . $federation->user->status) }}</span>
                    @endif
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-calendar text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('federation.creation_date') }}</small>
                    <span class="fw-medium">{{ $federation->creation_date ? $federation->creation_date->format('Y-m-d') : 'N/A' }}</span>
                  </div>
                </li>
                <li class="mb-0 d-flex align-items-center">
                  <i class="bx bx-time-five text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('federation.joined_date') }}</small>
                    <span class="fw-medium">{{ $federation->user->created_at->format('Y-m-d') }}</span>
                  </div>
                </li>
              </ul>
            </div>

            <hr class="my-4">

            <!-- Owner Information -->
            <div class="text-start">
              <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                {{ __('federation.owner_info') }}
              </h6>
              <ul class="list-unstyled mb-0">
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-user text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('federation.owner_name') }}</small>
                    <span class="fw-medium">{{ '@' . $federation->user->username }}</span>
                  </div>
                </li>
                <li class="mb-0 d-flex align-items-center">
                  <i class="bx bx-phone text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('federation.owner_phone') }}</small>
                    <span class="fw-medium">{{ $federation->user->phone }}</span>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column - Drivers List -->
      <div class="col-xl-8 col-lg-7">
        <!-- Statistics Grid -->
        <div class="row g-3 mb-4">
          <div class="col-sm-6 col-xl-4">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <div class="avatar me-2">
                    <div class="avatar-initial bg-label-primary rounded">
                      <i class="bx bx-group"></i>
                    </div>
                  </div>
                  <h5 class="mb-0">{{ $stats['total_drivers'] }}</h5>
                </div>
                <small class="text-muted">{{ __('federation.total_drivers') }}</small>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-4">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <div class="avatar me-2">
                    <div class="avatar-initial bg-label-success rounded">
                      <i class="bx bx-check-circle"></i>
                    </div>
                  </div>
                  <h5 class="mb-0">{{ $stats['active_drivers'] }}</h5>
                </div>
                <small class="text-muted">{{ __('federation.active_drivers') }}</small>
              </div>
            </div>
          </div>
          <div class="col-sm-6 col-xl-4">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                  <div class="avatar me-2">
                    <div class="avatar-initial bg-label-danger rounded">
                      <i class="bx bx-x-circle"></i>
                    </div>
                  </div>
                  <h5 class="mb-0">{{ $stats['banned_drivers'] }}</h5>
                </div>
                <small class="text-muted">{{ __('federation.banned_drivers') }}</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Drivers List Card -->
        <div class="card shadow-sm">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0">{{ __('federation.drivers_list') }}</h5>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#add-driver-modal" data-federation-id="{{ $federation->id }}">
              <i class="bx bx-plus me-1"></i>{{ __('federation.add_driver') }}
            </button>
          </div>
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="table-light">
                <tr>
                  <th>{{ __('federation.driver_name') }}</th>
                  <th>{{ __('federation.driver_phone') }}</th>
                  <th>{{ __('federation.driver_status') }}</th>
                  <th>{{ __('federation.joined_date') }}</th>
                  <th>{{ __('app.actions') }}</th>
                </tr>
              </thead>
              <tbody>
                @forelse($drivers as $driver)
                  <tr>
                    <td>
                      <div class="d-flex align-items-center">
                        <img class="rounded-circle me-2" 
                             src="{{ $driver->avatar_url }}" 
                             alt="Driver avatar" 
                             width="32" 
                             height="32"
                             style="object-fit: cover;" />
                        <div>
                          <span class="fw-medium">{{ $driver->fullname }}</span>
                          <br>
                          <small class="text-muted">{{ '@' . $driver->user->username }}</small>
                        </div>
                      </div>
                    </td>
                    <td>
                      <i class="bx bx-phone me-1"></i>{{ $driver->user->phone }}
                    </td>
                    <td>
                      @if($driver->user->status === \App\Constants\UserStatus::ACTIVE)
                        <span class="badge bg-label-success">{{ __('user.statuses.' . \App\Constants\UserStatus::ACTIVE) }}</span>
                      @elseif($driver->user->status === \App\Constants\UserStatus::BANNED)
                        <span class="badge bg-label-danger">{{ __('user.statuses.' . \App\Constants\UserStatus::BANNED) }}</span>
                      @else
                        <span class="badge bg-label-secondary">{{ __('user.statuses.' . $driver->user->status) }}</span>
                      @endif
                    </td>
                    <td>
                      <small class="text-muted">{{ $driver->user->created_at->format('Y-m-d') }}</small>
                    </td>
                    <td>
                      <div class="d-flex gap-1">
                        <a href="{{ route('drivers.show', $driver->user->id) }}" class="btn btn-sm btn-icon btn-label-info">
                          <i class="bx bx-show"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-icon btn-label-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#remove-driver-modal" 
                                data-driver-id="{{ $driver->id }}"
                                data-driver-name="{{ $driver->fullname }}"
                                data-federation-id="{{ $federation->id }}">
                          <i class="bx bx-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="5" class="text-center py-5">
                      <i class="bx bx-group text-muted mb-2" style="font-size: 2rem;"></i>
                      <p class="text-muted mb-0">{{ __('federation.no_drivers') }}</p>
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          @if($drivers->hasPages())
            <div class="card-footer">
              {{ $drivers->links() }}
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Modals for Federation Actions -->
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
    confirmationTitle="{{ __('user.suspend.confirmation') }}"
    confirmationText="{{ __('user.suspend.notice') }}"
    checkboxLabel="{{ __('user.suspend.confirm_checkbox') }}"
    submitLabel="{{ __('app.submit') }}"
    cancelLabel="{{ __('app.cancel') }}"
  />

  <!-- Add Driver Modal -->
  @php
    $driverOptions = '<option value="">' . __("federation.choose_driver") . '</option>';
    foreach($availableDrivers as $driver) {
        $driverOptions .= '<option value="' . $driver->id . '">' . 
                         e($driver->fullname) . ' (' . e($driver->user->phone) . ')</option>';
    }
  @endphp

  <x-modal.form
    id="add-driver-modal"
    title="{{ __('federation.add_driver') }}"
    action="{{ route('drivers.federation.add') }}"
    method="POST"
    inputs='
    <input type="hidden" name="federation_id" value="">
    
    <!-- Driver Selection -->
    <div class="mb-4">
      <label class="form-label fw-bold" for="driver_id">
        <i class="bx bx-user me-2"></i>{{ __("federation.select_driver") }}
      </label>
      <select name="driver_id" id="driver_id" class="form-select form-select-lg" required>
        {!! $driverOptions !!}
      </select>
      <small class="text-muted d-block mt-2">{{ __("federation.select_driver_info") }}</small>
    </div>
    
    <div class="alert alert-info d-flex align-items-center" role="alert">
      <i class="bx bx-info-circle me-2"></i>
      <div>{{ __("federation.add_driver_notice") }}</div>
    </div>
  '
    theme="primary"
    submitLabel="{{ __('app.add') }}"
    cancelLabel="{{ __('app.cancel') }}"
  />

  <!-- Remove Driver Modal -->
  <x-modal.confirmation
    id="remove-driver-modal"
    title="{{ __('federation.remove_driver') }}"
    action="{{ route('drivers.federation.remove') }}"
    method="POST"
    inputs='
    <input type="hidden" name="federation_id" value="">
    <input type="hidden" name="driver_id" value="">
  '
    theme="danger"
    confirmationTitle="{{ __('federation.remove_driver_confirmation') }}"
    confirmationText='<span id="driver-name-placeholder"></span>'
    checkboxLabel="{{ __('federation.remove_driver_confirm_checkbox') }}"
    submitLabel="{{ __('app.remove') }}"
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

      // Handle Add Driver Modal
      $(document).on('click', '[data-bs-target="#add-driver-modal"]', function() {
        const federationId = $(this).data('federation-id');
        $('#add-driver-modal').find('input[name="federation_id"]').val(federationId);
      });

      // Handle Remove Driver Modal
      $(document).on('click', '[data-bs-target="#remove-driver-modal"]', function() {
        const driverId = $(this).data('driver-id');
        const driverName = $(this).data('driver-name');
        const federationId = $(this).data('federation-id');
        
        $('#remove-driver-modal').find('input[name="driver_id"]').val(driverId);
        $('#remove-driver-modal').find('input[name="federation_id"]').val(federationId);
        $('#driver-name-placeholder').text('{{ __("federation.remove_driver_text") }} ' + driverName + '?');
      });
    });
  </script>
@endsection
