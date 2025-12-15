{{-- filepath: /home/abdelali/Data/projects/echeila-backend/resources/views/dashboard/lost-and-found/edit.blade.php --}}
@extends('layouts/contentNavbarLayout')

@section('title', __('lost_and_found.edit'))

@section('content')
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">{{ __('lost_and_found.edit') }}</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
          <li class="breadcrumb-item"><a href="{{ route('lost-and-founds.index') }}">{{ __('lost_and_found.lost_and_found') }}</a></li>
          <li class="breadcrumb-item active">{{ __('app.edit') }}</li>
        </ol>
      </nav>
    </div>
    <a href="{{ url()->previous() }}" class="btn btn-label-secondary">
      <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
    </a>
  </div>

  <form action="{{ route('lost-and-founds.update', $lostAndFound->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
      <div class="col-xl">
        <div class="card mb-4">
          <h5 class="card-header">{{ __('lost_and_found.image') }}</h5>
          <div class="card-body">
            <div class="d-flex align-items-start align-items-sm-center gap-4">
              <img src="{{ $lostAndFound->getFirstMediaUrl(App\Models\LostAndFound::IMAGE) ?: asset('assets/img/default-item.png') }}" 
                   alt="{{ __('lost_and_found.item_image') }}" 
                   class="d-block rounded" 
                   height="100" 
                   width="100" 
                   id="uploadedImage"/>
              <div class="button-wrapper">
                <label for="uploadImage" class="btn btn-primary me-2 mb-4" tabindex="0">
                  <span class="d-none d-sm-block"><i class="bx bx-upload me-1"></i>{{ __('app.upload') }}</span>
                  <i class="bx bx-upload d-block d-sm-none"></i>
                </label>
                <input type="file" name="image" class="image-file-input" id="uploadImage" hidden accept="image/png, image/jpeg, image/jpg, image/gif, image/svg, image/webp"/>
                <button type="button" class="btn btn-label-secondary image-reset mb-4">
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
                <label for="passenger" class="form-label">{{ __('lost_and_found.passenger') }}</label>
                <input type="text" class="form-control" id="passenger"
                       value="{{ $lostAndFound->passenger->fullname ?? '-' }}" 
                       disabled readonly>
                <small class="text-muted">{{ __('lost_and_found.passenger_cannot_be_changed') }}</small>
              </div>
              
              <div class="mb-3 col-md-6">
                <label for="status" class="form-label">{{ __('lost_and_found.status') }}</label>
                <select name="status" class="form-select" id="status" required>
                  <option value="">{{ __('app.select_option') }}</option>
                  @foreach($statuses as $key => $value)
                    <option value="{{ $key }}" {{ old('status', $lostAndFound->status) == $key ? 'selected' : '' }}>
                      {{ $value }}
                    </option>
                  @endforeach
                </select>
                @error('status')
                  <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3 col-md-12">
                <label for="description" class="form-label">{{ __('lost_and_found.description') }}</label>
                <textarea name="description" 
                          class="form-control @error('description') is-invalid @enderror" 
                          id="description"
                          rows="5"
                          placeholder="{{ __('lost_and_found.description') }}" 
                          required>{{ old('description', $lostAndFound->description ?? '') }}</textarea>
                @error('description')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="form-group" style="text-align: {{ app()->isLocale('ar') ? 'left' : 'right' }}">
              <button type="submit" class="btn btn-primary">{{ __('app.update') }}</button>
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
      const imageElement = document.getElementById('uploadedImage');
      const fileInput = document.getElementById('uploadImage');
      const resetButton = document.querySelector('.image-reset');

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
    });
  </script>
@endsection