@extends('layouts/contentNavbarLayout')

@section('title', __('trip.trip_details'))

@section('content')
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">{{ __('trip.trip_details') }} #{{ $trip->id }}</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
          <li class="breadcrumb-item"><a href="{{ route('trips.index', 'all') }}">{{ __('trip.trips') }}</a></li>
          <li class="breadcrumb-item active">{{ __('trip.trip_details') }}</li>
        </ol>
      </nav>
    </div>
    <a href="{{ url()->previous() }}" class="btn btn-label-secondary">
      <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
    </a>
  </div>

  <div class="row">
    <!-- Trip Information -->
    <div class="col-md-8">
      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">{{ __('trip.trip_information') }}</h5>
          <span class="badge bg-{{ \App\Constants\TripStatus::get_color($trip->status) }}">
            {{ \App\Constants\TripStatus::get_name($trip->status) }}
          </span>
        </div>
        <div class="card-body">
          <table class="table table-borderless">
            <tbody>
              <tr>
                <th width="200">{{ __('trip.trip_id') }}</th>
                <td>#{{ $trip->id }}</td>
              </tr>
              <tr>
                <th>{{ __('trip.identifier') }}</th>
                <td>{{ $trip->identifier ?? '-' }}</td>
              </tr>
              <tr>
                <th>{{ __('trip.type') }}</th>
                <td>
                  <span class="badge bg-{{ \App\Constants\TripType::get_color($trip->type) }}">
                    {{ \App\Constants\TripType::get_name($trip->type) }}
                  </span>
                </td>
              </tr>
              <tr>
                <th>{{ __('trip.price') }}</th>
                <td>{{ number_format($trip->price ?? 0, 2) }} MRU</td>
              </tr>
              <tr>
                <th>{{ __('trip.from_location') }}</th>
                <td>{{ $trip->from_location ?? '-' }}</td>
              </tr>
              <tr>
                <th>{{ __('trip.to_location') }}</th>
                <td>{{ $trip->to_location ?? '-' }}</td>
              </tr>
              @if($trip->note)
              <tr>
                <th>{{ __('trip.note') }}</th>
                <td>{{ $trip->note }}</td>
              </tr>
              @endif
              <tr>
                <th>{{ __('trip.created_at') }}</th>
                <td>{{ $trip->created_at->format('Y-m-d H:i:s') }}</td>
              </tr>
              <tr>
                <th>{{ __('trip.updated_at') }}</th>
                <td>{{ $trip->updated_at->format('Y-m-d H:i:s') }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Reviews -->
      @if($trip->reviews && $trip->reviews->count() > 0)
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0">{{ __('trip.reviews') }} ({{ $trip->reviews->count() }})</h5>
        </div>
        <div class="card-body">
          @foreach($trip->reviews as $review)
            <div class="d-flex mb-3 pb-3 border-bottom">
              <div class="flex-shrink-0 me-3">
                <img src="{{ $review->reviewer->getFirstMediaUrl(\App\Models\Passenger::IMAGE) ?: asset('assets/img/default-avatar.png') }}" 
                     alt="{{ $review->reviewer->fullname }}" 
                     class="rounded-circle" 
                     width="40" 
                     height="40">
              </div>
              <div class="flex-grow-1">
                <h6 class="mb-1">{{ $review->reviewer->fullname }}</h6>
                <div class="mb-1">
                  @for($i = 1; $i <= 5; $i++)
                    <i class="bx bxs-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                  @endfor
                </div>
                <p class="mb-0">{{ $review->comment }}</p>
                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
              </div>
            </div>
          @endforeach
        </div>
      </div>
      @endif
    </div>

    <!-- Driver & Passenger -->
    <div class="col-md-4">
      <!-- Driver Information -->
      @if($trip->driver)
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0">{{ __('trip.driver') }}</h5>
        </div>
        <div class="card-body text-center">
          <img src="{{ $trip->driver->getFirstMediaUrl(\App\Models\Driver::IMAGE) ?: asset('assets/img/default-avatar.png') }}" 
               alt="{{ $trip->driver->fullname }}" 
               class="rounded-circle mb-3" 
               width="100" 
               height="100">
          <h6 class="mb-1">{{ $trip->driver->fullname }}</h6>
          <p class="mb-2 text-muted">{{ $trip->driver->phone }}</p>
          <a href="{{ route('drivers.show', $trip->driver->id) }}" class="btn btn-sm btn-primary">
            {{ __('trip.view_profile') }}
          </a>
        </div>
      </div>
      @endif

      <!-- Passenger Information -->
      @if($trip->passenger)
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0">{{ __('trip.passenger') }}</h5>
        </div>
        <div class="card-body text-center">
          <img src="{{ $trip->passenger->getFirstMediaUrl(\App\Models\Passenger::IMAGE) ?: asset('assets/img/default-avatar.png') }}" 
               alt="{{ $trip->passenger->fullname }}" 
               class="rounded-circle mb-3" 
               width="100" 
               height="100">
          <h6 class="mb-1">{{ $trip->passenger->fullname }}</h6>
          <p class="mb-2 text-muted">{{ $trip->passenger->phone }}</p>
          <a href="{{ route('passengers.show', $trip->passenger->id) }}" class="btn btn-sm btn-primary">
            {{ __('trip.view_profile') }}
          </a>
        </div>
      </div>
      @endif

      <!-- Transactions -->
      @if($trip->transactions && $trip->transactions->count() > 0)
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">{{ __('trip.transactions') }}</h5>
        </div>
        <div class="card-body">
          @foreach($trip->transactions as $transaction)
            <div class="d-flex justify-content-between mb-2">
              <span>{{ \App\Constants\TransactionType::get_name($transaction->type) }}</span>
              <strong>{{ number_format($transaction->amount, 2) }} MRU</strong>
            </div>
          @endforeach
        </div>
      </div>
      @endif
    </div>
  </div>
@endsection
