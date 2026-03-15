@extends('layouts/contentNavbarLayout')

@section('title', __('zone.zones'))

@section('content')
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">{{ __('zone.zones') }}</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
          <li class="breadcrumb-item active">{{ __('zone.zones') }}</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Bootstrap Table with Header - Light -->
  <div class="card">
    <div class="card-header border-bottom">
      <x-table.filters :filters="[
        [
          'id' => 'type',
          'name' => 'type',
          'label' => __('zone.type'),
          'options' => [
            'circle' => 'Circle',
            'polygon' => 'Polygon',
          ],
        ],
        [
          'id' => 'isActive',
          'name' => 'isActive',
          'label' => __('zone.isActive'),
          'options' => [
            '1' => __('zone.active'),
            '0' => __('zone.inactive'),
          ],
        ],
      ]" />
    </div>
    <div class="card-datatable table-responsive">
      <div class="dataTables_wrapper dt-bootstrap5 no-footer">
        <div class="row mx-2">
          <div class="col-md-2">
            <x-table.custom-datatable-length />
          </div>
          <div class="col-md-10">
            <div
              class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-end flex-md-row flex-column mb-3 mb-md-0">
              <x-table.custom-datatable-search />
              <div class="dt-buttons btn-group flex-wrap">
                @permission(\App\Support\Enum\Permissions::ZONE_CREATE)
                <a href="{{ route('zones.create') }}" class="text-white text-decoration-none">
                  <button type="button" class="btn btn-primary">
                    <span class="tf-icons bx bx-plus"></span> @lang('app.add-new-zone')
                  </button>
                </a>
                @endpermission
              </div>
            </div>
          </div>
        </div>
        <x-table.datatable :columns="$columns" table-id="laravel_datatable" translation-prefix="zone" />
        <x-table.custom-datatable-pagination table-id="laravel_datatable" />
      </div>
    </div>
  </div>
  <!--/ Bootstrap Table with Header - Light -->

  <x-modal.confirmation
    id="delete-modal"
    title="{{ __('app.delete') }}"
    action="{{ route('zones.destroy', ':id') }}"
    method="DELETE"
    inputs='<input type="hidden" name="id" value="">'
    theme="danger"
  />

@endsection
@section('page-script')
  <script>
    $(document).ready(function() {
      let filters = {
        type: $('#type').val(),
        isActive: $('#isActive').val()
      };

      let table = initializeDataTable(
        "{{ route('zones.index') }}",
        @json($columns),
        filters
      );

      // Reload DataTable when the filter value changes
      $('.filter-input').on('change', function() {
        let filterName = $(this).attr('id');
        filters[filterName] = $(this).val();
        table.ajax.reload();
      });

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
