@if($trip->status === \App\Constants\TripStatus::CANCELED && $trip->canceled_by_id)
  @php
    $canceledByModel = $trip->canceled_by_type === \App\Constants\UserType::DRIVER
      ? $trip->driver
      : $trip->passenger;
  @endphp
  <div class="card shadow-sm border-danger mb-4">
    <div class="card-header bg-label-danger d-flex align-items-center gap-2">
      <i class="bx bx-x-circle text-danger"></i>
      <h6 class="mb-0 text-danger">{{ __('trip.cancellation_info') }}</h6>
    </div>
    <div class="card-body">
      <ul class="list-unstyled mb-0">

        {{-- Who canceled --}}
        <li class="mb-3 d-flex align-items-start">
          <i class="bx bx-user-x text-muted me-2 mt-1"></i>
          <div class="flex-grow-1">
            <small class="text-muted d-block">{{ __('trip.canceled_by') }}</small>
            <div class="d-flex align-items-center gap-2 mt-1">
              <span class="badge bg-label-{{ $trip->canceled_by_type === \App\Constants\UserType::DRIVER ? 'info' : 'primary' }}">
                {{ $trip->canceled_by_type === \App\Constants\UserType::DRIVER ? __('app.driver') : __('app.passenger') }}
              </span>
              @if($canceledByModel)
                @if($trip->canceled_by_type === \App\Constants\UserType::DRIVER)
                  <a href="{{ route('drivers.show', $canceledByModel->user_id) }}" class="fw-medium text-body text-decoration-none">
                    {{ $canceledByModel->fullname }}
                  </a>
                @else
                  <a href="{{ route('passengers.show', $canceledByModel->user_id) }}" class="fw-medium text-body text-decoration-none">
                    {{ $canceledByModel->fullname }}
                  </a>
                @endif
              @endif
            </div>
          </div>
        </li>

        {{-- Reason --}}
        <li class="mb-3 d-flex align-items-start">
          <i class="bx bx-question-mark text-muted me-2 mt-1"></i>
          <div class="flex-grow-1">
            <small class="text-muted d-block">{{ __('trip.cancellation_reason') }}</small>
            <span class="fw-medium">
              {{ $trip->cancellation_reason ? \App\Constants\CancellationReason::get_name($trip->cancellation_reason) : '-' }}
            </span>
          </div>
        </li>

        {{-- Note (only shown if present) --}}
        @if($trip->cancellation_note)
          <li class="mb-0 d-flex align-items-start">
            <i class="bx bx-comment-detail text-muted me-2 mt-1"></i>
            <div class="flex-grow-1">
              <small class="text-muted d-block">{{ __('trip.cancellation_note') }}</small>
              <span class="fw-medium">{{ $trip->cancellation_note }}</span>
            </div>
          </li>
        @endif

      </ul>
    </div>
  </div>
@endif
