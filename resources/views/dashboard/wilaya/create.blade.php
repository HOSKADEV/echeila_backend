@extends('layouts/contentNavbarLayout')

@section('title', __('app.create-wilaya'))

@section('vendor-style')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
  #map {
    width: 100%;
    aspect-ratio: 1;
    border-radius: 0.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    cursor: crosshair;
  }
  .leaflet-container {
    font-family: inherit;
  }
  .selected-marker-pulse {
    animation: pulse 2s infinite;
  }
  @keyframes pulse {
    0% {
      box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7);
    }
    70% {
      box-shadow: 0 0 0 10px rgba(102, 126, 234, 0);
    }
    100% {
      box-shadow: 0 0 0 0 rgba(102, 126, 234, 0);
    }
  }
  .name-input-group {
    transition: all 0.3s ease;
  }
  .name-input-group:hover {
    transform: translateY(-2px);
  }
  .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  }
  .coordinate-input {
    font-family: 'Courier New', monospace;
  }
</style>
@endsection

@section('content')
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">{{ __('app.create-wilaya') }}</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
          <li class="breadcrumb-item"><a href="{{ route('wilayas.index') }}">{{ __('app.wilayas') }}</a></li>
          <li class="breadcrumb-item active">{{ __('app.add') }}</li>
        </ol>
      </nav>
    </div>
    <div>
      <a href="{{ url()->previous() }}" class="btn btn-label-secondary me-2">
        <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
      </a>
      <button type="submit" form="wilaya-form" class="btn btn-primary">
        <i class="bx bx-check me-1"></i>{{ __('app.send') }}
      </button>
    </div>
  </div>

  <form action="{{ route('wilayas.store') }}" method="POST" id="wilaya-form">
    @csrf
    <div class="row">
      <!-- Left Column -->
      <div class="col-xl-7 col-lg-7 mb-4">
        <div class="d-flex flex-column h-100">
          <!-- Name Inputs -->
          <div class="card mb-3 flex-grow-1">
            <div class="card-body">
              @php
                $locales = config('app.available_locales', ['ar', 'en', 'fr']);
                $localeLabels = [
                  'ar' => 'العربية',
                  'en' => 'English',
                  'fr' => 'Français',
                ];
                $localeIcons = [
                  'ar' => 'bx-font',
                  'en' => 'bx-globe',
                  'fr' => 'bx-book',
                ];
              @endphp

              @foreach($locales as $locale)
              <div class="mb-3 col-md-auto">
                <label for="name_{{ $locale }}" class="form-label">{{ __('app.name') }} ({{ $localeLabels[$locale] ?? ucfirst($locale) }})</label>
                <input 
                  type="text" 
                  name="name[{{ $locale }}]" 
                  class="form-control @error("name.{$locale}") is-invalid @enderror" 
                  id="name_{{ $locale }}"
                  placeholder="{{ __('app.name') }}"
                  value="{{ old("name.{$locale}") }}" 
                  required>
                @error("name.{$locale}")
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
            @endforeach
            </div>
          </div>

          <!-- Coordinates Input -->
          <div class="card flex-shrink-0">
            <div class="card-body">
              <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                  <label for="latitude" class="form-label">
                    <i class='bx bx-target-lock me-1'></i>{{ __('app.latitude') }}
                  </label>
                  <input 
                    type="number" 
                    step="any" 
                    name="latitude" 
                    id="latitude" 
                    class="form-control coordinate-input @error('latitude') is-invalid @enderror" 
                    placeholder="36.7538"
                    value="{{ old('latitude') }}"
                    required>
                  @error('latitude')
                    <span class="invalid-feedback">{{ $message }}</span>
                  @enderror
                </div>
                <div class="col-md-6">
                  <label for="longitude" class="form-label">
                    <i class='bx bx-target-lock me-1'></i>{{ __('app.longitude') }}
                  </label>
                  <input 
                    type="number" 
                    step="any" 
                    name="longitude" 
                    id="longitude" 
                    class="form-control coordinate-input @error('longitude') is-invalid @enderror" 
                    placeholder="3.0588"
                    value="{{ old('longitude') }}"
                    required>
                  @error('longitude')
                    <span class="invalid-feedback">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="mt-2">
                <small class="text-muted">
                  <i class='bx bx-info-circle me-1'></i>{{ __('app.click_map_or_enter_coordinates') ?? 'Click on the map or enter coordinates manually' }}
                </small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column - Map Section -->
      <div class="col-xl-5 col-lg-5 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <div id="map"></div>
          </div>
        </div>
      </div>
    </div>
  </form>
