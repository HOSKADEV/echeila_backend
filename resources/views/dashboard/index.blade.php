@php $configData = Helper::appClasses(); @endphp

@extends("layouts/layoutMaster")

@section("title", __("actions.home"))

@section("content")
<div id="pdf-page-1">

{{-- PDF HEADER (hidden until download) --}}
<div id="pdf-header" style="display:none; padding:20px 24px 16px; border-bottom:2px solid #eee; margin-bottom:20px; background:#fff;">
    <div style="display:flex; align-items:center; justify-content:space-between;">
        <img src="{{ asset('assets/img/logo/1.png') }}" style="height:48px; object-fit:contain;" crossorigin="anonymous">
        <div style="text-align:right; font-size:13px; color:#555; line-height:1.6;">
            <div><strong>{{ auth()->user()->fullname }}</strong></div>
            <div>{{ now()->format('Y-m-d H:i') }}</div>
        </div>
    </div>
    <hr style="margin:12px 0 6px; border-color:#ddd;">
    <div style="font-size:13px; color:#777;">
        {{ __('dashboard.platform_overview_title') }} &mdash;
        @switch($period)
            @case('today')  {{ __('dashboard.filter_today')  }} @break
            @case('week')   {{ __('dashboard.filter_week')   }} @break
            @case('month')  {{ __('dashboard.filter_month')  }} @break
            @default        {{ __('dashboard.filter_all')    }}
        @endswitch
    </div>
</div>

{{-- ─────────────────────────── ROW 1 : Info cards (always on top) ────── --}}
<div class="row mb-4">
    {{-- Pending Drivers --}}
    <div class="col-lg-4 mb-4 mb-lg-0">
        <div class="card h-100">
            <div class="d-flex align-items-center row g-0">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-warning">{{ __("dashboard.pending_drivers") }}</h5>
                        <h2 class="fw-bold mb-3">{{ number_format($pendingDrivers) }}</h2>
                        <a href="{{ route("drivers.index") }}" class="btn btn-sm btn-label-warning">
                            <i class="bx bx-user-check me-1"></i> {{ __("dashboard.view_drivers") }}
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
    {{-- Active Trips --}}
    <div class="col-lg-4 mb-4 mb-lg-0">
        <div class="card h-100">
            <div class="d-flex align-items-center row g-0">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-primary">{{ __("dashboard.active_trips") }}</h5>
                        <h2 class="fw-bold mb-3">{{ number_format($activeTrips) }}</h2>
                        <a href="{{ route("trips.index", ["type" => "all"]) }}" class="btn btn-sm btn-label-primary">
                            <i class="bx bx-list-ul me-1"></i> {{ __("dashboard.view_trips") }}
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
    {{-- Found Items --}}
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="d-flex align-items-center row g-0">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-info">{{ __("dashboard.found_items") }}</h5>
                        <h2 class="fw-bold mb-3">{{ number_format($foundItems) }}</h2>
                        <a href="{{ route("lost-and-founds.index") }}" class="btn btn-sm btn-label-info">
                            <i class="bx bx-search-alt me-1"></i> {{ __("dashboard.view_items") }}
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
{{-- ─────────────────────────── PERIOD FILTER BAR ──────────────────────── --}}
<div id="pdf-exclude-bar" class="d-flex align-items-center justify-content-between mb-4">
    <h5 class="fw-bold mb-0">{{ __("dashboard.platform_overview_title") }}</h5>
    <div class="d-flex align-items-center gap-2">
        <div class="btn-group" role="group">
            <a href="{{ request()->fullUrlWithQuery(["period" => "today"]) }}"
               class="btn btn-sm {{ $period === "today" ? "btn-primary" : "btn-outline-primary" }}">
                {{ __("dashboard.filter_today") }}
            </a>
            <a href="{{ request()->fullUrlWithQuery(["period" => "week"]) }}"
               class="btn btn-sm {{ $period === "week"  ? "btn-primary" : "btn-outline-primary" }}">
                {{ __("dashboard.filter_week") }}
            </a>
            <a href="{{ request()->fullUrlWithQuery(["period" => "month"]) }}"
               class="btn btn-sm {{ $period === "month" ? "btn-primary" : "btn-outline-primary" }}">
                {{ __("dashboard.filter_month") }}
            </a>
            <a href="{{ request()->fullUrlWithQuery(["period" => "all"]) }}"
               class="btn btn-sm {{ $period === "all"   ? "btn-primary" : "btn-outline-primary" }}">
                {{ __("dashboard.filter_all") }}
            </a>
        </div>
        <button id="download-pdf-btn" class="btn btn-sm btn-danger">
            <i class="bx bxs-file-pdf me-1"></i> {{ __("dashboard.download_pdf") }}
        </button>
    </div>
