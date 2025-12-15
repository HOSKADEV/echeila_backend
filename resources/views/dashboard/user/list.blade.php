@extends('layouts/contentNavbarLayout')

@section('title', __('user.user'))

@section('content')

  <!-- Statistics -->
  <div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
              <span>Session</span>
              <div class="d-flex align-items-end mt-2">
                <h4 class="mb-0 me-2">21,459</h4>
                <small class="text-success">(+29%)</small>
              </div>
              <p class="mb-0">Total Users</p>
            </div>
            <div class="avatar">
            <span class="avatar-initial rounded bg-label-primary">
              <i class="bx bx-user bx-sm"></i>
            </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
              <span>Paid Users</span>
              <div class="d-flex align-items-end mt-2">
                <h4 class="mb-0 me-2">4,567</h4>
                <small class="text-success">(+18%)</small>
              </div>
              <p class="mb-0">Last week analytics </p>
            </div>
            <div class="avatar">
            <span class="avatar-initial rounded bg-label-danger">
              <i class="bx bx-user-check bx-sm"></i>
            </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
              <span>Active Users</span>
              <div class="d-flex align-items-end mt-2">
                <h4 class="mb-0 me-2">19,860</h4>
                <small class="text-danger">(-14%)</small>
              </div>
              <p class="mb-0">Last week analytics</p>
            </div>
            <div class="avatar">
            <span class="avatar-initial rounded bg-label-success">
              <i class="bx bx-group bx-sm"></i>
            </span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-xl-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between">
            <div class="content-left">
              <span>Pending Users</span>
              <div class="d-flex align-items-end mt-2">
                <h4 class="mb-0 me-2">237</h4>
                <small class="text-success">(+42%)</small>
              </div>
              <p class="mb-0">Last week analytics</p>
            </div>
            <div class="avatar">
            <span class="avatar-initial rounded bg-label-warning">
              <i class="bx bx-user-voice bx-sm"></i>
            </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Statistics -->

  <!-- Bootstrap Table with Header - Light -->
  <div class="card">
    <div class="card-header border-bottom">
      <x-table.filters :filters="[
        [
          'id' => 'user_type_filter',
          'name' => 'user_type_filter',
          'label' => 'user.type',
          'options' => \App\Constants\UserType::all(true)
        ],
        [
          'id' => 'user_role_filter',
          'name' => 'user_role_filter',
          'label' => 'app.role',
          'options' => \App\Support\Enum\Roles::all(true)
        ],
        [
          'id' => 'user_status_filter',
          'name' => 'user_status_filter',
          'label' => 'user.status',
          'options' => \App\Constants\UserStatus::all(true)
        ]
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
                <!-- Custom buttons can be added here if needed -->
                @permission(\App\Support\Enum\Permissions::MANAGE_USERS)
                <a href="{{ route('users.create') }}" class="text-white text-decoration-none">
                  <button type="button" class="btn btn-primary">
                    <span class="tf-icons bx bx-plus"></span> @lang('user.add-new-user')
                  </button>
                </a>
                @endpermission
              </div>
            </div>
          </div>
        </div>
        <x-table.datatable :columns="$columns" table-id="laravel_datatable" translation-prefix="user" />
        <x-table.custom-datatable-pagination table-id="laravel_datatable" />
      </div>
    </div>
  </div>
  <!--/ Bootstrap Table with Header - Light -->

  <!-- Modals for User Actions -->
  <x-modal.confirmation
    id="activate-modal"
    title="{{ __('user.modals.activate') }}"
    action="{{ route('users.status.update') }}"
    method="POST"
    inputs='
    <input type="hidden" name="id" value="">
    <input type="hidden" name="status" value="active">
  '
    theme="success"
    Optional
    confirmationTitle="{{ __('user.activate.confirmation') }}"
    confirmationText="{{ __('user.activate.notice') }}"
    checkboxLabel="{{ __('user.activate.confirm_checkbox') }}"
    submitLabel="{{ __('app.submit') }}"
    cancelLabel="{{ __('app.cancel') }}"
  />
  <x-modal.confirmation
    id="suspend-modal"
    title="{{ __('user.modals.suspend') }}"
    action="{{ route('users.status.update') }}"
    method="POST"
    inputs='
    <input type="hidden" name="id" value="">
    <input type="hidden" name="status" value="banned">
  '
    theme="danger"
    {{--    Optional --}}
    confirmationTitle="{{ __('user.suspend.confirmation') }}"
    confirmationText="{{ __('user.suspend.notice') }}"
    checkboxLabel="{{ __('user.suspend.confirm_checkbox') }}"
    submitLabel="{{ __('app.submit') }}"
    cancelLabel="{{ __('app.cancel') }}"
  />
  <x-modal.confirmation
    id="delete-modal"
    title="{{ __('app.delete') }}"
    action="{{ route('users.destroy', ':id') }}"
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
      let filters = {
        user_type_filter: $('#user_type_filter').val(),
        user_role_filter: $('#user_role_filter').val(),
        user_status_filter: $('#user_status_filter').val()
      };

      let table = initializeDataTable(
        "{{ route('users.index') }}",
        @json($columns),
        filters
      );


      // Reload DataTable when the filter value changes
      $('.filter-input').on('change', function() {
        let filterName = $(this).attr('id');
        filters[filterName] = $(this).val();
        table.ajax.reload();
      });

      $(document).on('click', '[data-bs-target="#activate-modal"]', function() {
        const userId = $(this).data('id');
        $('#activate-modal').find('input[name="id"]').val(userId);
      });

      $(document).on('click', '[data-bs-target="#suspend-modal"]', function() {
        const userId = $(this).data('id');
        $('#suspend-modal').find('input[name="id"]').val(userId);
      });

      $(document).on('click', '[data-bs-target="#delete-modal"]', function() {
        const id = $(this).data('id');
        const $form = $('#delete-modal form');
        $form.data('action') ?? $form.data('action', $form.attr('action')); // Store the original action URL
        $form.attr('action', $form.data('action').replace(':id', id));
        $form.find('input[name="id"]').val(id);
      });
    });
  </script>
@endsection
