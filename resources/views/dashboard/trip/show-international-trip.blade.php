@extends('layouts/contentNavbarLayout')

@section('title', __('trip.trip') . ' #' . $trip->identifier)

@section('content')
  <div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">{{ __('trip.trip') }} #{{ $trip->identifier }}</h4>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('trips.index', 'international_trip') }}">{{ __('trip.types.international_trip') }}</a></li>
            <li class="breadcrumb-item active">#{{ $trip->identifier }}</li>
          </ol>
        </nav>
      </div>
      <a href="{{ url()->previous() }}" class="btn btn-label-secondary">
        <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
      </a>
    </div>

    <div class="row">
      <!-- Left Column - Trip Card -->
      <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <!-- Trip Status Badge -->
            <div class="text-center mb-4">
              <div class="mb-3">
                <div class="avatar avatar-xl bg-label-primary rounded-circle mx-auto">
                    <div class="avatar-initial bg-label-primary rounded-circle">
                        <i class="bx bx-world" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
              </div>
              <div class="d-flex align-items-center justify-content-center gap-1">
                <h5 class="mb-2">{{ \App\Constants\TripType::get_name($trip->type) }}</h5>
              </div>
              <span class="{{ 'badge bg-label-' . \App\Constants\TripStatus::get_color($trip->status) }} mb-2">
                {{ \App\Constants\TripStatus::get_name($trip->status) }}
              </span>
              <br>
              <span class="{{ 'badge bg-label-' . \App\Constants\Direction::get_color($trip->detailable->direction) }}">
                @if($trip->type == \App\Constants\TripType::MRT_TRIP)
                    {{ $trip->detailable->direction == \App\Constants\Direction::FROM ? __('trip.mrt_from') : __('trip.mrt_to') }}
                @else
                  {{ $trip->detailable->direction == \App\Constants\Direction::FROM ? __('trip.esp_from') : __('trip.esp_to') }}
                @endif
              </span>
            </div>

            <hr class="my-4">

            <!-- Trip Information -->
            <div class="text-start">
              <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                {{ __('trip.trip_information') }}
              </h6>
              <ul class="list-unstyled mb-0">
                <li class="mb-3 d-flex align-items-start">
                  <i class="bx bx-map text-muted me-2 mt-1"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('trip.starting_place') }}</small>
                    <span class="fw-medium">{{ $trip->detailable->starting_place ?? 'N/A' }}</span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-time text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('trip.starting_time') }}</small>
                    <span class="fw-medium">{{ $trip->detailable->starting_time ? $trip->detailable->starting_time->format('Y-m-d H:i') : 'N/A' }}</span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-time-five text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('trip.arrival_time') }}</small>
                    <span class="fw-medium">{{ $trip->detailable->arrival_time ? $trip->detailable->arrival_time->format('Y-m-d H:i') : 'N/A' }}</span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-user-plus text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('trip.total_seats') }}</small>
                    <span class="fw-medium">{{ $trip->detailable->total_seats ?? 'N/A' }}</span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-user-check text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('trip.booked_seats') }}</small>
                    <span class="fw-medium">{{ $trip->clients->sum('number_of_seats') }}</span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-money text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('trip.seat_price') }}</small>
                    <span class="fw-medium">{{ number_format($trip->detailable->seat_price ?? 0, 2) }} {{ __('app.DZD') }}</span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-calendar text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('trip.created_at') }}</small>
                    <span class="fw-medium">{{ $trip->created_at->format('Y-m-d H:i') }}</span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-time-five text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('trip.updated_at') }}</small>
                    <span class="fw-medium">{{ $trip->updated_at->format('Y-m-d H:i') }}</span>
                  </div>
                </li>
                @if($trip->note)
                  <li class="mb-0 d-flex align-items-start">
                    <i class="bx bx-note text-muted me-2 mt-1"></i>
                    <div class="flex-grow-1">
                      <small class="text-muted d-block">{{ __('trip.note') }}</small>
                      <span class="fw-medium">{{ $trip->note }}</span>
                    </div>
                  </li>
                @endif
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column - Details & Data -->
      <div class="col-xl-8 col-lg-7">
        <!-- Driver Card -->
        <div class="row g-3 mb-4">
          <div class="col-12">
            <div class="card shadow-sm">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                  <h6 class="mb-0">{{ __('trip.driver') }}</h6>
                  @if($trip->driver)
                    <a href="{{ route('drivers.show', $trip->driver->user_id) }}" class="btn btn-sm btn-label-primary">
                      <i class="bx bx-show me-1"></i>{{ __('trip.view_profile') }}
                    </a>
                  @endif
                </div>
                @if($trip->driver)
                  <div class="d-flex align-items-center">
                    <img class="img-fluid rounded-circle me-3" 
                         src="{{ $trip->driver->avatar_url }}" 
                         height="60" 
                         width="60" 
                         alt="Driver avatar"
                         style="object-fit: cover;" />
                    <div>
                      <h6 class="mb-1">{{ $trip->driver->fullname }}</h6>
                      <p class="text-muted small mb-1">
                        <i class="bx bx-phone me-1"></i>{{ $trip->driver->user->phone }}
                      </p>
                      <div class="d-flex align-items-center">
                        <i class="bx bxs-star text-warning me-1"></i>
                        <span class="small">{{ number_format($trip->driver->review_average, 1) }} ({{ $trip->driver->trip_count }} {{ __('passenger.trips') }})</span>
                      </div>
                    </div>
                  </div>
                @else
                  <p class="text-muted mb-0">{{ __('app.no_data_available') }}</p>
                @endif
              </div>
            </div>
          </div>
        </div>

        <!-- Financial Stats -->
        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="avatar avatar-md bg-label-success rounded me-3">
                    <div class="avatar-initial bg-label-success rounded">
                      <i class="bx bx-user-plus"></i>
                    </div>
                  </div>
                  <div>
                    <small class="text-muted d-block">{{ __('trip.total_passengers') }}</small>
                    <h4 class="mb-0 text-success fw-bold">{{ $trip->clients->count() }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="avatar avatar-md bg-label-info rounded me-3">
                    <div class="avatar-initial bg-label-info rounded">
                      <i class="bx bx-package"></i>
                    </div>
                  </div>
                  <div>
                    <small class="text-muted d-block">{{ __('trip.total_cargos') }}</small>
                    <h4 class="mb-0 text-info fw-bold">{{ $trip->cargos->count() }}</h4>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center">
                  <div class="avatar avatar-md bg-label-primary rounded me-3">
                    <div class="avatar-initial bg-label-primary rounded">
                      <i class="bx bx-money"></i>
                    </div>
                  </div>
                  <div>
                    <small class="text-muted d-block">{{ __('trip.total_revenue') }}</small>
                    <h4 class="mb-0 text-primary fw-bold">{{ number_format($trip->clients->sum('total_fees') + $trip->cargos->sum('total_fees'), 2) }}</h4>
                    <small class="text-muted">{{ __('app.DZD') }}</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Vehicle Information Card -->
        @if($trip->driver && $trip->driver->vehicle)
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
                      <span class="fw-medium">{{ $trip->driver->vehicle->model->brand->name ?? 'N/A' }} {{ $trip->driver->vehicle->model->name ?? '' }}</span>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <div class="d-flex align-items-center">
                    <i class="bx bx-palette text-muted me-2"></i>
                    <div>
                      <small class="text-muted d-block">{{ __('vehicle.color') }}</small>
                      <div class="d-flex align-items-center gap-2">
                        @if($trip->driver->vehicle->color)
                          <span class="d-inline-block rounded" 
                                style="width: 24px; height: 24px; background-color: {{ $trip->driver->vehicle->color->code }}; border: 2px solid #ddd;"
                                title="{{ $trip->driver->vehicle->color->code }}"></span>
                        @endif
                        <span class="fw-medium">{{ $trip->driver->vehicle->color->name ?? 'N/A' }}</span>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <div class="d-flex align-items-center">
                    <i class="bx bx-calendar text-muted me-2"></i>
                    <div>
                      <small class="text-muted d-block">{{ __('vehicle.production_year') }}</small>
                      <span class="fw-medium">{{ $trip->driver->vehicle->production_year ?? 'N/A' }}</span>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <div class="d-flex align-items-center">
                    <i class="bx bx-hash text-muted me-2"></i>
                    <div>
                      <small class="text-muted d-block">{{ __('vehicle.plate_number') }}</small>
                      <span class="fw-medium">{{ $trip->driver->vehicle->plate_number ?? 'N/A' }}</span>
                    </div>
                  </div>
                </div>

                <!-- Vehicle Images -->
                @php
                  $vehicleImage = $trip->driver->vehicle->getFirstMediaUrl('image');
                  $vehiclePermit = $trip->driver->vehicle->getFirstMediaUrl('permit');
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

        <!-- Tabs for organized content -->
        <ul class="nav nav-pills mb-3" role="tablist">
          <li class="nav-item">
            <button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#passengers" role="tab">
              <i class="bx bx-user me-1"></i>{{ __('trip.passengers') }} ({{ $trip->clients->count() }})
            </button>
          </li>
          <li class="nav-item">
            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#cargos" role="tab">
              <i class="bx bx-package me-1"></i>{{ __('trip.cargos') }} ({{ $trip->cargos->count() }})
            </button>
          </li>
          <li class="nav-item">
            <button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews" role="tab">
              <i class="bx bx-star me-1"></i>{{ __('trip.reviews') }} ({{ $trip->reviews->count() }})
            </button>
          </li>
        </ul>

        <div class="tab-content">
          <!-- Passengers Tab -->
          <div class="tab-pane fade show active" id="passengers" role="tabpanel">
            <div class="card shadow-sm">
              <div class="card-body">
                @forelse($trip->clients as $tripClient)
                  <div class="d-flex align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="flex-shrink-0">
                      @if($tripClient->client)
                        <img class="img-fluid rounded-circle me-3" 
                             src="{{ $tripClient->client->avatar_url }}" 
                             height="50" 
                             width="50" 
                             alt="Passenger avatar"
                             style="object-fit: cover;" />
                      @else
                        <div class="avatar avatar-md bg-label-primary rounded-circle me-3">
                          <div class="avatar-initial bg-label-primary rounded-circle">
                            <i class="bx bx-user"></i>
                          </div>
                        </div>
                      @endif
                    </div>
                    
                    <div class="flex-grow-1">
                      @if($tripClient->client)
                        @if($tripClient->client_type === 'App\\Models\\Passenger')
                          <a href="{{ route('passengers.show', $tripClient->client->user_id) }}" class="fw-medium text-body text-decoration-none d-inline">{{ $tripClient->client->fullname }}</a>
                        @else
                          <span class="fw-medium">{{ $tripClient->client->fullname }}</span>
                        @endif
                        <p class="text-muted small mb-0">
                          <i class="bx bx-phone me-1"></i>{{ $tripClient->client_type === 'App\\Models\\Passenger' ? $tripClient->client->user->phone : $tripClient->client->phone }}
                        </p>
                      @else
                        <p class="text-muted mb-0">{{ __('app.no_data_available') }}</p>
                      @endif
                    </div>
                    <div class="text-end">
                      <div class="mb-1">
                        <span class="badge bg-label-info">
                          <i class="bx bx-user-plus me-1"></i>{{ $tripClient->number_of_seats }} {{ __('trip.seats') }}
                        </span>
                      </div>
                      <span class="fw-bold text-success">{{ number_format($tripClient->total_fees ?? 0, 2) }} {{ __('app.DZD') }}</span>
                    </div>
                  </div>
                @empty
                  <div class="text-center py-5">
                    <i class="bx bx-user text-muted mb-2" style="font-size: 3rem;"></i>
                    <p class="text-muted mb-0">{{ __('app.no_data_available') }}</p>
                  </div>
                @endforelse
              </div>
            </div>
          </div>

          <!-- Cargos Tab -->
          <div class="tab-pane fade" id="cargos" role="tabpanel">
            <div class="card shadow-sm">
              <div class="card-body">
                @forelse($trip->cargos as $tripCargo)
                  @php
                    $cargo = $tripCargo->cargo;
                  @endphp
                  <div class="mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                      <div>
                        <h6 class="mb-2">{{ __('trip.cargo') }} #{{ $loop->iteration }}</h6>
                        @if($cargo && $cargo->description)
                          <p class="text-muted mb-2">{{ $cargo->description }}</p>
                        @endif
                      </div>
                      <div class="text-end">
                        @if($cargo && $cargo->weight)
                          <span class="badge bg-label-info mb-1">
                            <i class="bx bx-package me-1"></i>{{ $cargo->weight }} kg
                          </span>
                          <br>
                        @endif
                        <span class="fw-bold text-success">{{ number_format($tripCargo->total_fees ?? 0, 2) }} {{ __('app.DZD') }}</span>
                      </div>
                    </div>

                    @if($cargo && $cargo->hasMedia('images'))
                      @php
                        $cargoImages = $cargo->getMedia('images');
                      @endphp
                      <div id="cargoImagesGallery{{ $cargo->id }}" class="d-none">
                        @foreach($cargoImages as $image)
                          <img src="{{ $image->getUrl() }}" alt="{{ __('trip.cargo_image') }}" data-title="{{ __('trip.cargo_image') }} {{ $loop->iteration }}">
                        @endforeach
                      </div>
                      <div class="d-flex gap-2 flex-wrap">
                        @foreach($cargoImages as $image)
                          <button type="button" class="btn btn-outline-primary btn-sm view-cargo-image" data-cargo-id="{{ $cargo->id }}" data-index="{{ $loop->index }}">
                            <i class="bx bx-image me-1"></i>{{ __('trip.cargo_image') }} {{ $loop->iteration }}
                          </button>
                        @endforeach
                      </div>
                    @endif
                  </div>
                @empty
                  <div class="text-center py-5">
                    <i class="bx bx-package text-muted mb-2" style="font-size: 3rem;"></i>
                    <p class="text-muted mb-0">{{ __('app.no_data_available') }}</p>
                  </div>
                @endforelse
              </div>
            </div>
          </div>

          <!-- Reviews Tab -->
          <div class="tab-pane fade" id="reviews" role="tabpanel">
            <div class="card shadow-sm">
              <div class="card-body">
                @forelse($trip->reviews as $review)
                  <div class="d-flex mb-4 pb-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="flex-shrink-0">
                      <div class="avatar">
                        @if($review->reviewer)
                          <img src="{{ $review->reviewer->avatar_url }}" alt="Avatar" class="rounded-circle">
                        @else
                          <div class="avatar-initial bg-label-primary rounded-circle">
                            <i class="bx bx-user"></i>
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
                            @else
                              {{ __('app.user') }}
                            @endif
                            <i class="bx bx-right-arrow-alt mx-1"></i>
                            @if($review->reviewee)
                              {{ $review->reviewee->fullname }}
                              <span class="badge bg-label-{{ $review->reviewee_type === 'App\Models\Driver' ? 'info' : 'primary' }} ms-2">
                                {{ $review->reviewee_type === 'App\Models\Driver' ? __('app.driver') : __('app.passenger') }}
                              </span>
                            @endif
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
                        <p class="text-muted fst-italic mb-0">{{ __('app.no_comment') }}</p>
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
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
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

      // Initialize Cargo Images Galleries
      @if($trip->cargos->count() > 0)
        @foreach($trip->cargos as $tripCargo)
          @if($tripCargo->cargo && $tripCargo->cargo->hasMedia('images'))
            @php
              $cargo = $tripCargo->cargo;
              $cargoImages = $cargo->getMedia('images');
            @endphp
            @if($cargoImages->isNotEmpty())
              const cargoGalleryElement{{ $cargo->id }} = document.getElementById('cargoImagesGallery{{ $cargo->id }}');
              if (cargoGalleryElement{{ $cargo->id }}) {
                const cargoGallery{{ $cargo->id }} = new Viewer(cargoGalleryElement{{ $cargo->id }}, {
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

                // Cargo image buttons for cargo {{ $cargo->id }}
                document.querySelectorAll('.view-cargo-image[data-cargo-id="{{ $cargo->id }}"]').forEach(button => {
                  button.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    cargoGallery{{ $cargo->id }}.view(index);
                  });
                });
              }
            @endif
          @endif
        @endforeach
      @endif
    });
  </script>
@endsection
