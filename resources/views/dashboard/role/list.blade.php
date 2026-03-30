@extends('layouts/contentNavbarLayout')

@section('title', __('app.roles'))

@section('content')
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">{{ __('app.roles') }}</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
          <li class="breadcrumb-item active">{{ __('app.roles') }}</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Bootstrap Table with Header - Light -->
  <div class="card">
    <div class="card-datatable table-responsive">
      <div class="dataTables_wrapper dt-bootstrap5 no-footer">
        <div class="row mx-2">
          <div class="col-md-2">
            <x-table.custom-datatable-length />
          </div>
          <div class="col-md-10">
            <div class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">
              <x-table.custom-datatable-search />
              <div class="dt-buttons btn-group flex-wrap">
                @if (auth()->user()->hasRole(\App\Support\Enum\Roles::SUPER_ADMIN))
                  <a href="{{ route('roles.create') }}" class="text-white text-decoration-none">
                    <button type="button" class="btn btn-primary">
                      <span class="tf-icons bx bx-plus"></span> @lang('app.add-new-role')
                    </button>
                  </a>
                @endif
              </div>
            </div>
          </div>
        </div>

        <x-table.datatable :columns="$columns" table-id="laravel_datatable" translation-prefix="app" />
        <x-table.custom-datatable-pagination table-id="laravel_datatable" />
      </div>
    </div>
  </div>
  <!-- Bootstrap Table with Header - Light -->

  <x-modal.confirmation
    id="delete-modal"
    title="{{ __('app.delete') }}"
    action="{{ route('roles.destroy', ':id') }}"
    method="DELETE"
    inputs='
      <input type="hidden" name="id" value="">
    '
    theme="danger"
  />
@endsection

@section('page-script')
  <script>
    $(document).ready(function() {
      let filters = {};

      initializeDataTable("{{ route('roles.index') }}", @json($columns), filters);

      $(document).on('click', '[data-bs-target="#delete-modal"]', function() {
        const id = $(this).data('id');
        const $form = $('#delete-modal form');
        $form.data('action') ?? $form.data('action', $form.attr('action'));
        $form.attr('action', $form.data('action').replace(':id', id));
        $form.find('input[name="id"]').val(id);
      });
    });
  </script>
@endsection
