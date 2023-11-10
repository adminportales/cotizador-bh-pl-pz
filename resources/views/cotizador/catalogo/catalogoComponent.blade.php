<div class="">
    <div class="grid grid-cols-12 gap-3">
        <div class="col-span-12 md:hidden block mb-2">
            <div class="flex justify-between shadow-sm border border-gray-200 rounded-lg items-center p-1">
                <p class="m-0 grow">+ {{ $products->total() }} resultados</p>
                <div class="">
                    <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
                        class="rounded-md text-gray-900 bg-white focus:outline-none focus:ring-4 focus:ring-gray-200 text-sm px-3 py-2.5  flex items-center"
                        type="button"><p class="block">Mis Productos</p><svg class="w-2.5 h-2.5 ml-1" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 4 4 4-4" />
                        </svg>
                    </button>

                    <!-- Dropdown menu -->
                    <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 ">
                        <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownDefaultButton">
                            <li>
                                <a href="{{ route('addProduct.cotizador') }}"
                                    class="block px-4 py-2 hover:bg-gray-100">Nuevo Producto</a>
                            </li>
                            <li>
                                <a href="{{ route('listProducts.cotizador') }}"
                                    class="block px-4 py-2 hover:bg-gray-100">Ver Mis
                                    Productos</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <button data-modal-target="staticModal" data-modal-toggle="staticModal"
                    class="rounded-md text-gray-900 bg-white focus:outline-none focus:ring-4 focus:ring-gray-200 text-sm px-3 py-2.5 "
                    type="button">
                    Busqueda
                </button>
            </div>
        </div>
        <div class="col-span-3 hidden md:block">
            <div class="w-full border-2 border-gray-200 py-4 px-5 rounded-lg">
                @include('cotizador.catalogo.filter-section')
            </div>
        </div>
        <div class="col-span-12 md:col-span-9">
            <div class="w-full border-2 border-gray-200 py-4 px-5 rounded-lg md:block hidden mb-3">
                <div class="flex justify-between items-center">
                    <p class="m-0 text-lg font-semibold grow">¿Vas a cotizar un producto que no esta en el catalogo?
                    </p>
                    <div class="text-center flex lg:flex-row md:flex-col">
                        <a href="{{ route('addProduct.cotizador') }}"
                            class="inline-block text-white bg-green-500 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-xl text-sm px-5 py-2.5 text-center mr-2 mb-2 ">Agregar
                            Nuevo
                            Producto</a>
                        <a href="{{ route('listProducts.cotizador') }}"
                            class="inline-block text-white bg-blue-600 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm px-5 py-2.5 text-center mr-2 mb-2 ">Ver
                            Mis
                            Productos</a>
                    </div>
                </div>
            </div>
            <div class="relative border-2 border-gray-200 py-4 px-5 rounded-lg w-full">
                @php
                    $counter = $products->perPage() * $products->currentPage() - $products->perPage() + 1;
                @endphp
                @if (count($products) <= 0)
                    <div class="flex flex-wrap justify-center items-center flex-col">
                        <p>No hay resultados de busqueda en la pagina actual</p>
                        @if (count($products->items()) == 0 && $products->currentPage() > 1)
                            <p>Click en la paginacion para ver mas resultados</p>
                        @endif
                    </div>
                @endif
                <div class="absolute w-full flex justify-center" style="top: 40%;z-index: 100;">
                    <div wire:loading.flex>
                        <div class="sk-chase">
                            <div class="sk-chase-dot"></div>
                            <div class="sk-chase-dot"></div>
                            <div class="sk-chase-dot"></div>
                            <div class="sk-chase-dot"></div>
                            <div class="sk-chase-dot"></div>
                            <div class="sk-chase-dot"></div>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-12 gap-2 mb-3" wire:loading.class="opacity-70">
                    @foreach ($products as $row)
                        <div class="sm:col-span-6 lg:col-span-3 md:col-span-4 col-span-12 flex justify-center">
                            <div class="border-2 border-gray-200 py-2 px-3 rounded-xl w-full h-full">
                                <div class="text-center shadow-sm p-2 h-full">
                                    @php
                                        $priceProduct = $row->price;
                                        if ($row->producto_promocion) {
                                            $priceProduct = round($priceProduct - $priceProduct * ($row->descuento / 100), 2);
                                        } else {
                                            $priceProduct = round($priceProduct - $priceProduct * ($row->provider->discount / 100), 2);
                                        }
                                    @endphp

                                    <div class="flex flex-row sm:flex-col justify-between h-full">
                                        <div class="flex justify-center" style="height: 150px;">
                                            <img src="{{ $row->firstImage ? $row->firstImage->image_url : '' }}"
                                                alt="{{ $row->name }}" class="text-center"
                                                style="width: auto; max-width: 150px; max-height: 150px; height: auto">
                                        </div>
                                        <div class="info-products flex-grow">
                                            <h5 class="text-lg font-medium m-0" style="text-transform: capitalize">
                                                {{ Str::limit($row->name, 22, '...') }}</h5>
                                            <p class="m-0 pt-0" style="font-size: 16px">SKU: {{ $row->sku }}</p>
                                            <div class="flex justify-between items-center mb-2">
                                                <div class="text-left">
                                                    <p class=" m-0">$
                                                        {{ round($priceProduct / ((100 - $utilidad) / 100), 2) }}</p>
                                                    <p class="m-0" style="font-size: 16px">Disponible:
                                                        <span class="font-bold">{{ $row->stock }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                            <a href="#" wire:click="cotizar({{ $row->id }})"
                                                class="block text-white bg-blue-500 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm px-5 py-2.5 text-center">
                                                Cotizar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="flex md:hidden justify-center">
                    <div>
                        {{ $products->onEachSide(0)->links() }}
                    </div>
                </div>
                <div class="hidden md:flex justify-center">
                    <div>
                        {{ $products->onEachSide(3)->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main modal -->
    <div wire:ignore.self id="staticModal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Configuracion de Filtros
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center"
                        data-modal-hide="staticModal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6">
                    @include('cotizador.catalogo.filter-section')
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                    <button type="button" wire:click="limpiar"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center ">Limpiar</button>
                    <button type="button" data-modal-hide="staticModal"
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 ">Buscar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('showPreview', event => {
            $('#previewModal').modal('show');
            // alert(event.detail.images)
        });
        // event scroll-to-top
        window.addEventListener('scroll-to-top', event => {
            $('html, body').animate({
                scrollTop: 0
            }, 300);
        });
    </script>
    <style>
        .product-info {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .info-products {
            flex-grow: 1;
            text-align: left;
        }

        @media(min-width:576px) {
            .product-info {
                width: 14rem;
                margin-bottom: 1.5rem;
            }

            .info-products {
                flex-grow: 0;
                text-align: center;
            }

            .stock-relative {
                display: block !important;
            }
        }

        .stock-relative {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #fcfcfcf2;
            border-radius: 5px;
            padding: 0px 5px;
            display: none;
        }

        .sk-chase {
            width: 130px;
            height: 130px;
            position: relative;
            animation: sk-chase 2.5s infinite linear both;
        }

        .sk-chase-dot {
            width: 100%;
            height: 100%;
            position: absolute;
            left: 0;
            top: 0;
            animation: sk-chase-dot 2.0s infinite ease-in-out both;
        }

        .sk-chase-dot:before {
            content: '';
            display: block;
            width: 25%;
            height: 25%;
            background-color: #41C4E3;
            border-radius: 100%;
            animation: sk-chase-dot-before 2.0s infinite ease-in-out both;
        }

        .sk-chase-dot:nth-child(1) {
            animation-delay: -1.1s;
        }

        .sk-chase-dot:nth-child(2) {
            animation-delay: -1.0s;
        }

        .sk-chase-dot:nth-child(3) {
            animation-delay: -0.9s;
        }

        .sk-chase-dot:nth-child(4) {
            animation-delay: -0.8s;
        }

        .sk-chase-dot:nth-child(5) {
            animation-delay: -0.7s;
        }

        .sk-chase-dot:nth-child(6) {
            animation-delay: -0.6s;
        }

        .sk-chase-dot:nth-child(1):before {
            animation-delay: -1.1s;
        }

        .sk-chase-dot:nth-child(2):before {
            animation-delay: -1.0s;
        }

        .sk-chase-dot:nth-child(3):before {
            animation-delay: -0.9s;
        }

        .opacity-70 {
            opacity: 0.3;
        }

        .sk-chase-dot:nth-child(4):before {
            animation-delay: -0.8s;
        }

        .sk-chase-dot:nth-child(5):before {
            animation-delay: -0.7s;
        }

        .sk-chase-dot:nth-child(6):before {
            animation-delay: -0.6s;
        }

        @keyframes sk-chase {
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes sk-chase-dot {

            80%,
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes sk-chase-dot-before {
            50% {
                transform: scale(0.4);
            }

            100%,
            0% {
                transform: scale(1.0);
            }
        }
    </style>
</div>
