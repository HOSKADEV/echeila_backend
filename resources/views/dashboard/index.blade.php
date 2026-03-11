@php $configData = Helper::appClasses(); @endphp

@extends('layouts/layoutMaster')

@section('title', __('actions.home'))

@section('content')
{{-- ─────────────────────────── ROW 1 : Info cards + Active Trips ──────────────── --}}
<div class="row">
    {{-- Pending Drivers card --}}
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="d-flex align-items-center row g-0">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-warning">{{ __('dashboard.pending_drivers') }}</h5>
                        <h2 class="fw-bold mb-3">{{ number_format($pendingDrivers) }}</h2>
                        <a href="{{ route('drivers.index') }}" class="btn btn-sm btn-label-warning">
                            <i class="bx bx-user-check me-1"></i> {{ __('dashboard.view_drivers') }}
                        </a>
                    </div>
                </div>
                <div class="col-sm-5 d-flex align-items-center justify-content-center py-4">
                    <span class="badge bg-label-warning rounded-circle p-4">
                        <i class="bx bx-time-five" style="font-size: 2.5rem; line-height: 1;"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Active Trips card --}}
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="d-flex align-items-center row g-0">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __('dashboard.active_trips') }}</h5>
                        <h2 class="fw-bold mb-3">{{ number_format($activeTrips) }}</h2>
                        <a href="{{ route('trips.index', ['type' => 'all']) }}" class="btn btn-sm btn-label-primary">
                            <i class="bx bx-list-ul me-1"></i> {{ __('dashboard.view_trips') }}
                        </a>
                    </div>
                </div>
                <div class="col-sm-5 d-flex align-items-center justify-content-center py-4">
                    <span class="badge bg-label-primary rounded-circle p-4">
                        <i class="bx bx-trip" style="font-size: 2.5rem; line-height: 1;"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Found Items card --}}
    <div class="col-lg-4 mb-4">
        <div class="card h-100">
            <div class="d-flex align-items-center row g-0">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-info">{{ __('dashboard.found_items') }}</h5>
                        <h2 class="fw-bold mb-3">{{ number_format($foundItems) }}</h2>
                        <a href="{{ route('lost-and-founds.index') }}" class="btn btn-sm btn-label-info">
                            <i class="bx bx-search-alt me-1"></i> {{ __('dashboard.view_items') }}
                        </a>
                    </div>
                </div>
                <div class="col-sm-5 d-flex align-items-center justify-content-center py-4">
                    <span class="badge bg-label-info rounded-circle p-4">
                        <i class="bx bx-package" style="font-size: 2.5rem; line-height: 1;"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ─────────────────────────── ROW 2 : Stat Cards ────────────────────────────── --}}
<div class="row mb-4">
    {{-- Total Passengers --}}
    <div class="col-md-4 col-xl-2 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="fw-semibold d-block mb-1 text-muted" style="font-size:.8rem;">{{ __('dashboard.total_passengers') }}</span>
                        <h4 class="card-title mb-0">{{ number_format($totalPassengers) }}</h4>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-purple">
                            <i class="bx bx-user"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Approved Drivers --}}
    <div class="col-md-4 col-xl-2 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="fw-semibold d-block mb-1 text-muted" style="font-size:.8rem;">{{ __('dashboard.approved_drivers') }}</span>
                        <h4 class="card-title mb-0">{{ number_format($approvedDrivers) }}</h4>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-blue">
                            <i class="bx bx-user-check"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Completed Trips --}}
    <div class="col-md-4 col-xl-2 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="fw-semibold d-block mb-1 text-muted" style="font-size:.8rem;">{{ __('dashboard.completed_trips') }}</span>
                        <h4 class="card-title mb-0">{{ number_format($completedTrips) }}</h4>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="bx bx-check-circle"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Items Returned --}}
    <div class="col-md-4 col-xl-2 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="fw-semibold d-block mb-1 text-muted" style="font-size:.8rem;">{{ __('dashboard.items_returned') }}</span>
                        <h4 class="card-title mb-0">{{ number_format($returnedItems) }}</h4>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="bx bx-package"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Total Revenue --}}
    <div class="col-md-4 col-xl-2 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="fw-semibold d-block mb-1 text-muted" style="font-size:.8rem;">{{ __('dashboard.total_revenue') }}</span>
                        <h4 class="card-title mb-0">{{ number_format($totalRevenue, 2) }}</h4>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="bx bx-dollar"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Admin Actions --}}
    <div class="col-md-4 col-xl-2 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="fw-semibold d-block mb-1 text-muted" style="font-size:.8rem;">{{ __('dashboard.admin_actions') }}</span>
                        <h4 class="card-title mb-0">{{ number_format($adminActions) }}</h4>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-danger">
                            <i class="bx bx-shield"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ─────────────────────────── ROW 3 : Trips by Type + Revenue per Month ─────────────────── --}}
<div class="row mb-4">
    <div class="col-lg-5 mb-4 mb-lg-0">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-title mb-0">{{ __('dashboard.trips_by_type') }}</h6>
                {!! $tripsByType->container() !!}
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-title mb-0">{{ __('dashboard.revenue_per_month') }}</h6>
                <small class="text-muted">{{ __('dashboard.last_6_months') }}</small>
                {!! $revenuePerMonth->container() !!}
            </div>
        </div>
    </div>
</div>

{{-- ─────────────────────────── ROW 4 : Users by Type + Drivers by Status + Trips by Status ── --}}
<div class="row mb-4">
    <div class="col-lg-4 mb-4 mb-lg-0">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-title mb-0">{{ __('dashboard.users_by_type') }}</h6>
                {!! $usersByType->container() !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-4 mb-lg-0">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-title mb-0">{{ __('dashboard.drivers_by_status') }}</h6>
                {!! $driversByStatus->container() !!}
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-title mb-0">{{ __('dashboard.trips_by_status') }}</h6>
                {!! $tripsByStatus->container() !!}
            </div>
        </div>
    </div>
</div>

{{-- ─────────────────────────── ROW 5 : Trips per Month + Users per Month ────────────── --}}
<div class="row mb-4">
    <div class="col-lg-6 mb-4 mb-lg-0">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-title mb-0">{{ __('dashboard.trips_per_month') }}</h6>
                <small class="text-muted">{{ __('dashboard.last_6_months') }}</small>
                {!! $tripsPerMonth->container() !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-title mb-0">{{ __('dashboard.new_users_per_month') }}</h6>
                <small class="text-muted">{{ __('dashboard.last_6_months') }}</small>
                {!! $usersPerMonth->container() !!}
            </div>
        </div>
    </div>
</div>

{{-- ApexCharts library + chart scripts --}}
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
{!! $tripsPerMonth->script() !!}
{!! $tripsByType->script() !!}
{!! $usersByType->script() !!}
{!! $driversByStatus->script() !!}
{!! $tripsByStatus->script() !!}
{!! $usersPerMonth->script() !!}
{!! $revenuePerMonth->script() !!}

@endsection
