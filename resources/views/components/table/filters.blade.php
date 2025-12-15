<h5 class="card-title">@lang('app.filter')</h5>
<div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">
    @foreach ($filters as $filter)
        @if ($filter['type'] ?? 'select' == 'date')
            <div class="{{ $filter['width'] ?? 'col-md-4' }}">
                <div class="input-group input-group-merge">
                    <input type="date" class="form-control filter-input dob-picker" id="{{ $filter['id'] }}"
                        name="{{ $filter['name'] }}" placeholder="{{ __($filter['label']) }}">
                    <span class="input-group-text cursor-pointer" id="{{ $filter['id'] }}_icon" title="Select date">
                        <i class="bx bx-calendar"></i>
                    </span>
                </div>
            </div>
        @else
            <div class="{{ $filter['width'] ?? 'col-md-4' }}">
                <select class="form-select text-capitalize filter-input" id="{{ $filter['id'] }}"
                    name="{{ $filter['name'] }}">
                    <option value="">{{ __($filter['label']) }} (@lang('app.all'))</option>
                    @foreach ($filter['options'] as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    @endforeach
</div>
