<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    
     {{-- @php
                                    $organization = App\Models\OrganizationSetting::first();
                                @endphp 
                                 --}}
    {{-- <title>{{$organization->organization_name ?? 'Admin'}}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/' . $organization->logo->file) }}"> --}}
    
    <title>{{'Big Cats India'}}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/logo.png') }}">
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!--plugins-->
    <link href="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet">
    <!--<link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet">-->
    <link href="{{ asset('assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/css/app.css') }}?t={{ time() }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <!-- loader-->
    <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet">
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/semi-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/header-colors.css') }}">
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    {{-- <link href="{{ asset('css/bootstrap-extended.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">

<link
  href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css"
  rel="stylesheet"
/>



    <style>
        .modal {
            overflow-y: auto !important;
        }

        span.select2-selection.select2-selection--single {
            height: auto;
            border-color: #ced4da !important;
        }

        span.select2-selection__rendered {
            padding: 0.275rem 1rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 0.375rem !important;
        }

        .select2-dropdown {
            z-index: 999999 !important;
        }


        .ribbon-wrapper {
            width: 150px;
            height: 150px;
            position: absolute;
            top: 0;
            left: 0;
            overflow: hidden;
            z-index: 10;
        }

        .ribbon {
            position: absolute;
            display: block;
            width: 200px;
            padding: 10px 0;
            background: #f16302;
            color: white;
            text-align: center;
            font-weight: bold;
            transform: rotate(-45deg);
            top: 25px;
            left: -58px;
            font-size: 15px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
        }
        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
          -webkit-appearance: none;
          margin: 0;
        }
        /* Firefox */
        input[type=number] {
          -moz-appearance: textfield;
        }
    </style>
    @livewireStyles
    @stack('styles')

</head>

<body>
    <div id="app">
        <div class="wrapper">
            @if (Auth::guard('web')->user()->hasRole('admin'))
                @include('components.includes.common-header')
            @else
                @include('components.includes.user-header')
            @endif
            @include('components.includes.common-sidebar')
            <div class="page-wrapper">
                <div class="page-content">
                    {{ $slot }}
                </div>
            </div>
        </div>
        @include('components.includes.common-footer')
    </div>

    @livewireScripts
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!---------------|| Js Files ||--------------->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <!--plugins-->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>

    <!--app JS-->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('assets/plugins/chartjs/js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/chartjs/js/Chart.extension.js') }}"></script>
    <script src="{{ asset('assets/plugins/sparkline-charts/jquery.sparkline.min.js') }}"></script>
    <!--Morris JavaScript -->
    <script src="{{ asset('assets/plugins/raphael/raphael-min.js') }}"></script>
    <script src="{{ asset('assets/plugins/morris/js/morris.js') }}"></script>
    <script src="{{ asset('assets/js/nicEdit.js') }}"></script>

    <!--    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>-->
    <!--<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>-->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script
  src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js">
</script>

    <script src="https://cdn.tiny.cloud/1/703e60ik4bbf0tgpid8nx2ir9yzwu22hdo6ab11waghkcofx/tinymce/5/tinymce.min.js"
        referrerpolicy="origin"></script>
        
    {{-- <script>
        function initAllTinyMCEEditors() {
            document.querySelectorAll('textarea[data-editor][data-model]').forEach(el => {
                const id = el.id;
                const editorId = el.getAttribute("data-editor-id");
                const componentRoot = el.closest('[wire\\:id]');
                const componentId = componentRoot ? componentRoot.getAttribute('wire:id') : null;
                const uniqueKey = editorId.split('-').pop();
                if (tinymce.get(id)) {
                    tinymce.get(id).remove();
                }
                tinymce.init({
                    selector: `#${id}`,
                    autoresize_bottom_margin: 20,
                    menubar: false,
                    plugins: [
                        'advlist', 'autoresize', 'autolink', 'link', 'image', 'lists', 'charmap',
                        'preview', 'anchor',
                        'pagebreak', 'searchreplace', 'wordcount', 'visualblocks', 'visualchars',
                        'code',
                        'fullscreen', 'insertdatetime', 'media', 'table', 'emoticons', 'help'
                    ],
                    toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
                        'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
                        'forecolor backcolor emoticons | help',
                    image_toolbar: 'alignleft aligncenter alignright | imageoptions',
                    menu: {
                        favs: {
                            title: 'My Favorites',
                            items: 'code visualaid | searchreplace | emoticons'
                        }
                    },
                    menubar: 'favs file edit view insert format tools table help',
                    // content_css: 'css/content.css',
                    image_advtab: true,
                    image_title: true,
                    automatic_uploads: true,
                    image_caption: true,
                    image_dimensions: true,
                    setup: function(editor) {
                        editor.on('change keyup', function() {
                            const content = editor.getContent();
                            const model = el.dataset.model;
                            if (componentId && model) {
                                Livewire.find(componentId)?.set('value', content);
                            }
                        });
                    }
                });
            });
        }

        window.addEventListener('init-tinymce', function() {
            setTimeout(() => initAllTinyMCEEditors(), 100);
        });
    </script> --}}


    @include('components.includes.sweet-alert')
    @include('components.includes.select2')
    @include('components.includes.datepicker')
    @include('components.includes.timepicker')
    @include('components.includes.offcanvas')
    @stack('scripts')
</body>

</html>