</div>
{{-- ─────────────────────────── ROW 2 : Fixed unfiltered totals ──────────── --}}
<div class="row mb-4">
    <div class="col-md-6 mb-4 mb-md-0">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="fw-semibold d-block mb-1 text-muted" style="font-size:.8rem;">{{ __("dashboard.total_users") }}</span>
                        <h4 class="card-title mb-0">{{ number_format($totalUsers) }}</h4>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-purple">
                            <i class="bx bx-group"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="fw-semibold d-block mb-1 text-muted" style="font-size:.8rem;">{{ __("dashboard.total_trips") }}</span>
                        <h4 class="card-title mb-0">{{ number_format($totalTrips) }}</h4>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="bx bx-map-alt"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- ─────────────────────────── ROW 3 : New Users / New Trips / Revenue ─── --}}
<div class="row mb-4">
    <div class="col-md-4 mb-4 mb-md-0">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="fw-semibold d-block mb-1 text-muted" style="font-size:.8rem;">{{ __("dashboard.new_users") }}</span>
                        <h4 class="card-title mb-0">{{ number_format($newUsers) }}</h4>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="bx bx-user-plus"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4 mb-md-0">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="fw-semibold d-block mb-1 text-muted" style="font-size:.8rem;">{{ __("dashboard.new_trips") }}</span>
                        <h4 class="card-title mb-0">{{ number_format($newTrips) }}</h4>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="bx bx-plus-circle"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="fw-semibold d-block mb-1 text-muted" style="font-size:.8rem;">{{ __("dashboard.total_revenue") }}</span>
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
</div>
{{-- ─────────────────────────── ROW 4 : Items / Ratings ───────────────── --}}
<div class="row mb-4">
    <div class="col-md-4 mb-4 mb-md-0">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="fw-semibold d-block mb-1 text-muted" style="font-size:.8rem;">{{ __("dashboard.items_returned") }}</span>
                        <h4 class="card-title mb-0">{{ number_format($returnedItems) }}</h4>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-danger">
                            <i class="bx bx-package"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4 mb-md-0">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="fw-semibold d-block mb-1 text-muted" style="font-size:.8rem;">{{ __("dashboard.avg_driver_rating") }}</span>
                        <h4 class="card-title mb-0">{{ number_format($avgDriverRating, 1) }} <small class="text-warning" style="font-size:.9rem;">&#9733;</small></h4>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-blue">
                            <i class="bx bxs-star"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="fw-semibold d-block mb-1 text-muted" style="font-size:.8rem;">{{ __("dashboard.avg_passenger_rating") }}</span>
                        <h4 class="card-title mb-0">{{ number_format($avgPassengerRating, 1) }} <small class="text-warning" style="font-size:.9rem;">&#9733;</small></h4>
                    </div>
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-teal">
                            <i class="bx bx-star"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- ═══════════════════════ CHARTS ═════════════════════════════════════════ --}}
<div class="d-flex align-items-center mb-3 mt-2">
    <span class="text-muted fw-semibold" style="font-size:.75rem; text-transform:uppercase; letter-spacing:.07rem; white-space:nowrap;">
        {{ __("dashboard.trends_title") }}
    </span>
    <hr class="flex-grow-1 ms-3 my-0 opacity-25">
