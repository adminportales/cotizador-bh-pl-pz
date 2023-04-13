<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @hasSection('title')
            @yield('title') |
        @endif {{ config('app.name', 'Laravel') }}
    </title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'Myriad Pro Regular';
            font-style: normal;
            font-weight: normal;
            src: local('Myriad Pro Regular'), url('/fonts/myriadpro/MYRIADPRO-REGULAR.woff') format('woff');
        }

        @font-face {
            font-family: 'Myriad Pro Bold';
            font-style: normal;
            font-weight: normal;
            src: local('Myriad Pro Bold'), url('/fonts/myriadpro/MYRIADPRO-BOLD.woff') format('woff');
        }

        .navbar {
            background-color: #0B1142;
            color: white;
        }

        .Drop-shadow {
            float: right;
        }

        .Drop-shadow img {
            filter: drop-shadow(1px 1px 1px #fff);
        }

        .btn-primary {
            background-color: #071689;
            border-color: #071689;
        }

        .btn-primary:hover,
        .btn-primary:active,
        .btn-primary:focus {
            background-color: #0B1142 !important;
            border-color: #0B1142 !important;
        }

        p,
        .table {
            color: #18839E !important;
        }

        .card-title,
        h5 {
            color: #0D4654 !important;
        }
    </style>
    @livewireStyles
</head>

<body>
  
    <div id="app">
        @include('layouts.components.navbar')
        @yield('content')
        @if (!auth()->user()->phone)
            @livewire('check-phone-component')
        @elseif(!auth()->user()->company)
            {{-- @livewire('check-company-component') --}}
        @endif
    </div>
    @livewireScripts
    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('assets/js/libs/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <script type="text/javascript">
        window.livewire.on('closeModal', () => {
            $('#createDataModal').modal('hide');
        });
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>

</html>
