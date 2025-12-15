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
            <li class="breadcrumb-item"><a href="{{ route('trips.index', 'cargo_transport') }}">{{ __('trip.types.cargo_transport') }}</a></li>
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
                <div class="avatar avatar-xl bg-label-warning rounded-circle mx-auto">
                    <div class="avatar-initial bg-label-warning rounded-circle">
                        <i class="bx bxs-package" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
              </div>
              <div class="d-flex align-items-center justify-content-center gap-1">
                <h5 class="mb-2">{{ \App\Constants\TripType::get_name($trip->type) }}</h5>
              </div>
              <span class="{{ 'badge bg-label-' . \App\Constants\TripStatus::get_color($trip->status) }} mb-2">
                {{ \App\Constants\TripStatus::get_name($trip->status) }}
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
                  <i class="bx bx-map-pin text-muted me-2 mt-1"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('trip.delivery_location') }}</small>
                    <span class="fw-medium">
                      @if($trip->detailable->deliveryPoint)
                        {{ $trip->detailable->deliveryPoint->name ?? 'N/A' }}
                        <br>
                        <a href="{{ $trip->detailable->deliveryPoint->url }}" target="_blank" class="text-primary small">
                          <i class="bx bx-map-pin"></i> {{ __('app.view_on_map') }}
                        </a>
                      @else
                        N/A
                      @endif
                    </span>
                  </div>
                </li>
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-time text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('trip.delivery_time') }}</small>
                    <span class="fw-medium">{{ $trip->detailable->delivery_time ? $trip->detailable->delivery_time->format('Y-m-d H:i') : 'N/A' }}</span>
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
        <!-- Driver & Passenger Cards -->
        <div class="row g-3 mb-4">
          <!-- Driver Card -->
          <div class="col-md-6">
            <div class="card shadow-sm h-100">
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

          <!-- Passenger Card -->
          <div class="col-md-6">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                  <h6 class="mb-0">{{ __('trip.passenger') }}</h6>
                  @if($trip->client && $trip->client->client)
                    <a href="{{ route('passengers.show', $trip->client->client->user_id) }}" class="btn btn-sm btn-label-primary">
                      <i class="bx bx-show me-1"></i>{{ __('trip.view_profile') }}
                    </a>
                  @endif
                </div>
                @if($trip->client && $trip->client->client)
                  <div class="d-flex align-items-center">
                    <img class="img-fluid rounded-circle me-3" 
                         src="{{ $trip->client->client->avatar_url }}" 
                         height="60" 
                         width="60" 
                         alt="Passenger avatar"
                         style="object-fit: cover;" />
                    <div>
                      <h6 class="mb-1">{{ $trip->client->client->fullname }}</h6>
                      <p class="text-muted small mb-1">
                        <i class="bx bx-phone me-1"></i>{{ $trip->client->client->user->phone }}
                      </p>
                    </div>
                  </div>
                @else
                  <p class="text-muted mb-0">{{ __('app.no_data_available') }}</p>
                @endif
              </div>
            </div>
          </div>
        </div>

        <!-- Financial Information -->
        @if($trip->client)
          <div class="card shadow-sm mb-4">
            <div class="card-body">
              <div class="d-flex align-items-center justify-content-between">
                <div>
                  <div class="d-flex align-items-center mb-2">
                    <i class="bx bx-money text-success me-2" style="font-size: 1.5rem;"></i>
                    <h6 class="mb-0">{{ __('trip.amount') }}</h6>
                  </div>
                  <h3 class="mb-0 text-success fw-bold">{{ number_format($trip->client->total_fees ?? 0, 2) }} {{ __('app.DZD') }}</h3>
                  <small class="text-muted">{{ __('passenger.total_amount_spent') }}</small>
                </div>
                <div class="avatar avatar-lg bg-label-success rounded">
                  <div class="avatar-initial bg-label-success rounded">
                    <i class="bx bx-money" style="font-size: 2rem;"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endif

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

        <!-- Cargo Information -->
        @if($trip->cargos && $trip->cargos->isNotEmpty())
          <div class="card shadow-sm mb-4">
            <div class="card-header pb-3">
              <h6 class="m-0"><i class="bx bx-package me-2"></i>{{ __('trip.cargo_info') }}</h6>
            </div>
            <div class="card-body">
              <div class="row">
                @foreach($trip->cargos as $tripCargo)
                  @if($tripCargo->cargo)
                    <div class="col-12 mb-4">
                      <div class="border rounded p-3">
                        <div class="row">
                          <div class="col-md-8 mb-3">
                            <div class="d-flex align-items-center">
                              <i class="bx bx-package text-muted me-2"></i>
                              <div>
                                <small class="text-muted d-block">{{ __('cargo.description') }}</small>
                                <span class="fw-medium">{{ $tripCargo->cargo->description ?? 'N/A' }}</span>
                              </div>
                            </div>
                          </div>
                          <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center">
                              <i class="bx bx-weight text-muted me-2"></i>
                              <div>
                                <small class="text-muted d-block">{{ __('cargo.weight') }}</small>
                                <span class="fw-medium">{{ $tripCargo->cargo->weight ?? 'N/A' }} kg</span>
                              </div>
                            </div>
                          </div>
                          {{-- <div class="col-md-3 mb-3">
                            <div class="d-flex align-items-center">
                              <i class="bx bx-money text-muted me-2"></i>
                              <div>
                                <small class="text-muted d-block">{{ __('cargo.fees') }}</small>
                                <span class="fw-medium">{{ number_format($tripCargo->total_fees ?? 0, 2) }} {{ __('app.DZD') }}</span>
                              </div>
                            </div>
                          </div> --}}

                          <!-- Cargo Images -->
                          @php
                            $cargoImages = $tripCargo->cargo->getMedia('images');
                          @endphp

                          @if($cargoImages->isNotEmpty())
                            <div class="col-12 mt-3">
                              <hr class="my-3">
                              <div id="cargoImagesGallery{{ $tripCargo->cargo->id }}" class="d-none">
                                @foreach($cargoImages as $image)
                                  <img src="{{ $image->getUrl() }}" alt="{{ __('trip.cargo_image') }}" data-title="{{ __('trip.cargo_image') }} {{ $loop->iteration }}">
                                @endforeach
                              </div>
                              <div class="d-flex gap-2 flex-wrap">
                                @foreach($cargoImages as $image)
                                  <button type="button" class="btn btn-outline-primary btn-sm view-cargo-image" data-cargo-id="{{ $tripCargo->cargo->id }}" data-index="{{ $loop->index }}">
                                    <i class="bx bx-image me-1"></i>{{ __('trip.cargo_image') }} {{ $loop->iteration }}
                                  </button>
                                @endforeach
                              </div>
                            </div>
                          @endif
                        </div>
                      </div>
                    </div>
                  @endif
                @endforeach
              </div>
            </div>
          </div>
        @endif

        <!-- Reviews Section -->
        <div class="card shadow-sm">
          <div class="card-header">
            <h5 class="mb-0">
              <i class="bx bx-star me-1"></i>{{ __('trip.reviews') }}
            </h5>
          </div>
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
      @if($trip->cargos && $trip->cargos->isNotEmpty())
        @foreach($trip->cargos as $tripCargo)
          @if($tripCargo->cargo)
            @php
              $cargoImages = $tripCargo->cargo->getMedia('images');
            @endphp
            @if($cargoImages->isNotEmpty())
              const cargoGalleryElement{{ $tripCargo->cargo->id }} = document.getElementById('cargoImagesGallery{{ $tripCargo->cargo->id }}');
              if (cargoGalleryElement{{ $tripCargo->cargo->id }}) {
                const cargoGallery{{ $tripCargo->cargo->id }} = new Viewer(cargoGalleryElement{{ $tripCargo->cargo->id }}, {
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

                // Cargo image buttons for cargo {{ $tripCargo->cargo->id }}
                document.querySelectorAll('.view-cargo-image[data-cargo-id="{{ $tripCargo->cargo->id }}"]').forEach(button => {
                  button.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    cargoGallery{{ $tripCargo->cargo->id }}.view(index);
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
