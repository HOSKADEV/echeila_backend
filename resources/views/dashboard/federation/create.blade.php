@extends('layouts/contentNavbarLayout')

@section('title', __('app.create-federation'))

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />

  <style>
    /* Custom styles for Quill editor */
    .ql-editor {
      min-height: 165px !important;
    }
  </style>
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
@endsection

@section('content')
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">{{ __('federation.add-new') }}</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
          <li class="breadcrumb-item"><a href="{{ route('federations.index') }}">{{ __('app.federations') }}</a></li>
          <li class="breadcrumb-item active">{{ __('app.add') }}</li>
        </ol>
      </nav>
    </div>
    <div>
      <a href="{{ url()->previous() }}" class="btn btn-label-secondary me-2">
        <i class="bx bx-arrow-back me-1"></i>{{ __('app.back') }}
      </a>
      <button type="submit" form="federation-form" class="btn btn-primary">
        <i class="bx bx-check me-1"></i>{{ __('app.send') }}
      </button>
    </div>
  </div>

  <form action="{{ route('federations.store') }}" method="POST" enctype="multipart/form-data" id="federation-form">
    @csrf
    <div class="row">
      <div class="col-xl">
        <div class="card mb-4">
          <h5 class="card-header">{{ __('federation.image') }}</h5>
          <div class="card-body">
            <div class="d-flex align-items-start align-items-sm-center gap-4">
              <img src="{{ asset('assets/img/avatars/1.png') }}" alt="{{ __('federation.image') }}" class="d-block rounded" height="100" width="100" id="uploadedImage"/>
              <div class="button-wrapper">
                <label for="uploadImage" class="btn btn-primary me-2 mb-4" tabindex="0">
                  <span class="d-none d-sm-block"><i class="bx bx-upload me-1"></i>{{ __('app.upload') }}</span>
                  <i class="bx bx-upload d-block d-sm-none"></i>
                </label>
                <input type="file" name="image" class="image-file-input" id="uploadImage" hidden accept="image/png, image/jpeg"/>
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
              <div class="col-md-6">
                <div class="mb-3">
                  <label for="user_id" class="form-label">{{ __('federation.passenger') }}</label>
                  <select name="user_id" class="form-select @error('user_id') is-invalid @enderror" id="user_id" required>
                    <option value="">{{ __('app.select_option') }}</option>
                    @foreach($users as $user)
                      <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->passenger->fullname }} ({{ $user->phone }})
                      </option>
                    @endforeach
                  </select>
                  @error('user_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                  @enderror
                </div>
                <div class="mb-3">
                  <label for="name" class="form-label">{{ __('federation.name') }}</label>
                  <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name"
                         placeholder="{{ __('federation.name') }}"
                         value="{{ old('name') }}" required>
                  @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                  @enderror
                </div>
                <div class="mb-3">
                  <label for="creation_date" class="form-label">{{ __('federation.creation_date') }}</label>
                  <input type="date" name="creation_date" class="form-control @error('creation_date') is-invalid @enderror" id="creation_date"
                         value="{{ old('creation_date') }}" required>
                  @error('creation_date')
                    <span class="invalid-feedback">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="col-md-6">
                <label for="description" class="form-label">{{ __('federation.description') }}</label>
                <div id="editor-description">
                  {!! old('description') ?? '' !!}
                </div>
                <input type="hidden" name="description" id="content-description">
                @error('description')
                  <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
@endsection

@section('page-script')
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

      // Initialize Quill editor for description with simple toolbar
      const editorDescription = new Quill('#editor-description', {
        theme: 'snow',
        modules: {
          toolbar: [
            ['bold', 'italic', 'underline'],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['link'],
            ['clean']
          ]
        }
      });

      // Set initial content
      const contentDescription = document.getElementById('content-description');
      contentDescription.value = editorDescription.root.innerHTML;

      // Update hidden input on text change
      editorDescription.on('text-change', function() {
        contentDescription.value = editorDescription.root.innerHTML;
      });

      // Ensure content is updated before form submission
      document.getElementById('federation-form').addEventListener('submit', function() {
        contentDescription.value = editorDescription.root.innerHTML;
      });
    });
  </script>
@endsection