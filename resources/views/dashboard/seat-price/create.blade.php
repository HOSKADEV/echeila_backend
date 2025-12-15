@extends('layouts/contentNavbarLayout')

@section('title', __('app.create-seat-price'))

@section('content')
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">{{ __('app.create-seat-price') }}</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
          <li class="breadcrumb-item"><a href="{{ route('seat-prices.index') }}">{{ __('app.seat-prices') }}</a></li>
          <li class="breadcrumb-item active">{{ __('app.add') }}</li>
        </ol>
      </nav>
    </div>
    <div>
      <a href="{{ url()->previous() }}" class="btn btn-label-secondary me-2">
        <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
      </a>
      <button type="submit" form="seat-price-form" class="btn btn-primary">
        <i class="bx bx-check me-1"></i>{{ __('app.send') }}
      </button>
    </div>
  </div>

  <form action="{{ route('seat-prices.store') }}" method="POST" id="seat-price-form">
    @csrf
    <div class="row">
      <div class="col-xl">
        <div class="card mb-4">
          <div class="row card-body">
            <div class="mb-3 col-md-6">
              <label for="starting_wilaya_id" class="form-label">{{ __('app.starting_wilaya') }}</label>
              <select 
                name="starting_wilaya_id" 
                class="form-select @error('starting_wilaya_id') is-invalid @enderror" 
                id="starting_wilaya_id"
                required>
                <option value="">{{ __('app.select_option') }}</option>
                @foreach($wilayas as $wilaya)
                  <option value="{{ $wilaya->id }}" {{ old('starting_wilaya_id') == $wilaya->id ? 'selected' : '' }}>
                    {{ $wilaya->translate('name', app()->getLocale()) }}
                  </option>
                @endforeach
              </select>
              @error('starting_wilaya_id')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>

            <div class="mb-3 col-md-6">
              <label for="arrival_wilaya_id" class="form-label">{{ __('app.arrival_wilaya') }}</label>
              <select 
                name="arrival_wilaya_id" 
                class="form-select @error('arrival_wilaya_id') is-invalid @enderror" 
                id="arrival_wilaya_id"
                required>
                <option value="">{{ __('app.select_option') }}</option>
                @foreach($wilayas as $wilaya)
                  <option value="{{ $wilaya->id }}" {{ old('arrival_wilaya_id') == $wilaya->id ? 'selected' : '' }}>
                    {{ $wilaya->translate('name', app()->getLocale()) }}
                  </option>
                @endforeach
              </select>
              @error('arrival_wilaya_id')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>

            <div class="mb-3 col-md-12">
              <label for="default_seat_price" class="form-label">{{ __('app.default_seat_price') }}</label>
              <div class="input-group">
                <input 
                  type="number" 
                  name="default_seat_price" 
                  class="form-control @error('default_seat_price') is-invalid @enderror" 
                  id="default_seat_price"
                  placeholder="{{ __('app.default_seat_price') }}"
                  value="{{ old('default_seat_price') }}" 
                  step="0.01"
                  min="0"
                  max="999999.99"
                  required>
                <span class="input-group-text">{{ __('app.DZD') }}</span>
                @error('default_seat_price')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
@endsection
