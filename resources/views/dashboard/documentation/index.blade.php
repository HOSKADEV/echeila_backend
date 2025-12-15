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


    <form action="{{ route('documentations.store') }}" method="POST" id="form">
        @csrf

        <div class="nav-align-top mb-4">
            <ul class="nav nav-pills mb-3" role="tablist">
                @foreach ($documentations as $key => $value)
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
                @foreach ($documentations as $key => $value)
                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="tab-{{ $key }}"
                        role="tabpanel">
                        <div class="mb-3">
                            <div id="editor-{{ $key }}">
                                {!! $value ?? '' !!}
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
            // Initialize Quill editors for each documentation type
            const editors = {};

            @foreach ($documentations as $key => $value)
                editors['{{ $key }}'] = new Quill('#editor-{{ $key }}', {
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

                // Set initial content
                const $content{{ $key }} = $('#content-{{ $key }}');
                $content{{ $key }}.val(editors['{{ $key }}'].root.innerHTML);

                // Update hidden input on text change
                editors['{{ $key }}'].on('text-change', function() {
                    $content{{ $key }}.val(editors['{{ $key }}'].root.innerHTML);
                });
            @endforeach

            // Ensure content is updated before form submission
            $('form').on('submit', function() {
                @foreach ($documentations as $key => $value)
                    $('#content-{{ $key }}').val(editors['{{ $key }}'].root.innerHTML);
                @endforeach
            });
        });
    </script>
@endsection
