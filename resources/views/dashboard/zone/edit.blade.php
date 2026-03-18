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
      <div class="col-xl-5 col-lg-5 mb-4">
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

            <div class="mb-3" id="circle-radius-group">
              <label for="radiusKm" class="form-label">{{ __('zone.radiusKm') }}</label>
              <input type="number" name="radiusKm" id="radiusKm" step="0.1" min="0"
                class="form-control @error('radiusKm') is-invalid @enderror"
                value="{{ old('radiusKm', $zone['radiusKm'] ?? '') }}">
              @error('radiusKm')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>

            <div class="row mb-3" id="center-group">
              <div class="col-md-6">
                <label for="lat" class="form-label">{{ __('app.latitude') }} <span class="text-danger">*</span></label>
                <input type="number" name="lat" id="lat" step="any"
                  class="form-control @error('lat') is-invalid @enderror"
                  value="{{ old('lat', $zone['center']['lat'] ?? '') }}" required>
                @error('lat')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="lng" class="form-label">{{ __('app.longitude') }} <span class="text-danger">*</span></label>
                <input type="number" name="lng" id="lng" step="any"
                  class="form-control @error('lng') is-invalid @enderror"
                  value="{{ old('lng', $zone['center']['lng'] ?? '') }}" required>
                @error('lng')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
            </div>

            <div class="mb-3 d-none" id="polygon-points-group">
              <label class="form-label">{{ __('zone.corners') }}</label>
              <input type="hidden" name="points_json" id="points_json" value='{{ old('points_json', json_encode($zone['corners'] ?? [])) }}'>
              <div id="polygon-points-list" class="small text-muted"></div>
              @error('points_json')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
              <small class="text-muted">{{ __('zone.polygon_hint') }}</small>
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
      <div class="col-xl-7 col-lg-7 mb-4">
        <div class="card h-100">
          <div class="card-header">
            <h6 class="mb-0">{{ __('zone.map') }}</h6>
            <small class="text-muted" id="map-hint">{{ __('zone.click_map_hint') }}</small>
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
  const typeField = document.getElementById('type');
  const circleRadiusGroup = document.getElementById('circle-radius-group');
  const centerGroup = document.getElementById('center-group');
  const polygonPointsGroup = document.getElementById('polygon-points-group');
  const pointsInput = document.getElementById('points_json');
  const pointsList = document.getElementById('polygon-points-list');
  const latInput = document.getElementById('lat');
  const lngInput = document.getElementById('lng');
  const radiusInput = document.getElementById('radiusKm');
  const mapHint = document.getElementById('map-hint');

  const initialLat = parseFloat('{{ old('lat', $zone['center']['lat'] ?? 28.0) }}') || 28.0;
  const initialLng = parseFloat('{{ old('lng', $zone['center']['lng'] ?? 2.0) }}') || 2.0;

  const map = L.map('map').setView([initialLat, initialLng], 8);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);

  let centerMarker = null;
  let circleLayer = null;
  let polygonLayer = null;
  let polygonMarkers = [];
  let polygonPoints = [];
  let centerSelected = true;

  function normalizePoints(points) {
    if (!Array.isArray(points)) {
      return [];
    }

    return points
      .filter(function(point) {
        return point && !isNaN(parseFloat(point.lat)) && !isNaN(parseFloat(point.lng));
      })
      .map(function(point) {
        return {
          lat: parseFloat(point.lat),
          lng: parseFloat(point.lng)
        };
      });
  }

  function renderPolygonPoints() {
    pointsInput.value = JSON.stringify(polygonPoints);

    polygonMarkers.forEach(function(m) {
      map.removeLayer(m);
    });
    polygonMarkers = [];

    if (polygonLayer) {
      map.removeLayer(polygonLayer);
      polygonLayer = null;
    }

    if (polygonPoints.length === 0) {
      pointsList.innerHTML = '<span class="text-muted">No corners selected.</span>';
      return;
    }

    polygonPoints.forEach(function(point, index) {
      const pMarker = L.circleMarker([point.lat, point.lng], {
        radius: 5,
        color: '#0d6efd',
        fillColor: '#0d6efd',
        fillOpacity: 0.8
      }).addTo(map);
      pMarker.bindTooltip('#' + (index + 1));
      polygonMarkers.push(pMarker);
    });

    if (polygonPoints.length >= 2) {
      polygonLayer = L.polygon(polygonPoints.map(function(p) {
        return [p.lat, p.lng];
      }), {
        color: '#0d6efd',
        fillOpacity: 0.15
      }).addTo(map);
    }

    if (typeField.value === 'polygon') {
      if (polygonPoints.length === 1) {
        map.setView([polygonPoints[0].lat, polygonPoints[0].lng], 8);
      } else if (polygonLayer) {
        map.fitBounds(polygonLayer.getBounds(), {
          padding: [30, 30]
        });
      }
    }

    pointsList.innerHTML = polygonPoints.map(function(point, index) {
      return '<div>#' + (index + 1) + ': ' + point.lat.toFixed(6) + ', ' + point.lng.toFixed(6) + ' <a href="javascript:void(0);" data-remove-index="' + index + '">remove</a></div>';
    }).join('');
  }

  function setCenterMarker(lat, lng) {
    if (centerMarker) map.removeLayer(centerMarker);
    centerMarker = L.marker([lat, lng], {
      icon: L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
      })
    }).addTo(map);
    latInput.value = lat.toFixed(6);
    lngInput.value = lng.toFixed(6);
    centerSelected = true;
    renderCircleOverlay();
  }

  function renderCircleOverlay() {
    if (circleLayer) {
      map.removeLayer(circleLayer);
      circleLayer = null;
    }

    if (typeField.value !== 'circle' || !centerSelected) {
      return;
    }

    const lat = parseFloat(latInput.value);
    const lng = parseFloat(lngInput.value);
    const radiusKm = parseFloat(radiusInput.value);

    if (isNaN(lat) || isNaN(lng) || isNaN(radiusKm) || radiusKm <= 0) {
      return;
    }

    circleLayer = L.circle([lat, lng], {
      radius: radiusKm * 1000,
      color: '#0d6efd',
      fillColor: '#0d6efd',
      fillOpacity: 0.12
    }).addTo(map);
  }

  function applyTypeState() {
    const currentType = typeField.value;
    const isCircle = currentType === 'circle';
    const isPolygon = currentType === 'polygon';

    circleRadiusGroup.classList.toggle('d-none', !isCircle);
    polygonPointsGroup.classList.toggle('d-none', !isPolygon);

    radiusInput.required = isCircle;
    pointsInput.required = isPolygon;

    if (isPolygon) {
      mapHint.textContent = '{{ __('zone.polygon_click_map_hint') }}';
      renderPolygonPoints();
    } else if (isCircle) {
      mapHint.textContent = 'Adjust the radius in the left panel or click on map to change center (red dot)';
      renderCircleOverlay();
    }
  }

  map.on('click', function(e) {
    if (typeField.value === 'polygon') {
      polygonPoints.push({
        lat: parseFloat(e.latlng.lat.toFixed(6)),
        lng: parseFloat(e.latlng.lng.toFixed(6))
      });
      renderPolygonPoints();
      return;
    } else if (typeField.value === 'circle') {
      setCenterMarker(e.latlng.lat, e.latlng.lng);
      map.setView([e.latlng.lat, e.latlng.lng], 8);
    }
  });

  // Initialize center marker with existing data
  const initialCenterLat = parseFloat('{{ old('lat', $zone['center']['lat'] ?? '') }}');
  const initialCenterLng = parseFloat('{{ old('lng', $zone['center']['lng'] ?? '') }}');
  if (!isNaN(initialCenterLat) && !isNaN(initialCenterLng)) {
    setCenterMarker(initialCenterLat, initialCenterLng);
  }

  polygonPoints = normalizePoints(JSON.parse(pointsInput.value || '[]'));
  renderPolygonPoints();

  // Sync lat/lng inputs → move center marker
  ['lat', 'lng'].forEach(function(id) {
    document.getElementById(id).addEventListener('change', function() {
      const lat = parseFloat(latInput.value);
      const lng = parseFloat(lngInput.value);
      if (!isNaN(lat) && !isNaN(lng)) {
        setCenterMarker(lat, lng);
        map.setView([lat, lng], 8);
      } else {
        renderCircleOverlay();
      }
    });
  });

  radiusInput.addEventListener('input', renderCircleOverlay);
  radiusInput.addEventListener('change', renderCircleOverlay);

  pointsList.addEventListener('click', function(event) {
    const removeIndex = event.target.getAttribute('data-remove-index');
    if (removeIndex === null) {
      return;
    }

    polygonPoints.splice(parseInt(removeIndex, 10), 1);
    renderPolygonPoints();
  });

  typeField.addEventListener('change', applyTypeState);
  applyTypeState();
  renderCircleOverlay();
</script>
@endsection
