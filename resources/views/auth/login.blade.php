@php
  $customizerHidden = 'customizer-hide';
@endphp

@extends('layouts/layoutMaster')

@section('title', __('app.login'))

@section('vendor-style')
  <!-- Vendor -->
  <link rel="stylesheet" href="{{asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css')}}" />
@endsection

@section('page-style')
  <!-- Page -->
  <link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-auth.css')}}">
@endsection

@section('vendor-script')
  <script src="{{asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js')}}"></script>
  <script src="{{asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js')}}"></script>
@endsection

@section('page-script')
  <script src="{{asset('assets/js/pages-auth.js')}}"></script>
@endsection

@section('content')
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <!-- Register -->
      <div class="card">
      <div class="card-body">
        <!-- Logo -->
        <div class="app-brand justify-content-center">
        <a href="{{url('/admin')}}" class="app-brand-link gap-2">
          <span class="app-brand-logo demo">
             <img src="{{asset('assets/img/logo/1.png')}}" alt="Logo" class="w-px-40 h-auto rounded-2" width="50" />
          </span>
          <span class="app-brand-text demo text-body fw-bold text-capitalize">@lang('app-name')</span>
        </a>
        </div>
        <!-- /Logo -->
        <h4 class="mb-2">Bienvenue à @lang('app-name')  👋</h4>
        <p class="mb-4">S'il vous plaît, connectez-vous à votre compte et commencez l'aventure</p>

        <form id="formAuthentication" class="mb-3" action="{{route('login')}}" method="POST">
        @csrf
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="text" class="form-control" id="email" name="email" placeholder="Entrer votre email" autofocus>
        </div>
        <div class="mb-3 form-password-toggle">
          <div class="d-flex justify-content-between">
          <label class="form-label" for="password">Mot de passe</label>
{{--          <a href="javascript:void(0);">--}}
{{--            <small>Forgot Password?</small>--}}
{{--          </a>--}}
          </div>
          <div class="input-group input-group-merge">
          <input type="password" id="password" class="form-control" name="password"
            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
            aria-describedby="password" />
          <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
          </div>
        </div>
        <div class="mb-3">
          <div class="form-check">
          <input class="form-check-input" type="checkbox" id="remember-me">
          <label class="form-check-label" for="remember-me">
            Se souvenir de moi
          </label>
          </div>
        </div>
        <div class="mb-3">
          <button class="btn btn-primary d-grid w-100" type="submit">Se connecter</button>
        </div>
        </form>

{{--        <p class="text-center">--}}
{{--        <span>New on our platform?</span>--}}
{{--        <a href="{{url('auth/register-basic')}}">--}}
{{--          <span>Create an account</span>--}}
{{--        </a>--}}
{{--        </p>--}}

        <div class="divider my-4">
        <div class="divider-text">@lang('app-name')</div>
        </div>

{{--        <div class="d-flex justify-content-center">--}}
{{--        <a href="javascript:;" class="btn btn-icon btn-label-facebook me-3">--}}
{{--          <i class="tf-icons bx bxl-facebook"></i>--}}
{{--        </a>--}}

{{--        <a href="javascript:;" class="btn btn-icon btn-label-google-plus me-3">--}}
{{--          <i class="tf-icons bx bxl-google-plus"></i>--}}
{{--        </a>--}}

{{--        <a href="javascript:;" class="btn btn-icon btn-label-twitter">--}}
{{--          <i class="tf-icons bx bxl-twitter"></i>--}}
{{--        </a>--}}
{{--        </div>--}}
      </div>
      </div>
      <!-- /Register -->
    </div>
    </div>
  </div>
@endsection
