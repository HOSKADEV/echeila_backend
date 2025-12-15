@extends('layouts/contentNavbarLayout')

@section('title', __('app.profile'))


@section('content')
  <h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light"> @lang('app.account_settings') /</span> @lang('app.account')
  </h4>
  <div class="row">
    <div class="col-md-12">
      <form action="{{ route('profile.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card mb-4">
          <h5 class="card-header">@lang('app.profile_details')</h5>
          <div class="card-body">
            <div class="d-flex align-items-start align-items-sm-center gap-4">
              <img src="{{ $user->avatar_url }}" alt="@lang('app.avatar')" class="d-block rounded" height="100" width="100" id="uploadedAvatar"/>
              <div class="button-wrapper">
                <label for="uploadAvatar" class="btn btn-primary me-2 mb-4"><i class="bx bx-upload"></i></label>
                <input type="file" name="avatar" class="avatar-file-input" id="uploadAvatar" hidden accept="image/png, image/jpeg"/>
                <button type="button" class="btn btn-secondary avatar-image-reset mb-4">
                  <i class="bx bx-reset"></i>
                </button>
              </div>
            </div>
            <p class="text-muted small mt-2">@lang('app.allowed_jpg_gif_png_max_size')</p>
          </div>
          <hr class="my-0">
          <div class="card-body">
            <div class="row">
              <div class="mb-3 col-md-6">
                <label for="firstname" class="form-label">@lang('user.firstname')</label>
                <input type="text" name="firstname" class="form-control" id="firstname" value="{{ old('firstname', $user->firstname) }}" required>
              </div>
              <div class="mb-3 col-md-6">
                <label for="lastname" class="form-label">@lang('user.lastname')</label>
                <input type="text" name="lastname" class="form-control" id="lastname" value="{{ old('lastname', $user->lastname) }}" required>
              </div>
              <div class="mb-3 col-md-6">
                <label for="email" class="form-label">@lang('user.email')</label>
                <input type="email" name="email" class="form-control" id="email" value="{{ old('email', $user->email) }}" disabled>
              </div>
              <div class="mb-3 col-md-6">
                <label for="phone" class="form-label">@lang('user.phone')</label>
                <input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone', $user->phone) }}" required>
              </div>
              {{-- <div class="mb-3 col-md-6">
                <label for="birthdate" class="form-label">@lang('user.birthdate')</label>
                <input type="date" name="birthdate" class="form-control" id="birthdate" value="{{ old('birthdate', $user->birthdate?->format('Y-m-d')) }}">
              </div>
              <div class="mb-3 col-md">
                <label for="full_address" class="form-label">@lang('user.full_address')</label>
                <input type="text" name="full_address" class="form-control" id="full_address" value="{{ old('full_address', $user->full_address) }}">
              </div> --}}
            </div>
            <div class="form-group" style="text-align: {{ app()->isLocale('ar') ? 'left' : 'right' }}">
              <button type="submit" class="btn btn-primary">@lang('app.send')</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <form action="{{ route('profile.update-password') }}" method="POST">
        @csrf
        <div class="card mb-4">
          <h5 class="card-header">@lang('app.reset_password')</h5>
          <div class="card-body">
            <div class="row">
              <div class="mb-3 col-md-12">
                <label for="old_password" class="form-label">@lang('user.current_password')</label>
                <div class="input-group input-group-merge">
                  <input type="password" name="old_password" id="old_password" class="form-control" required minlength="8" placeholder="••••••••••••">
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
              </div>
              <div class="mb-3 col-md-6">
                <label for="password" class="form-label">@lang('user.new_password')</label>
                <div class="input-group input-group-merge">
                  <input type="password" name="password" id="password" class="form-control" required minlength="8" placeholder="••••••••••••">
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
              </div>
              <div class="mb-3 col-md-6">
                <label for="password_confirmation" class="form-label">@lang('user.password_confirmation')</label>
                <div class="input-group input-group-merge">
                  <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required minlength="8" placeholder="••••••••••••">
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
              </div>
              <div class="form-group" style="text-align: {{ app()->isLocale('ar') ? 'left' : 'right' }}">
                <button type="submit" class="btn btn-primary">@lang('app.send')</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
    </div>

    @endsection

    @section('scripts')
      <script>
        document.addEventListener('DOMContentLoaded', function () {
          function setupImageUpload(imageId, fileInputId, resetButtonClass) {
            let imageElement = document.getElementById(imageId);
            const fileInput = document.getElementById(fileInputId);
            const resetButton = document.querySelector(`.${resetButtonClass}`);

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
                fileInput.value = ''; // Clear file input
                imageElement.src = resetImage; // Restore original image
              });
            }
          }

          function setupStateCityDropdown() {
            const stateDropdown = document.getElementById('state_id');
            const cityDropdown = document.getElementById('city_id');

            if (stateDropdown && cityDropdown) {
              stateDropdown.addEventListener('change', function () {
                const stateId = this.value;

                cityDropdown.innerHTML = '<option value="">{{ __("app.select_option") }}</option>'; // Clear old options

                if (stateId) {
                  fetch(`/get-cities/${stateId}`)
                    .then(response => response.json())
                    .then(data => {
                      const cities = data.data; // Extract the array from the object
                      if (!Array.isArray(cities)) {
                        return;
                      }
                      cityDropdown.innerHTML = '<option value="">{{ __("app.select_option") }}</option>'; // Clear old options
                      cities.forEach(city => {
                        const option = new Option(city.name, city.id);
                        cityDropdown.appendChild(option);
                      });
                    })
                    .catch(error => console.error('Error fetching cities:', error));
                }
              });
            }
          }

          document.querySelectorAll(".input-group-text").forEach(function (eyeIcon) {
            eyeIcon.addEventListener("click", function () {
              let passwordInput = this.previousElementSibling;
              if (passwordInput.type === "password") {
                passwordInput.type = "text";
                this.innerHTML = '<i class="bx bx-show"></i>'; // Change icon
              } else {
                passwordInput.type = "password";
                this.innerHTML = '<i class="bx bx-hide"></i>'; // Change icon back
              }
            });
          });

          // Initialize functions
          setupImageUpload('uploadedAvatar', 'uploadAvatar', 'avatar-image-reset');
          setupStateCityDropdown(); // ← Call this function here
        });
      </script>
@endsection