@endsection

@section('vendor-script')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endsection

@section('page-script')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Default center for Algeria (Algiers)
    const defaultLat = 36.7538;
    const defaultLng = 3.0588;
    
    // Get input fields
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    
    // Get old values if they exist
    const oldLat = parseFloat(latInput.value) || defaultLat;
    const oldLng = parseFloat(lngInput.value) || defaultLng;
    
    // Initialize map
    const map = L.map('map').setView([oldLat, oldLng], 6);
    
    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
      maxZoom: 19
    }).addTo(map);
    
    // Custom marker icon
    const customIcon = L.divIcon({
      className: 'custom-marker',
      html: '<div style="background: #667eea; width: 30px; height: 30px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.3);"></div>',
      iconSize: [30, 30],
      iconAnchor: [15, 15]
    });
    
    // Create marker
    let marker = null;
    let isUpdatingFromInput = false;
    
    // If there are old values, add marker
    if (latInput.value && lngInput.value) {
      marker = L.marker([oldLat, oldLng], {
        draggable: true,
        icon: customIcon
      }).addTo(map);
      
      marker.on('dragend', function(e) {
        const position = marker.getLatLng();
        updateCoordinates(position.lat, position.lng, false);
      });
    }
    
    // Add click event to map
    map.on('click', function(e) {
      const lat = e.latlng.lat;
      const lng = e.latlng.lng;
      
      if (marker) {
        map.removeLayer(marker);
      }
      
      marker = L.marker([lat, lng], {
        draggable: true,
        icon: customIcon
      }).addTo(map);
      
      updateCoordinates(lat, lng, false);
      
      // Smooth zoom to clicked location
      if (map.getZoom() < 12) {
        map.flyTo([lat, lng], 12, {
          animate: true,
          duration: 1
        });
      }
      
      marker.on('dragend', function(e) {
        const position = marker.getLatLng();
        updateCoordinates(position.lat, position.lng, false);
      });
    });
    
    // Update coordinates in input fields
    function updateCoordinates(lat, lng, fromInput = false) {
      if (!fromInput) {
        latInput.value = lat.toFixed(8);
        lngInput.value = lng.toFixed(8);
      }
    }
    
    // Update map when input changes
    function updateMapFromInputs() {
      const lat = parseFloat(latInput.value);
      const lng = parseFloat(lngInput.value);
      
      if (isNaN(lat) || isNaN(lng)) return;
      
      // Validate coordinates
      if (lat < -90 || lat > 90 || lng < -180 || lng > 180) {
        return;
      }
      
      isUpdatingFromInput = true;
      
      if (marker) {
        marker.setLatLng([lat, lng]);
      } else {
        marker = L.marker([lat, lng], {
          draggable: true,
          icon: customIcon
        }).addTo(map);
        
        marker.on('dragend', function(e) {
          const position = marker.getLatLng();
          updateCoordinates(position.lat, position.lng, false);
        });
      }
      
      map.setView([lat, lng], Math.max(map.getZoom(), 12));
      
      setTimeout(() => {
        isUpdatingFromInput = false;
      }, 100);
    }
    
    // Add input event listeners
    latInput.addEventListener('input', updateMapFromInputs);
    lngInput.addEventListener('input', updateMapFromInputs);
    
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
      const lat = parseFloat(latInput.value);
      const lng = parseFloat(lngInput.value);
      
      if (!lat || !lng || isNaN(lat) || isNaN(lng)) {
        e.preventDefault();
        alert('{{ __("app.please_select_location") ?? "Please select a location on the map" }}');
        return false;
      }
      
      if (lat < -90 || lat > 90 || lng < -180 || lng > 180) {
        e.preventDefault();
        alert('{{ __("app.invalid_coordinates") ?? "Invalid coordinates. Latitude must be between -90 and 90, Longitude between -180 and 180" }}');
        return false;
      }
    });
  });
</script>
@endsection