@php
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\Route;
  $containerNav = $containerNav ?? 'container-fluid';
  $navbarDetached = ($navbarDetached ?? '');
@endphp

<!-- Navbar -->
@if(isset($navbarDetached) && $navbarDetached == 'navbar-detached')
  <nav
    class="layout-navbar {{$containerNav}} navbar navbar-expand-xl {{$navbarDetached}} align-items-center bg-navbar-theme"
    id="layout-navbar">
  @endif
  @if(isset($navbarDetached) && $navbarDetached == '')
    <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="{{$containerNav}}">
  @endif

      <!--  Brand demo (display only for navbar-full and hide on below xl) -->
      @if(isset($navbarFull))
      <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
      <a href="{{url('/admin')}}" class="app-brand-link gap-2">
        <span
        class="app-brand-logo demo">@include('_partials.macros', ["width" => 25, "withbg" => 'var(--bs-primary)'])</span>
        <span class="app-brand-text demo menu-text fw-bold">{{config('variables.templateName')}}</span>
      </a>

      @if(isset($menuHorizontal))
      <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
      </a>
    @endif
      </div>
    @endif

      <!-- ! Not required for layout-without-menu -->
      @if(!isset($navbarHideToggle))
      <div
      class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ? ' d-xl-none ' : '' }}">
      <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
        <i class="bx bx-menu bx-sm"></i>
      </a>
      </div>
    @endif

      <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        @if(!isset($menuHorizontal))
      <!-- Search -->
      <div class="navbar-nav align-items-center">
        <div class="nav-item navbar-search-wrapper mb-0">
        <a class="nav-item nav-link search-toggler px-0" href="javascript:void(0);">
          <i class="bx bx-search bx-sm"></i>
          <span class="d-none d-md-inline-block text-muted">@lang('app.search')</span>
        </a>
        </div>
      </div>
      <!-- /Search -->
    @endif

        <ul class="navbar-nav flex-row align-items-center ms-auto">
          @if(isset($menuHorizontal))
        <!-- Search -->
        <li class="nav-item navbar-search-wrapper me-2 me-xl-0">
        <a class="nav-link search-toggler" href="javascript:void(0);">
          <i class="bx bx-search bx-sm"></i>
        </a>
        </li>
        <!-- /Search -->
      @endif

          <!-- Language -->
          <li class="nav-item dropdown-language dropdown me-2 me-xl-0">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
              <i class='bx bx-globe bx-sm'></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item {{ app()->getLocale() === 'fr' ? 'active' : '' }}" href="{{url('fr')}}"
                   data-language="fr" data-text-direction="ltr">
                  <span class="align-middle">@lang('app.french')</span>
                </a>
              </li>
              <li>
                <a class="dropdown-item {{ app()->getLocale() === 'en' ? 'active' : '' }}" href="{{url('en')}}"
                  data-language="en" data-text-direction="ltr">
                  <span class="align-middle">@lang('app.english')</span>
                </a>
              </li>
              <li>
                <a class="dropdown-item {{ app()->getLocale() === 'ar' ? 'active' : '' }}" href="{{url('ar')}}"
                  data-language="ar" data-text-direction="rtl">
                  <span class="align-middle">@lang('app.arabic')</span>
                </a>
              </li>
            </ul>
          </li>
          <!--/ Language -->

          <!-- Quick links  -->
          <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"
              data-bs-auto-close="outside" aria-expanded="false">
              <i class='bx bx-grid-alt bx-sm'></i>
            </a>
            <div class="dropdown-menu dropdown-menu-end py-0">
              <div class="dropdown-menu-header border-bottom">
                <div class="dropdown-header d-flex align-items-center py-3">
                  <h5 class="text-body mb-0 me-auto">@lang('app.quick-access')</h5>
{{--                  <a href="javascript:void(0)" class="dropdown-shortcuts-add text-body" data-bs-toggle="tooltip"--}}
{{--                    data-bs-placement="top" title="Add shortcuts"><i class="bx bx-sm bx-plus-circle"></i></a>--}}
                </div>
              </div>
              <div class="dropdown-shortcuts-list scrollable-container">
                <div class="row row-bordered overflow-visible g-0">
                  @permission(\App\Support\Enum\Permissions::MANAGE_ROLES)
                  <div class="dropdown-shortcuts-item col">
                    <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                      <i class="bx bx-user-check fs-4"></i>
                    </span>
                    <a href="{{ route('roles.index') }}" class="stretched-link">@lang('app.roles')</a>
                    <small class="text-muted mb-0">@lang('app.manage-roles')</small>
                  </div>
                  @endpermission()
                  @permission(\App\Support\Enum\Permissions::MANAGE_PERMISSIONS)
                  <div class="dropdown-shortcuts-item col">
                    <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                      <i class="bx bx-check-shield fs-4"></i>
                    </span>
                    <a href="{{ route('permissions.index') }}" class="stretched-link">@lang('app.permissions')</a>
                    <small class="text-muted mb-0">@lang('app.manage-permissions')</small>
                  </div>
                  @endpermission()
                </div>
                <div class="row row-bordered overflow-visible g-0">
                  @permission(\App\Support\Enum\Permissions::MANAGE_SETTINGS)
                  <div class="dropdown-shortcuts-item col">
                    <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                      <i class="bx bx-cog fs-4"></i>
                    </span>
                    <a href="{{ route('settings.index') }}" class="stretched-link">@lang('app.settings')</a>
                    <small class="text-muted mb-0">@lang('app.manage-settings')</small>
                  </div>
                  @endpermission()
                  @permission(\App\Support\Enum\Permissions::MANAGE_DOCUMENTATIONS)
                  <div class="dropdown-shortcuts-item col">
                    <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                      <i class="bx bx-book fs-4"></i>
                    </span>
                    <a href="{{ route('documentations.index') }}" class="stretched-link">@lang('app.documentations')</a>
                    <small class="text-muted mb-0">@lang('app.manage-documentations')</small>
                  </div>
                  @endpermission()
                </div>
                <div class="row row-bordered overflow-visible g-0">
                  {{-- @permission(\App\Support\Enum\Permissions::MANAGE_USERS)
                  <div class="dropdown-shortcuts-item col">
                    <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                      <i class="bx bx-user fs-4"></i>
                    </span>
                    <a href="{{ route('users.index') }}" class="stretched-link">@lang('app.users')</a>
                    <small class="text-muted mb-0">@lang('app.manage-users')</small>
                  </div>
                  @endpermission() --}}
                  <div class="dropdown-shortcuts-item col">
                    <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                      <i class="bx bx-home fs-4"></i>
                    </span>
                    <a href="{{ url('/') }}" class="stretched-link">@lang('app.home')</a>
                    <small class="text-muted mb-0">@lang('app.landing')</small>
                  </div>
                  @permission(\App\Support\Enum\Permissions::MANAGE_NOTIFICATIONS)
                  <div class="dropdown-shortcuts-item col">
                    <span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                      <i class="bx bx-bell fs-4"></i>
                    </span>
                    <a href="{{ route('send-notification') }}" class="stretched-link">@lang('app.notifications')</a>
                    <small class="text-muted mb-0">@lang('app.send-notification')</small>
                  </div>
                  @endpermission()
                </div>
              </div>
            </div>
          </li>
          <!-- Quick links -->

          @if($configData['hasCustomizer'] == true)
        <!-- Style Switcher -->
        <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
          <i class='bx bx-sm'></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
          <li>
          <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
            <span class="align-middle"><i class='bx bx-sun me-2'></i>@lang('app.light')</span>
          </a>
          </li>
          <li>
          <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
            <span class="align-middle"><i class="bx bx-moon me-2"></i>@lang('app.dark')</span>
          </a>
          </li>
          <li>
          <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
            <span class="align-middle"><i class="bx bx-desktop me-2"></i>@lang('app.system')</span>
          </a>
          </li>
        </ul>
        </li>
        <!--/ Style Switcher -->
      @endif

          <!-- Notification -->
{{--          <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">--}}
{{--            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown"--}}
{{--              data-bs-auto-close="outside" aria-expanded="false">--}}
{{--              <i class="bx bx-bell bx-sm"></i>--}}
{{--              <span class="badge bg-danger rounded-pill badge-notifications">5</span>--}}
{{--            </a>--}}
{{--            <ul class="dropdown-menu dropdown-menu-end py-0">--}}
{{--              <li class="dropdown-menu-header border-bottom">--}}
{{--                <div class="dropdown-header d-flex align-items-center py-3">--}}
{{--                  <h5 class="text-body mb-0 me-auto">Notification</h5>--}}
{{--                  <a href="javascript:void(0)" class="dropdown-notifications-all text-body" data-bs-toggle="tooltip"--}}
{{--                    data-bs-placement="top" title="Mark all as read"><i class="bx fs-4 bx-envelope-open"></i></a>--}}
{{--                </div>--}}
{{--              </li>--}}
{{--              <li class="dropdown-notifications-list scrollable-container">--}}
{{--                <ul class="list-group list-group-flush">--}}
{{--                  --}}
{{--                </ul>--}}
{{--              </li>--}}
{{--              <li class="dropdown-menu-footer border-top p-3">--}}
{{--                <button class="btn btn-primary text-uppercase w-100">view all notifications</button>--}}
{{--              </li>--}}
{{--            </ul>--}}
{{--          </li>--}}
          <!--/ Notification -->

          <!-- User -->
          <li class="nav-item navbar-dropdown dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
              <div class="avatar avatar-online">
                <img src="{{auth()->user() ? auth()->user()->avatar_url : asset('assets/img/avatars/1.png') }}" alt
                  class="w-px-40 h-auto rounded-circle">
              </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item"
                  href="javascript:void(0);">
                  <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                      <div class="avatar avatar-online">
                        <img
                          src="{{ auth()->user() ? auth()->user()->avatar_url : asset('assets/img/avatars/1.png') }}"
                          alt class="w-px-40 h-auto rounded-circle">
                      </div>
                    </div>
                    <div class="flex-grow-1">
                      <span class="fw-medium d-block">
                        @if (Auth::check())
                          {{ auth()->user()->fullname }}
                       @else
                         John Doe
                        @endif
                      </span>
                      <small class="text-muted">{{auth()->user()->email}}</small>
                    </div>
                  </div>
                </a>
              </li>
              <li>
                <div class="dropdown-divider"></div>
              </li>
              <li>
                <a class="dropdown-item"
                  href="{{ url('admin/profile') }}">
                  <i class="bx bx-user me-2"></i>
                  <span class="align-middle">@lang('app.my_profile')</span>
                </a>
              </li>
              <li>
                <div class="dropdown-divider"></div>
              </li>
              @if (Auth::check())
          <li>
          <a class="dropdown-item text-danger" href="{{ url('admin/logout') }}">
            <i class='bx bx-power-off me-2 text-danger'></i>
            <span class="align-middle text-danger">@lang('app.logout')</span>
          </a>
          </li>
          @csrf
        @else
          <li>
          <a class="dropdown-item" href="{{ Route::has('login') ? route('login') : url('auth/login-basic') }}">
            <i class='bx bx-log-in me-2'></i>
            <span class="align-middle">Login</span>
          </a>
          </li>
        @endif
            </ul>
          </li>
          <!--/ User -->
        </ul>
      </div>

      <!-- Search Small Screens -->
      <div class="navbar-search-wrapper search-input-wrapper {{ isset($menuHorizontal) ? $containerNav : '' }} d-none">
        <input type="text" class="form-control search-input {{ isset($menuHorizontal) ? '' : $containerNav }} border-0"
          placeholder="@lang('app.search')" aria-label="@lang('app.search')">
        <i class="bx bx-x bx-sm search-toggler  cursor-pointer"></i>
      </div>

      @if(isset($navbarDetached) && $navbarDetached == '')
    </div>
  @endif
  </nav>
  <!-- / Navbar -->
