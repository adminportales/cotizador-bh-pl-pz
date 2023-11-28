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

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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
        @include('components.cotizador.navbar')
        <div class="max-w-7xl mx-auto p-4">
            @yield('content')
        </div>
        @if (!auth()->user()->phone)
            @livewire('components.check-phone-component')
        @endif
        @if (!auth()->user()->companySession)
            @livewire('components.check-company-component')
        @endif
    </div>
    @livewireScripts
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>

</html>
