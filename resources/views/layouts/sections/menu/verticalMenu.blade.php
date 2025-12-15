@php
  use Illuminate\Support\Facades\Route;
  use Illuminate\Support\Facades\Request;
  use App\Support\Enum\Roles;
  $configData = Helper::appClasses();
@endphp

<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">

  <!-- ! Hide app brand if navbar-full -->
  @if(!isset($navbarFull))
    <div class="app-brand demo px-4">
    <a href="{{url('/admin')}}" class="app-brand-link">
      <span class="app-brand-logo demo avatar">
        <img src="{{asset('assets/img/logo/1.png')}}" alt="Logo" class="rounded-2"/>
      </span>
      <span class="app-brand-text demo menu-text fw-bold ms-2 text-capitalize">@lang('app-name')</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
      <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
    </a>
    </div>
  @endif

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner">
    @foreach ($menuData->verticalMenu as $menu)

      {{-- adding active and open class if child is active --}}

      {{-- menu headers --}}
      @if (isset($menu->menuHeader))
        @if (auth()->user()->hasRole([Roles::SUPER_ADMIN, Roles::ADMIN]))
        <li class="menu-header small text-uppercase mt-0 mb-0">
        <span class="menu-header-text">{{ __($menu->menuHeader) }}</span>
        </li>
        @endif
      @else

        {{-- active menu method --}}
        @php
        $activeClass = null;
        $currentRouteName = Route::currentRouteName();
        $currentPath = request()->path();

        if (!isset($menu->submenu)) {
        if (gettype($menu->slug) === 'array') {
        foreach ($menu->slug as $slug) {
        // Check both route name and URL path
        if ((str_contains($currentRouteName, $slug) and strpos($currentRouteName, $slug) === 0) ||
            (str_contains($currentPath, $slug) and strpos($currentPath, $slug) === 0)) {
        $activeClass = 'active';
        }
        }
        } else {
        // Check both route name and URL path
        if ((str_contains($currentRouteName, $menu->slug) and strpos($currentRouteName, $menu->slug) === 0) ||
            (str_contains($currentPath, $menu->slug) and strpos($currentPath, $menu->slug) === 0)) {
        $activeClass = 'active';
        }
        }
        } elseif (isset($menu->submenu)) {
        if (gettype($menu->slug) === 'array') {
        foreach ($menu->slug as $slug) {
        // Check both route name and URL path
        if ((str_contains($currentRouteName, $slug) and strpos($currentRouteName, $slug) === 0) ||
            (str_contains($currentPath, $slug) and strpos($currentPath, $slug) === 0)) {
        $activeClass = 'active open';
        }
        }
        } else {
        // Check both route name and URL path
        if ((str_contains($currentRouteName, $menu->slug) and strpos($currentRouteName, $menu->slug) === 0) ||
            (str_contains($currentPath, $menu->slug) and strpos($currentPath, $menu->slug) === 0)) {
        $activeClass = 'active open';
        }
        }
        }
        @endphp

        {{-- main menu --}}
        @php
        $hasPermission = empty($menu->permission) || (
        is_array($menu->permission)
        ? collect($menu->permission)->some(fn($perm) => auth()->user()?->hasPermissionTo($perm))
        : auth()->user()?->hasPermissionTo($menu->permission)
        );
        @endphp

        @if ($hasPermission)
        <li class="menu-item {{$activeClass}}">
        <a href="{{ isset($menu->url) ? url($menu->url) : (isset($menu->route) ? route($menu->route) : 'javascript:void(0);') }}"
        class="{{ isset($menu->submenu) ? 'menu-link menu-toggle' : 'menu-link' }}" @if (isset($menu->target) and !empty($menu->target)) target="_blank" @endif>
        @isset($menu->icon)
        <i class="{{ $menu->icon }}"></i>
        @endisset
        <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
        </a>
        {{-- submenu --}}
        @isset($menu->submenu)
        @include('layouts.sections.menu.submenu', ['menu' => $menu->submenu])
        @endisset
        </li>
        @endif
      @endif
  @endforeach
  </ul>

</aside>