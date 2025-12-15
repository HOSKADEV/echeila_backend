@extends('layouts/contentNavbarLayout')

@section('title', __('app.admin_actions'))

@section('content')
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">{{ __('app.admin_actions') }}</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
          <li class="breadcrumb-item active">{{ __('app.admin_actions') }}</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Statistics -->
  <div class="row mb-4">
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div class="card-info">
              <p class="card-text">{{ __('app.total_actions') }}</p>
              <div class="d-flex align-items-end mb-2">
                <h4 class="card-title mb-0 me-2">{{ $stats['total'] }}</h4>
              </div>
            </div>
            <div class="card-icon">
              <span class="badge bg-label-purple rounded p-2">
                <i class="bx bx-list-ul bx-sm"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div class="card-info">
              <p class="card-text">{{ __('app.today') }}</p>
              <div class="d-flex align-items-end mb-2">
                <h4 class="card-title mb-0 me-2">{{ $stats['today'] }}</h4>
              </div>
            </div>
            <div class="card-icon">
              <span class="badge bg-label-success rounded p-2">
                <i class="bx bx-calendar-check bx-sm"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div class="card-info">
              <p class="card-text">{{ __('app.this_week') }}</p>
              <div class="d-flex align-items-end mb-2">
                <h4 class="card-title mb-0 me-2">{{ $stats['this_week'] }}</h4>
              </div>
            </div>
            <div class="card-icon">
              <span class="badge bg-label-info rounded p-2">
                <i class="bx bx-calendar-week bx-sm"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div class="card-info">
              <p class="card-text">{{ __('app.this_month') }}</p>
              <div class="d-flex align-items-end mb-2">
                <h4 class="card-title mb-0 me-2">{{ $stats['this_month'] }}</h4>
              </div>
            </div>
            <div class="card-icon">
              <span class="badge bg-label-warning rounded p-2">
                <i class="bx bx-calendar bx-sm"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap Table with Header - Light -->
  <div class="card">
    <div class="card-header border-bottom">
      <x-table.filters :filters="[
        [
          'id' => 'action_type_filter',
          'name' => 'action_type_filter',
          'label' => 'app.action_type',
          'options' => [
            \App\Models\AdminAction::WALLET_CHARGE => __('app.wallet_charge'),
            \App\Models\AdminAction::WITHDRAW_SUM => __('app.withdraw_sum'),
            \App\Models\AdminAction::PURCHASE_SUBSCRIPTION => __('app.purchase_subscription'),
            \App\Models\AdminAction::CHANGE_USER_STATUS => __('app.change_user_status'),
            \App\Models\AdminAction::CHANGE_DRIVER_STATUS => __('app.change_driver_status'),
          ]
        ],
        /* [
          'id' => 'date_from',
          'name' => 'date_from',
          'label' => 'app.from',
          'type' => 'date',
          'placeholder' => __('app.select_date')
        ],
        [
          'id' => 'date_to',
          'name' => 'date_to',
          'label' => 'app.date_to',
          'type' => 'date',
          'placeholder' => __('app.select_date')
        ], */
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
        <x-table.datatable :columns="$columns" table-id="laravel_datatable" translation-prefix="admin-action" />
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
        action_type_filter: $('#action_type_filter').val(),
        //date_from: $('#date_from').val(),
        //date_to: $('#date_to').val(),
      };

      let table = initializeDataTable(
        "{{ route('admin-actions.index') }}",
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
