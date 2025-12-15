@php
  use Illuminate\Support\Facades\Route;
  $currentRouteName = Route::currentRouteName();
  $activeRoutes = ['front-pages-pricing', 'front-pages-payment', 'front-pages-checkout', 'front-pages-help-center'];
  $activeClass = in_array($currentRouteName, $activeRoutes) ? 'active' : '';
@endphp
<!-- Navbar: Start -->
<nav class="layout-navbar shadow-none py-0">
  <div class="container">
    <div class="navbar navbar-expand-lg landing-navbar px-3 px-md-4 ">
      <!-- Menu logo wrapper: Start -->
      <div class="navbar-brand app-brand demo d-flex py-0 me-4">
        <!-- Mobile menu toggle: Start-->
        <button class="navbar-toggler border-0 px-0 me-2" type="button" data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
          aria-label="Toggle navigation">
          <i class="tf-icons bx bx-menu bx-sm align-middle"></i>
        </button>
        <!-- Mobile menu toggle: End-->
        <a href="/" class="app-brand-link">
         <img src="{{ asset('assets/img/logo/1.png') }}" alt="Logo" class="w-px-40 h-auto rounded-2">
        </a>
      </div>
      <!-- Menu logo wrapper: End -->
      <!-- Menu wrapper: Start -->
      <div class="collapse navbar-collapse landing-nav-menu" id="navbarSupportedContent">
        <button class="navbar-toggler border-0 text-heading position-absolute end-0 top-0 scaleX-n1-rtl" type="button"
          data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
          aria-expanded="false" aria-label="Toggle navigation">
          <i class="tf-icons bx bx-x bx-sm"></i>
        </button>
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link fw-medium" aria-current="page" href="{{ url('/#landingHero') }}">Accueille</a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-medium" href="{{ url('/#landingAbout') }}">A propos de nous</a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-medium" href="{{ url('/#landingValues') }}">Nos valeurs </a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-medium" href="{{ url('/#landingMissions') }}">Nos Missions</a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-medium" href="{{ url('/#landingServices') }}">Nos services</a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-medium" href="{{ url('/#landingContact') }}">Contact</a>
          </li>
        </ul>
      </div>
      <div class="landing-menu-overlay d-lg-none"></div>
      <!-- Menu wrapper: End -->
      <!-- Toolbar: Start -->
      <ul class="navbar-nav flex-row align-items-center ms-auto">
        @if($configData['hasCustomizer'] == true)
      <!-- Style Switcher -->
      <li class="nav-item dropdown-style-switcher dropdown me-2 me-xl-0">
        <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
        <i class='bx bx-sm'></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-styles">
        <li>
          <a class="dropdown-item" href="javascript:void(0);" data-theme="light">
          <span class="align-middle"><i class='bx bx-sun me-2'></i>Light</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="javascript:void(0);" data-theme="dark">
          <span class="align-middle"><i class="bx bx-moon me-2"></i>Dark</span>
          </a>
        </li>
        <li>
          <a class="dropdown-item" href="javascript:void(0);" data-theme="system">
          <span class="align-middle"><i class="bx bx-desktop me-2"></i>System</span>
          </a>
        </li>
        </ul>
      </li>
      <!-- / Style Switcher-->
    @endif


      </ul>
      <!-- Toolbar: End -->
    </div>
  </div>
</nav>
<!-- Navbar: End -->
