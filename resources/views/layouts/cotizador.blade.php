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
            background-color: #09343F;
            color: white;
        }

        .Drop-shadow {
            float: right;
        }

        .Drop-shadow img {
            filter: drop-shadow(1px 1px 1px #fff);
        }

        .btn-primary {
            background-color: #41C4E3;
            border-color: #41C4E3;
        }

        .btn-primary:hover,
        .btn-primary:active,
        .btn-primary:focus {
            background-color: #1FAFD3 !important;
            border-color: #1FAFD3 !important;
        }

        p,
        .table {
            color: #18839E !important;
        }

        .card-title,
        h5 {
            color: #0D4654 !important;
        }

        .container {
            max-width: 1360px;
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        @media(min-width:768px) {
            .container {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }
        }
    </style>
    @livewireStyles
</head>

<body>
    @php
        $companySession = auth()->user()->company_session;
        if (
            $companySession == null &&
            !auth()
                ->user()
                ->hasRole('admin')
        ) {
            auth()->user()->company_session = auth()->user()->info[0]->company_id;
            auth()
                ->user()
                ->save();
        }
    @endphp
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
