@extends('layouts/contentNavbarLayout')

@section('title', __('app.create-admin'))

@section('content')
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">{{ __('app.create-admin') }}</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admins.index') }}">{{ __('app.admins') }}</a></li>
          <li class="breadcrumb-item active">{{ __('app.add') }}</li>
        </ol>
      </nav>
    </div>
    <div>
      <a href="{{ url()->previous() }}" class="btn btn-label-secondary me-2">
        <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
      </a>
      <button type="submit" form="admin-form" class="btn btn-primary">
        <i class="bx bx-check me-1"></i>{{ __('app.send') }}
      </button>
    </div>
  </div>

  <form action="{{ route('admins.store') }}" method="POST" enctype="multipart/form-data" id="admin-form">
    @csrf
    <div class="row">
      <div class="col-xl">
        <div class="card mb-4">
          <h5 class="card-header">{{ __('admin.avatar') }}</h5>
          <div class="card-body">
            <div class="d-flex align-items-start align-items-sm-center gap-4">
              <img src="{{ asset('assets/img/avatars/1.png') }}" alt="{{ __('app.avatar') }}" class="d-block rounded" height="100" width="100" id="uploadedAvatar"/>
              <div class="button-wrapper">
                <label for="uploadAvatar" class="btn btn-primary me-2 mb-4" tabindex="0">
                  <span class="d-none d-sm-block"><i class="bx bx-upload me-1"></i>{{ __('app.upload') }}</span>
                  <i class="bx bx-upload d-block d-sm-none"></i>
                </label>
                <input type="file" name="avatar" class="avatar-file-input" id="uploadAvatar" hidden accept="image/png, image/jpeg"/>
                <button type="button" class="btn btn-label-secondary avatar-image-reset mb-4">
                  <i class="bx bx-reset d-block d-sm-none"></i>
                  <span class="d-none d-sm-block"><i class="bx bx-reset me-1"></i>{{ __('app.reset') }}</span>
                </button>
                <p class="text-muted mb-0">{{ __('app.allowed_jpg_gif_png_max_size') }}</p>
              </div>
            </div>
          </div>
          <hr class="my-0">
          <div class="card-body">
            <div class="row">
              <div class="mb-3 col-md-6">
                <label for="firstname" class="form-label">{{ __('admin.firstname') }}</label>
                <input type="text" name="firstname" class="form-control" id="firstname"
                       placeholder="{{ __('admin.firstname') }}"
                       value="{{ old('firstname') }}" required>
              </div>
              <div class="mb-3 col-md-6">
                <label for="lastname" class="form-label">{{ __('admin.lastname') }}</label>
                <input type="text" name="lastname" class="form-control" id="lastname"
                       placeholder="{{ __('admin.lastname') }}" value="{{ old('lastname') }}"
                       required>
              </div>
              <div class="mb-3 col-md-6">
                <label for="email" class="form-label">{{ __('admin.email') }}</label>
                <input type="text" name="email" class="form-control" id="email" placeholder="{{ __('admin.email') }}"
                       value="{{ old('email') }}" required>
              </div>
              <div class="mb-3 col-md-6">
                <label for="phone" class="form-label">{{ __('admin.phone') }}</label>
                <input type="text" name="phone" class="form-control phone-mask" id="phone"
                       placeholder="{{ __('admin.phone') }}" value="{{ old('phone') }}" required>
              </div>
              <div class="mb-3 col-md-6">
                <div class="form-password-toggle">
                  <label for="password" class="form-label">{{ __('admin.password') }}</label>
                  <div class="input-group input-group-merge">
                    <input type="password" name="password" class="form-control" id="password"
                           placeholder="············" value="{{ old('password') }}" required>
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>
              </div>
              <div class="mb-3 col-md-6">
                <label for="role" class="form-label">{{ __('admin.role') }}</label>
                <select name="role" class="form-select" id="role" required>
                  <option value="">{{ __('app.select_option') }}</option>
                  @foreach(\App\Support\Enum\Roles::all(true) as $key => $value)
                    <option value="{{ $key }}" {{ old('role') == $key ? 'selected' : '' }}>{{ $value }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
@endsection

@section('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Image Upload Preview
      const imageElement = document.getElementById('uploadedAvatar');
      const fileInput = document.getElementById('uploadAvatar');
      const resetButton = document.querySelector('.avatar-image-reset');

      if (imageElement && fileInput && resetButton) {
        const resetImage = imageElement.src;

        // Update the image preview when a file is selected
        fileInput.addEventListener('change', function () {
          if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
              imageElement.src = e.target.result;
            };
            reader.readAsDataURL(this.files[0]);
          }
        });

        // Reset to the original image
        resetButton.addEventListener('click', function () {
          fileInput.value = '';
          imageElement.src = resetImage;
        });
      }

      // Password visibility toggle
      document.querySelectorAll(".input-group-text").forEach(function (eyeIcon) {
        eyeIcon.addEventListener("click", function () {
          let passwordInput = this.previousElementSibling;
          if (passwordInput.type === "password") {
            passwordInput.type = "text";
            this.innerHTML = '<i class="bx bx-show"></i>';
          } else {
            passwordInput.type = "password";
            this.innerHTML = '<i class="bx bx-hide"></i>';
          }
        });
      });
    });
  </script>
@endsection
