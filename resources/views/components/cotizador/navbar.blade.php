<nav class="bg-primary-500">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <div class="flex items-center gap-x-3">
            <div class="flex">
                @if (auth()->user()->companySession)
                    <div class="{{ auth()->user()->companySession->name != 'PROMO LIFE' ? 'bg-white' : '' }} rounded-sm">
                        <img alt="logo" class="h-12 w-auto"
                            src="{{ asset('img') . '/' . auth()->user()->companySession->image }}">
                    </div>
                @endif
                <div class="w-full ml-3 text-white">
                    <h6 class="m-0 font-bold">
                        COTIZADOR {{ auth()->user()->companySession ? auth()->user()->companySession->name : '' }}
                    </h6>
                    <p class="m-0">Cotiza tus
                        Productos</p>
                </div>
            </div>
            @if (count(auth()->user()->info) > 1)
                <div class="">
                    <button id="dropdownNavbarLink" data-dropdown-toggle="dropdownNavbar"
                        class="flex items-center justify-between w-full py-2 pl-3 pr-4  text-white border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-secondary-500 md:p-0 md:w-auto">{{ auth()->user()->companySession->name }}
                        <svg class="w-2.5 h-2.5 ml-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="dropdownNavbar"
                        class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                        <ul class="py-2 text-sm text-gray-700 " aria-labelledby="dropdownLargeButton">

                            @foreach (auth()->user()->info as $companyInfo)
                                <li>
                                    <a href="{{ route('changeCompany.cotizador', ['company' => $companyInfo->company_id]) }}"
                                        class="block px-4 py-2 hover:bg-gray-100 {{ $companyInfo->company->id == auth()->user()->company_session ? 'bg-gray-300' : '' }}">{{ $companyInfo->company->name }}</a>
                                </li>
                            @endforeach

                        </ul>
                    </div>
                </div>
            @endif
        </div>
        <button data-collapse-toggle="navbar-multi-level" type="button"
            class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
            aria-controls="navbar-multi-level" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M1 1h15M1 7h15M1 13h15" />
            </svg>
        </button>
        <div class="hidden w-full md:block md:w-auto" id="navbar-multi-level">
            <ul
                class="flex flex-col font-medium p-4 md:p-0 mt-4 border rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:border-0 md:bg-transparent">
                <li>
                    <a href="{{ route('catalogo') }}"
                        class="block py-2 pl-3 pr-4 text-white rounded hover:bg-secondary-500 md:border-0  md:p-0"
                        aria-current="page">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                    </a>
                </li>
                <li>
                    @livewire('components.count-cart-quote')
                </li>
                <li>
                    <a href="{{ route('cotizaciones') }}"
                        class="block py-2 pl-3 pr-4 text-white rounded hover:border-secondary-500 md:border-0  md:p-0 hover:bg-transparent">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                        </svg>
                    </a>
                </li>
                <li>
                    <button id="navMenuLink" data-dropdown-toggle="navMenu"
                        class="flex items-center justify-between w-full py-2 pl-3 pr-4  text-white border-b border-gray-100 hover:bg-gray-50 md:hover:bg-transparent md:border-0 md:hover:text-secondary-500 md:p-0 md:w-auto">{{ auth()->user()->name }}
                        <svg class="w-2.5 h-2.5 ml-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg></button>
                    <!-- Dropdown menu -->
                    <div id="navMenu"
                        class="z-10 hidden font-normal bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                        <ul class="py-2 text-sm text-gray-700 " aria-labelledby="dropdownLargeButton">

                            @role('admin')
                                <a class="block px-4 py-2 hover:bg-gray-100 " href="{{ url('admin/') }}">
                                    Administrador
                                </a>
                            @endrole
                            <li>
                                <a href="{{ route('logout') }}" class="block px-4 py-2 hover:bg-gray-100 "
                                    onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">Salir</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
