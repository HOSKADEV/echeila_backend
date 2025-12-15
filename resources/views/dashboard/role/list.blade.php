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
    <h5 class="card-header">@lang('app.roles')</h5>
    <div class="table-responsive text-nowrap">
      <table id="laravel_datatable" class="table">
        <thead class="table-light">
          <tr>
            <th>#</th>
            @foreach ($columns as $column)
              <th>{{ __('app.'.$column) }}</th>
              @endforeach
          </tr>
        </thead>
      </table>
    </div>
  </div>
  <!-- Bootstrap Table with Header - Light -->
@endsection
@section('page-script')
  <script>
    $(document).ready(function() {
      initializeDataTable("{{ route('roles.index') }}", @json($columns));
    });
  </script>
@endsection
