@extends('layouts/contentNavbarLayout')

@section('title', __('app.settings'))

@section('content')
    <form class="form-horizontal" action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <h4 class="fw-bold py-3 mb-3">
            <span class="text-muted fw-light"></span> {{ __('settings.version') }}
        </h4>

        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('settings.android') }}</h5>
                        <small class="text-muted float-end">{{ __('settings.android_version') }}</small>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="android_version_number">{{ __('settings.version_number') }}</label>
                            <input type="text" class="form-control" id="android_version_number"
                                name="android_version_number" value="{{ $settings['android_version_number'] ?? '' }}" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="android_build_number">{{ __('settings.build_number') }}</label>
                            <input type="text" class="form-control" id="android_build_number" name="android_build_number"
                                value="{{ $settings['android_build_number'] ?? '' }}" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="android_priority">{{ __('settings.priority') }}</label>
                            <select class="form-select" id="android_priority" name="android_priority">
                                <option value="0">{{ __('settings.optional') }}</option>
                                <option value="1" @if ($settings['android_priority'] ?? '') selected @endif>
                                    {{ __('settings.required') }}</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="android_link">{{ __('settings.link') }}</label>
                            <textarea class="form-control" id="android_link" name="android_link">{{ $settings['android_link'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('settings.ios') }}</h5>
                        <small class="text-muted float-end">{{ __('settings.ios_version') }}</small>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="ios_version_number">{{ __('settings.version_number') }}</label>
                            <input type="text" class="form-control" id="ios_version_number" name="ios_version_number"
                                value="{{ $settings['ios_version_number'] ?? '' }}" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="ios_build_number">{{ __('settings.build_number') }}</label>
                            <input type="text" class="form-control" id="ios_build_number" name="ios_build_number"
                                value="{{ $settings['ios_build_number'] ?? '' }}" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="ios_priority">{{ __('settings.priority') }}</label>
                            <select class="form-select" id="ios_priority" name="ios_priority">
                                <option value="0">{{ __('settings.optional') }}</option>
                                <option value="1" @if ($settings['ios_priority'] ?? '') selected @endif>
                                    {{ __('settings.required') }}</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="ios_link">{{ __('settings.link') }}</label>
                            <textarea class="form-control" id="ios_link" name="ios_link">{{ $settings['android_link'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <h4 class="fw-bold py-3 mb-3">
            <span class="text-muted fw-light"></span> {{ __('settings.contact_information') }}
        </h4>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('settings.contact') }}</h5>
                        <small class="text-muted float-end">{{ __('settings.contact_info') }}</small>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="contact_phone">{{ __('settings.phone_number') }}</label>
                            <input type="tel" class="form-control" id="contact_phone" name="contact_phone"
                                value="{{ $settings['contact_phone'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="emergency_number">{{ __('settings.emergency_number') }}</label>
                            <input type="tel" class="form-control" id="emergency_number" name="emergency_number"
                                value="{{ $settings['emergency_number'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="contact_email">{{ __('settings.email') }}</label>
                            <input type="email" class="form-control" id="contact_email" name="contact_email"
                                value="{{ $settings['contact_email'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="contact_facebook">{{ __('settings.facebook') }}</label>
                            <input type="url" class="form-control" id="contact_facebook" name="contact_facebook"
                                value="{{ $settings['contact_facebook'] ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="contact_instagram">{{ __('settings.instagram') }}</label>
                            <input type="url" class="form-control" id="contact_instagram" name="contact_instagram"
                                value="{{ $settings['contact_instagram'] ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pricing Settings -->
        <h4 class="fw-bold py-3 mb-3">
            <span class="text-muted fw-light"></span> {{ __('settings.pricing_settings') }}
        </h4>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label" for="subscription_month_price">{{ __('settings.subscription_month_price') }}</label>
                        <div class="input-group">
                            <input type="number" step="0.01" class="form-control" id="subscription_month_price" name="subscription_month_price"
                                value="{{ $settings['subscription_month_price'] ?? '' }}">
                            <span class="input-group-text">{{ __('settings.da') }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="water_price_per_litre">{{ __('settings.water_price_per_litre') }}</label>
                        <div class="input-group">
                            <input type="number" step="0.01" class="form-control" id="water_price_per_litre" name="water_price_per_litre"
                                value="{{ $settings['water_price_per_litre'] ?? '' }}">
                            <span class="input-group-text">{{ __('settings.da') }}</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="cargo_price_per_kg">{{ __('settings.cargo_price_per_kg') }}</label>
                        <div class="input-group">
                            <input type="number" step="0.01" class="form-control" id="cargo_price_per_kg" name="cargo_price_per_kg"
                                value="{{ $settings['cargo_price_per_kg'] ?? '' }}">
                            <span class="input-group-text">{{ __('settings.da') }}</span>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="min_charge_amount">{{ __('settings.minimum_charge_amount') }}</label>
                        <div class="input-group">
                            <input type="number" step="0.01" class="form-control" id="min_charge_amount" name="min_charge_amount"
                                value="{{ $settings['min_charge_amount'] ?? '' }}">
                            <span class="input-group-text">{{ __('settings.da') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="max_withdraw_amount">{{ __('settings.maximum_withdraw_amount') }}</label>
                        <div class="input-group">
                            <input type="number" step="0.01" class="form-control" id="max_withdraw_amount" name="max_withdraw_amount"
                                value="{{ $settings['max_withdraw_amount'] ?? '' }}">
                            <span class="input-group-text">{{ __('settings.da') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-3" style="text-align: center">
            <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
        </div>
    </form>

@endsection
