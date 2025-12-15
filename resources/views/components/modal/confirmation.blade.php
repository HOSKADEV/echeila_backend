<div class="modal fade" id="{{ $id }}" aria-hidden="true">
  <div class="modal-dialog modal-{{ $size }}" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="fw-bold py-1 mb-1">{{ $title }}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" action="{{ $action }}" method="POST">
          @csrf
          @method($method)

          <div class="alert alert-{{ $theme }} mb-4">
            <h5 class="text-center">{{ $confirmationTitle }}</h5>
            <p class="text-center mb-0">{{ $confirmationText }}</p>
          </div>

          <div class="mb-4">
            {!! $inputs !!}
          </div>

          @if($requireConfirmation)
            <div class="mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="confirmed" required>
                <label class="form-check-label" for="confirmed">
                  {{ $checkboxLabel }}
                </label>
              </div>
            </div>
          @endif

          <div class="d-flex justify-content-center gap-2">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
              {{ $cancelLabel }}
            </button>
            <button type="submit" class="btn btn-{{ $theme }}">
              {{ $submitLabel }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
