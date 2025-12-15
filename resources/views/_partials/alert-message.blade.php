@if(session()->has('success'))
  <div class="bs-toast toast toast-placement-ex m-2 fade bg-primary bottom-0 end-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
    <div class="toast-header">
      <i class='bx bx-check-circle me-2'></i>
      <div class="me-auto fw-semibold">@lang('app.success')</div>
{{--      <small>11 mins ago</small>--}}
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      {{ session()->get('success') }}
    </div>
  </div>
@endif

@if(session()->has('error'))
  <div class="bs-toast toast toast-placement-ex m-2 fade bg-danger bottom-0 end-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
    <div class="toast-header">
      <i class='bx bx-error-circle me-2'></i>
      <div class="me-auto fw-semibold">@lang('app.error')</div>
{{--      <small>11 mins ago</small>--}}
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      {{ session()->get('error') }}
    </div>
  </div>
@endif

@if ($errors->any())
  <div class="bs-toast toast toast-placement-ex m-2 fade bg-danger bottom-0 end-0 show" role="alert" aria-live="assertive" aria-atomic="true" data-delay="2000">
    <div class="toast-header">
      <i class='bx bx-error-circle me-2'></i>
      <div class="me-auto fw-semibold">@lang('app.error')</div>
      {{--      <small>11 mins ago</small>--}}
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ __($error) }}</li>
        @endforeach
      </ul>
    </div>
  </div>
@endif

<script>
  // Hide the toast after the delay time
  document.addEventListener('DOMContentLoaded', function () {
    setTimeout(function () {
      var toasts = document.querySelectorAll('.toast');
      toasts.forEach(function (toast) {
        toast.classList.remove('show');
        toast.classList.add('hide');
      });
    }, 200000); // Delay set to 2000ms (2 seconds)
  });
</script>
