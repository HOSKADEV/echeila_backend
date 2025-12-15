@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Privacy Policy')

@section('page-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/front-page-landing.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />

    <style>
        .documentation-card {
            border-color: rgba(255, 255, 255, 0.68) !important;
            background: rgba(255, 255, 255, 0.38) !important;
        }
    </style>
@endsection

@section('content')
    <section class="section-py landing-hero position-relative">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-center mb-4">@lang('app.privacy_policy' , [], 'fr')</h1>
                    <div class="card documentation-card">
                        <div class="card-body ql-content">
                            {!! $privacy_policy !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
