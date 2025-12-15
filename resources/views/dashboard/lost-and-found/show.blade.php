@extends('layouts/contentNavbarLayout')

@section('title', __('lost-and-founds') . ' #' . $lostAndFound->id)

@section('content')
  <div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold mb-1">{{ __('lost-and-founds') }} #{{ $lostAndFound->id }}</h4>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('lost-and-founds.index') }}">{{ __('lost-and-founds') }}</a></li>
            <li class="breadcrumb-item active">#{{ $lostAndFound->id }}</li>
          </ol>
        </nav>
      </div>
      <div class="d-flex gap-2">
        @can(\App\Support\Enum\Permissions::LOST_AND_FOUND_CHANGE_STATUS)
          @if($lostAndFound->status === \App\Constants\LostAndFoundStatus::FOUND)
            <button type="button" class="btn btn-label-warning" data-bs-toggle="modal" data-bs-target="#mark-as-returned-modal" data-id="{{ $lostAndFound->id }}">
              <i class="bx bx-check-circle me-1"></i>{{ __('app.mark_as_returned') }}
            </button>
          @endif
        @endcan
        @can(\App\Support\Enum\Permissions::LOST_AND_FOUND_UPDATE)
          <a href="{{ route('lost-and-founds.edit', $lostAndFound->id) }}" class="btn btn-label-primary">
            <i class="bx bx-edit me-1"></i>{{ __('Edit') }}
          </a>
        @endcan
        <a href="{{ url()->previous() }}" class="btn btn-label-secondary">
          <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
        </a>
      </div>
    </div>

    <div class="row">
      <!-- Left Column - Lost & Found Card -->
      <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <!-- Status Badge -->
            <div class="text-center mb-4">
              <div class="mb-3">
                <div class="avatar avatar-xl bg-label-info rounded-circle mx-auto">
                    <div class="avatar-initial bg-label-info rounded-circle">
                        <i class="bx bx-search-alt" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
              </div>
              <div class="d-flex align-items-center justify-content-center gap-1">
                <h5 class="mb-2">{{ __('lost-and-founds') }}</h5>
              </div>
              <span class="{{ 'badge bg-label-' . \App\Constants\LostAndFoundStatus::get_color($lostAndFound->status) }}">
                {{ \App\Constants\LostAndFoundStatus::get_name($lostAndFound->status) }}
              </span>
            </div>

            <hr class="my-4">

            <!-- Item Information -->
            <div class="text-start">
              <h6 class="text-uppercase text-muted mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                {{ __('lost_and_found.details') }}
              </h6>
              <ul class="list-unstyled mb-0">
                <li class="mb-3 d-flex align-items-center">
                  <i class="bx bx-calendar text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('lost_and_found.created_at') }}</small>
                    <span class="fw-medium">{{ $lostAndFound->created_at->format('Y-m-d H:i') }}</span>
                  </div>
                </li>
                <li class="mb-0 d-flex align-items-center">
                  <i class="bx bx-time-five text-muted me-2"></i>
                  <div class="flex-grow-1">
                    <small class="text-muted d-block">{{ __('lost_and_found.updated_at') }}</small>
                    <span class="fw-medium">{{ $lostAndFound->updated_at->format('Y-m-d H:i') }}</span>
                  </div>
                </li>
              </ul>
            </div>


          </div>
        </div>
      </div>

      <!-- Right Column - Passenger Info -->
      <div class="col-xl-8 col-lg-7">
        <!-- Passenger Card -->
        <div class="row g-3 mb-4">
          <div class="col-12">
            <div class="card shadow-sm">
              <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                  <h6 class="mb-0">{{ __('lost_and_found.passenger_information') }}</h6>
                  @if($lostAndFound->passenger)
                    <a href="{{ route('passengers.show', $lostAndFound->passenger->user_id) }}" class="btn btn-sm btn-label-primary">
                      <i class="bx bx-show me-1"></i>{{ __('lost_and_found.view_profile') }}
                    </a>
                  @endif
                </div>
                @if($lostAndFound->passenger)
                  <div class="d-flex align-items-center">
                    <img class="img-fluid rounded-circle me-3" 
                         src="{{ $lostAndFound->passenger->avatar_url }}" 
                         height="60" 
                         width="60" 
                         alt="Passenger avatar"
                         style="object-fit: cover;" />
                    <div>
                      <h6 class="mb-1">{{ $lostAndFound->passenger->fullname }}</h6>
                      <p class="text-muted small mb-1">
                        <i class="bx bx-phone me-1"></i>{{ $lostAndFound->passenger->user->phone }}
                      </p>
                      @if($lostAndFound->passenger->user->email)
                        <p class="text-muted small mb-0">
                          <i class="bx bx-envelope me-1"></i>{{ $lostAndFound->passenger->user->email }}
                        </p>
                      @endif
                    </div>
                  </div>
                @else
                  <p class="text-muted mb-0">{{ __('app.no_data_available') }}</p>
                @endif
              </div>
            </div>
          </div>
        </div>

        <!-- Item Details Card -->
        <div class="card shadow-sm mb-4">
          <div class="card-header pb-3">
            <h6 class="m-0"><i class="bx bx-info-circle me-2"></i>{{ __('lost_and_found.details') }}</h6>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <h6 class="text-muted mb-2">{{ __('lost_and_found.description') }}</h6>
              <p class="mb-0">{{ $lostAndFound->description }}</p>
            </div>

            @if($lostAndFound->hasMedia(\App\Models\LostAndFound::IMAGE))
              @php
                $image = $lostAndFound->getFirstMedia(\App\Models\LostAndFound::IMAGE);
              @endphp
              @if($image)
                <hr class="my-3">
                <div id="lostAndFoundImageGallery" class="d-none">
                  <img src="{{ $image->getUrl() }}" alt="{{ __('lost_and_found.item_image') }}" data-title="{{ __('lost_and_found.item_image') }}">
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm" id="viewImageBtn">
                  <i class="bx bx-image me-1"></i>{{ __('lost_and_found.view_image') }}
                </button>
              @endif
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal for Lost and Found Actions -->
  <x-modal.confirmation
    id="mark-as-returned-modal"
    title="{{ __('lost_and_found.mark_as_returned') }}"
    action="{{ route('lost-and-founds.status.update') }}"
    method="POST"
    inputs='
    <input type="hidden" name="id" value="">
    <input type="hidden" name="status" value="{{ \App\Constants\LostAndFoundStatus::RETURNED }}">
  '
    theme="success"
    confirmationTitle="{{ __('lost_and_found.mark_as_returned_confirmation') }}"
    confirmationText="{{ __('lost_and_found.mark_as_returned_notice') }}"
    checkboxLabel="{{ __('lost_and_found.mark_as_returned_checkbox') }}"
    submitLabel="{{ __('app.submit') }}"
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
      @if($lostAndFound->hasMedia(\App\Models\LostAndFound::IMAGE))
        // Initialize Image Gallery
        const galleryElement = document.getElementById('lostAndFoundImageGallery');
        if (galleryElement) {
          const imageGallery = new Viewer(galleryElement, {
            inline: false,
            title: function(image) {
              return image.alt;
            },
            toolbar: {
              zoomIn: 1,
              zoomOut: 1,
              oneToOne: 1,
              reset: 1,
              prev: 0,
              play: 0,
              next: 0,
              rotateLeft: 1,
              rotateRight: 1,
              flipHorizontal: 1,
              flipVertical: 1,
            },
            navbar: false,
            tooltip: true,
            movable: true,
            zoomable: true,
            rotatable: true,
            scalable: true,
            transition: true,
            fullscreen: true,
            keyboard: true,
          });

          // View image button
          const viewImageBtn = document.getElementById('viewImageBtn');
          if (viewImageBtn) {
            viewImageBtn.addEventListener('click', function() {
              imageGallery.show();
            });
          }
        }
      @endif

      // Handle mark as returned modal
      $(document).on('click', '[data-bs-target="#mark-as-returned-modal"]', function() {
        const id = $(this).data('id');
        $('#mark-as-returned-modal').find('input[name="id"]').val(id);
      });
    });
  </script>
@endsection
