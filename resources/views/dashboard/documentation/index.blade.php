@extends('layouts/contentNavbarLayout')

@section('title', __('app.documentations'))

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
@endsection

@section('content')

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div class="d-flex flex-column justify-content-center">
            <h4 class="mb-1 mt-3">{{ __('app.documentations') }}</h4>
        </div>
        <div class="d-flex align-content-center flex-wrap gap-3">
            <button type="submit" form="form" class="btn btn-primary">
                <i class="bx bx-save me-1"></i>{{ __('app.send') }}
            </button>
        </div>
    </div>

    <div class="nav-align-top mb-4">
        <ul class="nav nav-pills mb-3" role="tablist">
            @foreach ($locales as $locale)
                <li class="nav-item">
                    <a href="{{ route('documentations.index', ['locale' => $locale]) }}"
                        class="nav-link {{ $selectedLocale === $locale ? 'active' : '' }}">
                        {{ strtoupper($locale) }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <form action="{{ route('documentations.store') }}" method="POST" id="form">
        @csrf
        <input type="hidden" name="locale" value="{{ $selectedLocale }}">

        <div class="nav-align-top mb-4">
            <ul class="nav nav-pills mb-3" role="tablist">
                @foreach ($documentations as $key => $documentation)
                    <li class="nav-item">
                        <button type="button" class="nav-link {{ $loop->first ? 'active' : '' }}" role="tab"
                            data-bs-toggle="tab" data-bs-target="#tab-{{ $key }}"
                            aria-controls="tab-{{ $key }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                            {{ __("app.{$key}") }}
                        </button>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content">
                @foreach ($documentations as $key => $documentation)
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $key }}"
                        role="tabpanel">
                        <div class="mb-3">
                            @php
                                $translations = $documentation->getTranslations('value');
                                $value = $translations[$selectedLocale] ?? '';
                                $rawValue = $documentation->getRawOriginal('value');
                                if ($value === '' && is_string($rawValue) && $rawValue !== '') {
                                    json_decode($rawValue, true);
                                    if (json_last_error() !== JSON_ERROR_NONE) {
                                        $defaultLocale = config('app.locale', 'en');
                                        if ($selectedLocale === $defaultLocale) {
                                            $value = $rawValue;
                                        }
                                    }
                                }
                            @endphp
                            <div id="editor-{{ $key }}">
                                {!! $value !!}
                            </div>
                            <input type="hidden" name="documentations[{{ $loop->index }}][key]"
                                value="{{ $key }}">
                            <input type="hidden" name="documentations[{{ $loop->index }}][value]"
                                id="content-{{ $key }}">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </form>


@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            const editors = {};

            @foreach ($documentations as $key => $documentation)
                {
                    const editor = new Quill('#editor-{{ $key }}', {
                        theme: 'snow',
                        modules: {
                            toolbar: [
                                [{
                                    font: []
                                }, {
                                    size: []
                                }],
                                ['bold', 'italic', 'underline', 'strike'],
                                [{
                                    color: []
                                }, {
                                    background: []
                                }],
                                [{
                                    script: 'super'
                                }, {
                                    script: 'sub'
                                }],
                                [{
                                    header: [1, 2, 3, 4, 5, 6, false]
                                }, 'blockquote', 'code-block'],
                                [{
                                    list: 'ordered'
                                }, {
                                    list: 'bullet'
                                }, {
                                    indent: '-1'
                                }, {
                                    indent: '+1'
                                }],
                                [{
                                    align: []
                                }, {
                                    direction: 'rtl'
                                }],
                                ['link', 'image', 'video'],
                                ['clean']
                            ]
                        }
                    });

                    editors['{{ $key }}'] = editor;

                    const $content = $('#content-{{ $key }}');
                    $content.val(editor.root.innerHTML);

                    editor.on('text-change', function() {
                        $content.val(editor.root.innerHTML);
                    });
                }
            @endforeach

            $('form').on('submit', function() {
                @foreach ($documentations as $key => $documentation)
                    $('#content-{{ $key }}').val(editors['{{ $key }}'].root.innerHTML);
                @endforeach
            });
        });
    </script>
@endsection
