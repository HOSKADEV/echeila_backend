@extends('layouts/contentNavbarLayout')

@section('title', __('app.notifications'))

  @section('vendor-style')
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/tagify/tagify.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/typeahead-js/typeahead.css')}}" />
  @endsection

  @section('vendor-script')
    <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/tagify/tagify.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/typeahead-js/typeahead.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/bloodhound/bloodhound.js')}}"></script>
  @endsection

@section('content')
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div class="d-flex flex-column justify-content-center">
            <h4 class="mb-1 mt-3">{{ __('app.notifications') }}</h4>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-3">
            <button type="submit" form="form" class="btn btn-primary">
                <i class="bx bx-send me-1"></i>{{ __('app.submit') }}
            </button>
        </div>
    </div>

  <form action="{{ route('send-notification.send') }}" method="POST" enctype="multipart/form-data" id="form">
    @csrf

    <div class="row">
      <div class="col-xl">
        <div class="card mb-4">
          <div class="card-body">
            <div class="row">
              <div class="mb-3 col-md-4">
                <label for="channels[]" class="form-label">@lang('app.channels')</label>
                <select name="channels[]" id="select2Multiple" class="select2 form-select" multiple required>
                  <option value="database" selected>@lang('app.database-notifications')</option>
                  <option value="fcm" selected>@lang('app.fcm-notifications')</option>
                </select>
              </div>
              <div class="mb-3 col-md-4">
                <label for="key" class="form-label">@lang('app.notification')</label>
                <select name="key" id="notification-select" class="form-select" required>
                  <option value="" selected disabled>@lang('app.select_option')</option>
                  @foreach(\App\Constants\NotificationMessages::customNotifications() as $notification)
                    <option value="{{ $notification }}">{{ __("app.{$notification}")}}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3 col-md-4">
                <label for="user_types[]" class="form-label">@lang('app.send_to')</label>
                <select name="user_types[]" id="select2UserTypes" class="select2 form-select" multiple required>
                  @foreach(\App\Constants\UserType::all(true) as $type => $label)
                    <option value="{{ $type }}" selected>{{ $label }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <!-- Hidden inputs for default values -->
            @foreach(\App\Constants\NotificationMessages::customNotifications() as $notification)
              <input type="hidden" id="default-title-en-{{ $notification }}" value="{{ \App\Constants\NotificationMessages::title($notification, 'en') }}">
              <input type="hidden" id="default-title-ar-{{ $notification }}" value="{{ \App\Constants\NotificationMessages::title($notification, 'ar') }}">
              <input type="hidden" id="default-title-fr-{{ $notification }}" value="{{ \App\Constants\NotificationMessages::title($notification, 'fr') }}">
              <input type="hidden" id="default-body-en-{{ $notification }}" value="{{ \App\Constants\NotificationMessages::body($notification, 'en') }}">
              <input type="hidden" id="default-body-ar-{{ $notification }}" value="{{ \App\Constants\NotificationMessages::body($notification, 'ar') }}">
              <input type="hidden" id="default-body-fr-{{ $notification }}" value="{{ \App\Constants\NotificationMessages::body($notification, 'fr') }}">
            @endforeach

            <!-- Title fields -->
            <div class="row">
              <h5 class="mb-3">@lang('app.notification_title')</h5>
              <div class="mb-3 col-md-4">
                <label for="title[en]" class="form-label">@lang('app.english')</label>
                <input type="text" class="form-control" id="title-en" name="title[en]" required>
              </div>
              <div class="mb-3 col-md-4">
                <label for="title[ar]" class="form-label">@lang('app.arabic')</label>
                <input type="text" class="form-control" id="title-ar" name="title[ar]" required>
              </div>
              <div class="mb-3 col-md-4">
                <label for="title[fr]" class="form-label">@lang('app.french')</label>
                <input type="text" class="form-control" id="title-fr" name="title[fr]" required>
              </div>
            </div>

            <!-- Body fields -->
            <div class="row">
              <h5 class="mb-3">@lang('app.notification_body')</h5>
              <div class="mb-3 col-md-4">
                <label for="body[en]" class="form-label">@lang('app.english')</label>
                <textarea class="form-control" id="body-en" name="body[en]" rows="3" required></textarea>
              </div>
              <div class="mb-3 col-md-4">
                <label for="body[ar]" class="form-label">@lang('app.arabic')</label>
                <textarea class="form-control" id="body-ar" name="body[ar]" rows="3" required></textarea>
              </div>
              <div class="mb-3 col-md-4">
                <label for="body[fr]" class="form-label">@lang('app.french')</label>
                <textarea class="form-control" id="body-fr" name="body[fr]" rows="3" required></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
@endsection
@section('page-script')
    <script>
      // JavaScript to update title and body fields when notification type changes
      $(document).ready(function() {

        $('.select2').select2({
          placeholder: "@lang('app.select_option')"
        });
        const $notificationSelect = $('#notification-select');
        updateNotificationFields();

        $notificationSelect.on('change', function() {
          updateNotificationFields();
        });

        function updateNotificationFields() {
          const selectedNotification = $notificationSelect.val();
          if (selectedNotification) {
            // Get default values from data attributes
            const titleEn = $(`#default-title-en-${selectedNotification}`).val();
            const titleAr = $(`#default-title-ar-${selectedNotification}`).val();
            const titleFr = $(`#default-title-fr-${selectedNotification}`).val();
            const bodyEn = $(`#default-body-en-${selectedNotification}`).val();
            const bodyAr = $(`#default-body-ar-${selectedNotification}`).val();
            const bodyFr = $(`#default-body-fr-${selectedNotification}`).val();

            // Set values to form fields
            $('#title-en').val(titleEn);
            $('#title-ar').val(titleAr);
            $('#title-fr').val(titleFr);
            $('#body-en').val(bodyEn);
            $('#body-ar').val(bodyAr);
            $('#body-fr').val(bodyFr);
          }
        }
      });
    </script>
@endsection
