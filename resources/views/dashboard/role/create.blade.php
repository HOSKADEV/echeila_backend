@extends('layouts/contentNavbarLayout')

@section('title', __('app.create-role'))

@section('content')
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">{{ __('app.create-role') }}</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
          <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">{{ __('app.roles') }}</a></li>
          <li class="breadcrumb-item active">{{ __('app.add') }}</li>
        </ol>
      </nav>
    </div>
    <div>
      <a href="{{ url()->previous() }}" class="btn btn-label-secondary me-2">
        <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
      </a>
      <button type="submit" form="role-form" class="btn btn-primary">
        <i class="bx bx-check me-1"></i>{{ __('app.send') }}
      </button>
    </div>
  </div>

  <form action="{{ route('roles.store') }}" method="POST" id="role-form">
    @csrf
    <div class="row">
      <div class="col-xl">
        <div class="card mb-4">
          <div class="row card-body">
            <div class="mb-3 col-md-6">
              <label for="name" class="form-label">{{ __('app.name') }}</label>
              <input
                type="text"
                name="name"
                class="form-control @error('name') is-invalid @enderror"
                id="name"
                placeholder="{{ __('app.name') }}"
                value="{{ old('name') }}"
                required
              >
              @error('name')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>

            <div class="mb-3 col-md-6">
              <label for="guard_name" class="form-label">{{ __('app.guard_name') }}</label>
              <input
                type="text"
                name="guard_name"
                class="form-control @error('guard_name') is-invalid @enderror"
                id="guard_name"
                value="{{ old('guard_name', 'web') }}"
                required
              >
              @error('guard_name')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
@endsection
