@extends('layouts/contentNavbarLayout')

@section('title', __('trip.trips'))

@section('content')
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">{{ __('trip.trips') }}</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
          <li class="breadcrumb-item active">{{ __('trip.trips') }}</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Bootstrap Table with Header - Light -->
  <div class="card">
    <div class="card-header border-bottom">
      <x-table.filters :filters="[
        [
          'id' => 'status_filter',
          'name' => 'status_filter',
          'label' => 'trip.status',
          'options' => App\Constants\TripStatus::all(true)
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
            </div>
          </div>
        </div>
        <x-table.datatable :columns="$columns" table-id="laravel_datatable" translation-prefix="trip" />
        <x-table.custom-datatable-pagination table-id="laravel_datatable" />
      </div>
    </div>
  </div>
  <!--/ Bootstrap Table with Header - Light -->

@endsection

@section('page-script')
  <script>
    $(document).ready(function() {
      let filters = {
        status_filter: $('#status_filter').val(),
      };

      let table = initializeDataTable(
        "{{ route('trips.index', 'all') }}",
        @json($columns),
        filters
      );

      // Reload DataTable when the filter value changes
      $('.filter-input').on('change', function() {
        let filterName = $(this).attr('id');
        filters[filterName] = $(this).val();
        table.ajax.reload();
      });
    });
  </script>
@endsection
