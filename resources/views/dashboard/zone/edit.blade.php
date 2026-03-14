@extends('layouts/contentNavbarLayout')

@section('title', __('app.edit-zone'))

@section('vendor-style')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
  #map {
    width: 100%;
    height: 350px;
    border-radius: 0.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    cursor: crosshair;
  }
</style>
@endsection

@section('content')
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">{{ __('app.edit-zone') }}</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
          <li class="breadcrumb-item"><a href="{{ route('zones.index') }}">{{ __('zone.zones') }}</a></li>
          <li class="breadcrumb-item active">{{ __('app.edit') }}</li>
        </ol>
      </nav>
    </div>
    <div>
      <a href="{{ url()->previous() }}" class="btn btn-label-secondary me-2">
        <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
      </a>
      <button type="submit" form="zone-form" class="btn btn-primary">
        <i class="bx bx-check me-1"></i>{{ __('app.send') }}
      </button>
    </div>
  </div>

  <form action="{{ route('zones.update', $zone['id']) }}" method="POST" id="zone-form">
    @csrf
    @method('PATCH')
    <div class="row">
      <!-- Left Column -->
      <div class="col-xl-7 col-lg-7 mb-4">
        <div class="card h-100">
          <div class="card-body">

            <div class="mb-3">
              <label for="zoneId" class="form-label">{{ __('zone.zoneId') }}</label>
              <input type="text" id="zoneId"
                class="form-control"
                value="{{ $zone['zoneId'] ?? $zone['id'] }}" disabled>
              <small class="text-muted">{{ __('zone.zoneId_readonly') }}</small>
            </div>

            <div class="mb-3">
              <label for="name" class="form-label">{{ __('zone.name') }}</label>
              <input type="text" name="name" id="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $zone['name'] ?? '') }}"
                placeholder="{{ __('zone.name') }}" required>
              @error('name')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>

            <div class="mb-3">
              <label for="type" class="form-label">{{ __('zone.type') }}</label>
              <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                <option value="">-- {{ __('app.choose') }} --</option>
                <option value="circle" {{ old('type', $zone['type'] ?? '') === 'circle' ? 'selected' : '' }}>Circle</option>
                <option value="polygon" {{ old('type', $zone['type'] ?? '') === 'polygon' ? 'selected' : '' }}>Polygon</option>
              </select>
              @error('type')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>

            <div class="mb-3">
              <label for="radiusKm" class="form-label">{{ __('zone.radiusKm') }}</label>
              <input type="number" name="radiusKm" id="radiusKm" step="0.1" min="0"
                class="form-control @error('radiusKm') is-invalid @enderror"
                value="{{ old('radiusKm', $zone['radiusKm'] ?? '') }}" required>
              @error('radiusKm')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="lat" class="form-label">{{ __('app.latitude') }}</label>
                <input type="number" name="lat" id="lat" step="any"
                  class="form-control @error('lat') is-invalid @enderror"
                  value="{{ old('lat', $zone['center']['lat'] ?? '') }}" required>
                @error('lat')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="lng" class="form-label">{{ __('app.longitude') }}</label>
                <input type="number" name="lng" id="lng" step="any"
                  class="form-control @error('lng') is-invalid @enderror"
                  value="{{ old('lng', $zone['center']['lng'] ?? '') }}" required>
                @error('lng')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
            </div>

            <div class="mb-3">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="isActive" id="isActive" value="1"
                  {{ old('isActive', $zone['isActive'] ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="isActive">{{ __('zone.isActive') }}</label>
              </div>
            </div>

          </div>
        </div>
      </div>

      <!-- Right Column: Map -->
      <div class="col-xl-5 col-lg-5 mb-4">
        <div class="card h-100">
          <div class="card-header">
            <h6 class="mb-0">{{ __('zone.center') }}</h6>
            <small class="text-muted">{{ __('zone.click_map_hint') }}</small>
          </div>
          <div class="card-body p-2">
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
  const initialLat = parseFloat('{{ $zone['center']['lat'] ?? 28.0 }}') || 28.0;
  const initialLng = parseFloat('{{ $zone['center']['lng'] ?? 2.0 }}') || 2.0;

  const map = L.map('map').setView([initialLat, initialLng], 8);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  let marker = L.marker([initialLat, initialLng]).addTo(map);

  function setMarker(lat, lng) {
    marker.setLatLng([lat, lng]);
    document.getElementById('lat').value = lat.toFixed(6);
    document.getElementById('lng').value = lng.toFixed(6);
  }

  map.on('click', function(e) {
    setMarker(e.latlng.lat, e.latlng.lng);
  });

  // Sync lat/lng inputs → move marker
  ['lat', 'lng'].forEach(function(id) {
    document.getElementById(id).addEventListener('change', function() {
      const lat = parseFloat(document.getElementById('lat').value);
      const lng = parseFloat(document.getElementById('lng').value);
      if (!isNaN(lat) && !isNaN(lng)) {
        setMarker(lat, lng);
        map.setView([lat, lng], 8);
      }
    });
  });
</script>
@endsection
