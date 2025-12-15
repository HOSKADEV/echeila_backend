@extends('layouts/contentNavbarLayout')

@section('title', __('app.edit-color'))

@section('content')
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">{{ __('app.edit-color') }}</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
          <li class="breadcrumb-item"><a href="{{ route('colors.index') }}">{{ __('app.colors') }}</a></li>
          <li class="breadcrumb-item active">{{ __('app.edit') }}</li>
        </ol>
      </nav>
    </div>
    <div>
      <a href="{{ url()->previous() }}" class="btn btn-label-secondary me-2">
        <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
      </a>
      <button type="submit" form="color-form" class="btn btn-primary">
        <i class="bx bx-check me-1"></i>{{ __('app.send') }}
      </button>
    </div>
  </div>

  <form action="{{ route('colors.update', $color->id) }}" method="POST" id="color-form">
    @csrf
    @method('PATCH')
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
                  value="{{ old("name.{$locale}", $color->translate('name', $locale) ?? '') }}" 
                  required>
                @error("name.{$locale}")
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
            @endforeach

            <div class="mb-3 col-md-6">
              <label for="code" class="form-label">{{ __('app.color') }}</label>
              <div class="input-group">
                <input 
                  type="color" 
                  name="code" 
                  class="form-control form-control-color @error('code') is-invalid @enderror" 
                  id="code"
                  value="{{ old('code', $color->code ?? '#000000') }}"
                  required>
                <input 
                  type="text" 
                  class="form-control @error('code') is-invalid @enderror" 
                  id="code-text"
                  placeholder="#000000"
                  value="{{ old('code', $color->code ?? '#000000') }}"
                  readonly>
                @error('code')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
              <small class="form-text text-muted">{{ __('app.color_code_format') }}</small>
            </div>

          </div>
        </div>
      </div>
    </div>
  </form>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const colorInput = document.getElementById('code');
      const colorText = document.getElementById('code-text');
      const colorInputField = document.querySelector('input[name="code"]');

      if (colorInput) {
        colorInput.addEventListener('input', function() {
          colorText.value = this.value;
          colorInputField.value = this.value;
        });
      }
    });
  </script>
@endsection
