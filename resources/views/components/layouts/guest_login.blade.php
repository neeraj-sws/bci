<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    
         {{-- @php
                                    $organization = App\Models\OrganizationSetting::first();
                                @endphp
                                
    <title>{{$organization->organization_name ?? 'Login'}}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/' . $organization->logo->file) }}"> --}}
    
    
    <title>{{'Big Cats India'}}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/logo.png') }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="{{ asset('assets/css/loginform.css') }}?t={{ time() }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @livewireStyles
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
    </style>
</head>

<body>
    {{ $slot }}


    @livewireScripts
    <!-- Script to JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @include('components.includes.sweet-alert')
</body>

</html>