</div>
{{-- Row 5: Trips by Status + Trips by Type ──────────────────────────────── --}}
<div class="row mb-4">
    <div class="col-md-6 mb-4 mb-md-0">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-title mb-0">{{ __("dashboard.trips_by_status") }}</h6>
                @if($tripsByStatusEmpty)
                    <div class="d-flex flex-column align-items-center justify-content-center text-muted py-5">
                        <i class="bx bx-pie-chart-alt-2 mb-2" style="font-size:2.5rem; opacity:.3;"></i>
                        <p class="mb-0" style="font-size:.85rem;">{{ __("dashboard.no_data_available") }}</p>
                    </div>
                @else
                    {!! $tripsByStatus->container() !!}
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-title mb-0">{{ __("dashboard.trips_by_type") }}</h6>
                @if($tripsByTypeEmpty)
                    <div class="d-flex flex-column align-items-center justify-content-center text-muted py-5">
                        <i class="bx bx-pie-chart-alt-2 mb-2" style="font-size:2.5rem; opacity:.3;"></i>
                        <p class="mb-0" style="font-size:.85rem;">{{ __("dashboard.no_data_available") }}</p>
                    </div>
                @else
                    {!! $tripsByType->container() !!}
                @endif
            </div>
        </div>
    </div>
</div>
{{-- Row 6: Users by Type + Trip Cancellation Rate + Drivers by Status ───── --}}
<div class="row mb-4">
    <div class="col-md-4 mb-4 mb-md-0">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-title mb-0">{{ __("dashboard.users_by_type") }}</h6>
                @if($usersByTypeEmpty)
                    <div class="d-flex flex-column align-items-center justify-content-center text-muted py-5">
                        <i class="bx bx-pie-chart-alt-2 mb-2" style="font-size:2.5rem; opacity:.3;"></i>
                        <p class="mb-0" style="font-size:.85rem;">{{ __("dashboard.no_data_available") }}</p>
                    </div>
                @else
                    {!! $usersByType->container() !!}
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4 mb-md-0">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-title mb-0">{{ __("dashboard.trip_cancellation_rate") }}</h6>
                @if($tripCancellationRateEmpty)
                    <div class="d-flex flex-column align-items-center justify-content-center text-muted py-5">
                        <i class="bx bx-pie-chart-alt-2 mb-2" style="font-size:2.5rem; opacity:.3;"></i>
                        <p class="mb-0" style="font-size:.85rem;">{{ __("dashboard.no_data_available") }}</p>
                    </div>
                @else
                    {!! $tripCancellationRate->container() !!}
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-title mb-0">{{ __("dashboard.drivers_by_status") }}</h6>
                @if($driversByStatusEmpty)
                    <div class="d-flex flex-column align-items-center justify-content-center text-muted py-5">
                        <i class="bx bx-pie-chart-alt-2 mb-2" style="font-size:2.5rem; opacity:.3;"></i>
                        <p class="mb-0" style="font-size:.85rem;">{{ __("dashboard.no_data_available") }}</p>
                    </div>
                @else
                    {!! $driversByStatus->container() !!}
                @endif
            </div>
        </div>
    </div>
