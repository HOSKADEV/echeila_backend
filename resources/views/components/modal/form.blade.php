<div class="modal fade" id="{{ $id }}" aria-hidden="true">
  <div class="modal-dialog modal-{{ $size ?? 'md' }}" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="fw-bold py-1 mb-1">{{ $title }}</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" action="{{ $action }}" method="POST">
          @csrf
          @method($method ?? 'POST')

          <div class="mb-4">
            {!! $inputs !!}
          </div>

          <div class="d-flex justify-content-center gap-2">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
              {{ $cancelLabel ?? __('app.cancel') }}
            </button>
            <button type="submit" class="btn btn-{{ $theme ?? 'primary' }}">
              {{ $submitLabel ?? __('app.submit') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
