{{-- filepath: /home/abdelali/Data/projects/echeila-backend/resources/views/dashboard/lost-and-found/list.blade.php --}}
@extends('layouts/contentNavbarLayout')

@section('title', __('lost_and_found.lost_and_found'))

@section('content')
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h4 class="fw-bold mb-1">{{ __('lost_and_found.lost_and_found') }}</h4>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.dashboard') }}</a></li>
          <li class="breadcrumb-item active">{{ __('lost_and_found.lost_and_found') }}</li>
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
          'label' => 'lost_and_found.status',
          'options' => $statuses
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
              {{-- <div class="dt-buttons btn-group flex-wrap">
                <a href="{{ route('lost-and-founds.create') }}" class="text-white text-decoration-none">
                  <button type="button" class="btn btn-primary">
                    <span class="tf-icons bx bx-plus"></span> {{ __('lost_and_found.add_new') }}
                  </button>
                </a>
              </div> --}}
            </div>
          </div>
        </div>
        <x-table.datatable :columns="$columns" table-id="laravel_datatable" translation-prefix="lost_and_found" />
        <x-table.custom-datatable-pagination table-id="laravel_datatable" />
      </div>
    </div>
  </div>
  <!--/ Bootstrap Table with Header - Light -->

  <!-- Modals -->
  <x-modal.confirmation
    id="delete-modal"
    title="{{ __('lost_and_found.delete') }}"
    action="{{ route('lost-and-founds.destroy', ':id') }}"
    method="DELETE"
    inputs='
    <input type="hidden" name="id" value="">
  '
    theme="danger"
  />

  <x-modal.confirmation
    id="mark-as-returned-modal"
    title="{{ __('lost_and_found.mark_as_returned') }}"
    action="{{ route('lost-and-founds.status.update') }}"
    method="POST"
    inputs='
    <input type="hidden" name="id" value="">
    <input type="hidden" name="status" value="{{ \App\Constants\LostAndFoundStatus::RETURNED }}">
  '
    theme="success"
    confirmationTitle="{{ __('lost_and_found.mark_as_returned_confirmation') }}"
    confirmationText="{{ __('lost_and_found.mark_as_returned_notice') }}"
    checkboxLabel="{{ __('lost_and_found.mark_as_returned_checkbox') }}"
    submitLabel="{{ __('app.submit') }}"
    cancelLabel="{{ __('app.cancel') }}"
  />

@endsection

@section('page-script')
  <script>
    $(document).ready(function() {
      let filters = {
        status_filter: $('#status_filter').val(),
      };

      let table = initializeDataTable(
        "{{ route('lost-and-founds.index') }}",
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
        $form.data('action') ?? $form.data('action', $form.attr('action')); // Store the original action URL
        $form.attr('action', $form.data('action').replace(':id', id));
        $form.find('input[name="id"]').val(id);
      });

      $(document).on('click', '[data-bs-target="#mark-as-returned-modal"]', function() {
        const id = $(this).data('id');
        $('#mark-as-returned-modal').find('input[name="id"]').val(id);
      });
    });
  </script>
@endsection