</div>
</div>{{-- #pdf-page-1 --}}

<div id="pdf-page-2">
{{-- Row 7: Trips per Month + Users per Month ────────────────────────────── --}}
<div class="row mb-4">
    <div class="col-md-6 mb-4 mb-md-0">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-title mb-0">{{ __("dashboard.trips_per_month") }}</h6>
                <small class="text-muted">{{ __("dashboard.last_6_months") }}</small>
                @if($tripsPerMonthEmpty)
                    <div class="d-flex flex-column align-items-center justify-content-center text-muted py-5">
                        <i class="bx bx-line-chart mb-2" style="font-size:2.5rem; opacity:.3;"></i>
                        <p class="mb-0" style="font-size:.85rem;">{{ __("dashboard.no_data_available") }}</p>
                    </div>
                @else
                    {!! $tripsPerMonth->container() !!}
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-title mb-0">{{ __("dashboard.new_users_per_month") }}</h6>
                <small class="text-muted">{{ __("dashboard.last_6_months") }}</small>
                @if($usersPerMonthEmpty)
                    <div class="d-flex flex-column align-items-center justify-content-center text-muted py-5">
                        <i class="bx bx-line-chart mb-2" style="font-size:2.5rem; opacity:.3;"></i>
                        <p class="mb-0" style="font-size:.85rem;">{{ __("dashboard.no_data_available") }}</p>
                    </div>
                @else
                    {!! $usersPerMonth->container() !!}
                @endif
            </div>
        </div>
    </div>
</div>
{{-- Row 8: Revenue per Month ────────────────────────────────────────────── --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-0">{{ __("dashboard.revenue_per_month") }}</h6>
                <small class="text-muted">{{ __("dashboard.last_6_months") }}</small>
                @if($revenuePerMonthEmpty)
                    <div class="d-flex flex-column align-items-center justify-content-center text-muted py-5">
                        <i class="bx bx-line-chart mb-2" style="font-size:2.5rem; opacity:.3;"></i>
                        <p class="mb-0" style="font-size:.85rem;">{{ __("dashboard.no_data_available") }}</p>
                    </div>
                @else
                    {!! $revenuePerMonth->container() !!}
                @endif
            </div>
        </div>
    </div>
</div>
<script src="{{ asset("assets/vendor/libs/apex-charts/apexcharts.js") }}"></script>
@if(!$tripsPerMonthEmpty)        {!! $tripsPerMonth->script() !!}        @endif
@if(!$tripsByTypeEmpty)          {!! $tripsByType->script() !!}          @endif
@if(!$usersByTypeEmpty)          {!! $usersByType->script() !!}          @endif
@if(!$driversByStatusEmpty)      {!! $driversByStatus->script() !!}      @endif
@if(!$tripsByStatusEmpty)        {!! $tripsByStatus->script() !!}        @endif
@if(!$usersPerMonthEmpty)        {!! $usersPerMonth->script() !!}        @endif
@if(!$revenuePerMonthEmpty)      {!! $revenuePerMonth->script() !!}      @endif
@if(!$tripCancellationRateEmpty) {!! $tripCancellationRate->script() !!} @endif

</div>{{-- #pdf-page-2 --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
  document.getElementById('download-pdf-btn').addEventListener('click', async function () {
    const btn = this;
    const originalHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> {{ __("dashboard.pdf_generating") }}';

    const header = document.getElementById('pdf-header');
    header.style.display = 'block';

    try {
      const opts = {
        scale: 2,
        useCORS: true,
        allowTaint: true,
        backgroundColor: '#ffffff',
        logging: false,
        ignoreElements: (el) => el.id === 'pdf-exclude-bar',
      };

      const [canvas1, canvas2] = await Promise.all([
        html2canvas(document.getElementById('pdf-page-1'), opts),
        html2canvas(document.getElementById('pdf-page-2'), opts),
      ]);

      const { jsPDF } = window.jspdf;
      const margin = 10;
      const pdf = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
      const pageW   = pdf.internal.pageSize.getWidth();
      const usableW = pageW - margin * 2;

      // Page 1
      const h1 = usableW / (canvas1.width / canvas1.height);
      pdf.addImage(canvas1.toDataURL('image/png'), 'PNG', margin, margin, usableW, h1);

      // Page 2
      pdf.addPage();
      const h2 = usableW / (canvas2.width / canvas2.height);
      pdf.addImage(canvas2.toDataURL('image/png'), 'PNG', margin, margin, usableW, h2);

      pdf.save('dashboard-{{ now()->format("Y-m-d") }}.pdf');
    } finally {
      header.style.display = 'none';
      btn.disabled = false;
      btn.innerHTML = originalHtml;
    }
  });
</script>

@endsection
