@extends('layouts/contentNavbarLayout')

@section('title', __('app.create-brand'))

@section('content')
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">{{ __('app.create-brand') }}</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
          <li class="breadcrumb-item"><a href="{{ route('brands.index') }}">{{ __('app.brands') }}</a></li>
          <li class="breadcrumb-item active">{{ __('app.add') }}</li>
        </ol>
      </nav>
    </div>
    <div>
      <a href="{{ url()->previous() }}" class="btn btn-label-secondary me-2">
        <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
      </a>
      <button type="submit" form="brand-form" class="btn btn-primary">
        <i class="bx bx-check me-1"></i>{{ __('app.send') }}
      </button>
    </div>
  </div>

  <form action="{{ route('brands.store') }}" method="POST" id="brand-form">
    @csrf
    <div class="row">
      <div class="col-xl">
        <div class="card mb-4">
          <div class="row card-body">
            @php
              $locales = config('app.available_locales', ['ar', 'en', 'fr']);
              $localeLabels = [
                'ar' => 'Arabic',
                'en' => 'English',
                'fr' => 'French',
              ];
            @endphp

            @foreach($locales as $locale)
              <div class="mb-3 col-md-6">
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
      </div>
    </div>
  </form>
@endsection
