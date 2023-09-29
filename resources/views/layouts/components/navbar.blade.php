<style>
    .nav-link.text-white:hover {
        color: #1FAFD3 !important;
    }
</style>
<nav class="navbar navbar-expand-md topbar mb-2 static-top shadow h-auto d-none d-md-block">
    <div class="container">
        <a href="/" class="navbar-brand p-0">
            <div class="text-light d-flex align-items-center"
                style="gap: 20px">
                @if (auth()->user()->companySession)
                    <div class="p-1 {{ auth()->user()->companySession->name != 'PROMO LIFE' ? 'bg-white' : '' }} rounded m-1">
                        <img alt="logo" class="imagen" height="50"
                            src="{{ asset('img') . '/' . auth()->user()->companySession->image }}">
                    </div>
                @endif
                <div class="w-100">
                    <h6 class="m-0 text-white font-weight-bold" style="font-family:'Myriad Pro Bold';font-weight:bold;">
                        COTIZADOR {{ auth()->user()->companySession ? auth()->user()->companySession->name : '' }}
                    </h6>
                    <p class="m-0 text-white " style="font-family:'Myriad Pro Regular';font-weight:normal;">Cotiza tus
                        Productos</p>
                </div>
            </div>
        </a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                @if (count(auth()->user()->info) > 1)
                    <li>
                        <div class="dropdown">
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton"
                                style="background-color: transparent; color:white" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                {{ auth()->user()->companySession->name }}
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                @foreach (auth()->user()->info as $companyInfo)
                                    <a class="dropdown-item {{ $companyInfo->company->id == auth()->user()->company_session ? 'disabled' : '' }}"
                                        href="{{ route('changeCompany.cotizador', ['company' => $companyInfo->company_id]) }}">{{ $companyInfo->company->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </li>
                @endif

                <li>
                    <div class="dropdown text-center">
                        <button class="btn dropdown-toggle" type="button" id="dropdownCurrencyButton"
                            style="background-color: transparent; color:white" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            {{ session()->get('currency_type') ?? 'Nothing' }}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownCurrencyButton">
                            <a class="dropdown-item {{ session()->get('currency_type') == 'MXN' ? 'disabled' : '' }}"
                                href="{{ route('changeCurrency.cotizador', ['currency' => 'MXN']) }}">MXN</a>
                            <a class="dropdown-item {{ session()->get('currency_type') == 'USD' ? 'disabled' : '' }}"
                                href="{{ route('changeCurrency.cotizador', ['currency' => 'USD']) }}">USD</a>
                        </div>
                    </div>
                </li>
            </ul>
            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item d-flex align-items-center">
                    <a class="nav-link text-white" aria-current="page" href="{{ route('catalogo') }}"
                        data-toggle="tooltip" data-placement="bottom" title="Inicio">
                        <div style="width: 2rem">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                        </div>
                    </a>
                </li>
                <li class="nav-item d-flex align-items-center">
                    @livewire('count-cart-quote')
                </li>
                <li class="nav-item d-flex align-items-center">
                    <a class="nav-link text-white" aria-current="page" href="{{ route('cotizaciones') }}"
                        data-toggle="tooltip" data-placement="bottom" title="Lista de Cotizaciones">
                        <div style="width: 2rem">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                            </svg>
                        </div>
                    </a>
                </li>
                <div class="topbar-divider d-none d-md-block"></div>
                <!-- Nav Item - User Information -->
                @auth
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-inline text-white"> {{ Auth::user()->name }}</span>
                        </a>

                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                            aria-labelledby="userDropdown">
                            @role('admin')
                                <a class="dropdown-item" href="{{ url('admin/') }}">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Administrador
                                </a>
                            @endrole
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Salir
                            </a>
                        </div>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
<nav class="navbar d-block d-md-none mb-2">
    <h6 class="m-0 text-white font-weight-bold text-center m-2"
        style="font-family:'Myriad Pro Bold';font-weight:bold; font-size: 18px">
        COTIZADOR {{ auth()->user()->companySession ? auth()->user()->companySession->name : '' }}
    </h6>
    @if (count(auth()->user()->info) > 1)
        <div class="dropdown text-center">
            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton"
                style="background-color: transparent; color:white" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
                {{ auth()->user()->companySession->name }}
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                @foreach (auth()->user()->info as $companyInfo)
                    <a class="dropdown-item {{ $companyInfo->company->id == auth()->user()->company_session ? 'disabled' : '' }}"
                        href="{{ route('changeCompany.cotizador', ['company' => $companyInfo->company_id]) }}">{{ $companyInfo->company->name }}</a>
                @endforeach
            </div>
        </div>
    @endif
    <div class="dropdown text-center">
        <button class="btn dropdown-toggle" type="button" id="dropdownCurrencyButton"
            style="background-color: transparent; color:white" data-toggle="dropdown" aria-haspopup="true"
            aria-expanded="false">
            {{ session()->get('currency_type') ?? 'Nothing' }}
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownCurrencyButton">
            <a class="dropdown-item {{ session()->get('currency_type') == 'USD' ? 'disabled' : '' }}"
                href="{{ route('changeCurrency.cotizador', ['currency' => 'USD']) }}"></a>
        </div>
    </div>
    <div class="container">
        <li class="nav-item d-flex align-items-center">
            <a class="nav-link text-white" aria-current="page" href="{{ route('catalogo') }}" data-toggle="tooltip"
                data-placement="bottom" title="Inicio">
                <div style="width: 2rem">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                </div>
            </a>
        </li>
        <li class="nav-item d-flex align-items-center">
            @livewire('count-cart-quote')
        </li>
        <li class="nav-item d-flex align-items-center">
            <a class="nav-link text-white" aria-current="page" href="{{ route('cotizaciones') }}"
                data-toggle="tooltip" data-placement="bottom" title="Lista de Cotizaciones">
                <div style="width: 2rem">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                    </svg>
                </div>
            </a>
        </li>
        @auth
            <li class="nav-item d-flex align-items-center">
                <div class="dropdown show">
                    <a class="btn btn-link text-white" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div style="width: 2rem">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                        <p class="dropdown-item">{{ auth()->user()->name }}</p>
                        @role('admin')
                            <a class="dropdown-item" href="{{ url('admin/') }}">
                                <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                Administrador
                            </a>
                        @endrole
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Salir
                        </a>
                    </div>
                </div>
            </li>
        @endauth
    </div>
</nav>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">Desea salir del cotizador web?.
                <br>
                <br>
                <button class="btn btn-secondary" type="button" data-dismiss="modal">No</button>
                <a class="btn btn-primary" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
        document.getElementById('logout-form').submit();">
                    {{ __('Salir') }}
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
