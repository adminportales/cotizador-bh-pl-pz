<div class="">
    <div class="grid grid-cols-12 gap-3">
        <div class="md:hidden block mb-2">
            <div class="d-flex justify-content-between shadow-sm align-items-center p-1">
                <p class="m-0">+ {{ $products->total() }} resultados</p>
                <div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-link btn-sm dropdown-toggle" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            Mis Productos
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="{{ route('addProduct.cotizador') }}" class="dropdown-item">Nuevo
                                Producto</a>
                            <a href="{{ route('listProducts.cotizador') }}" class="dropdown-item">Ver Mis
                                Productos</a>
                        </div>
                    </div>
                    <button type="button" class="btn btn-link btn-sm dropdown-toggle" data-toggle="modal"
                        data-target="#exampleModal">
                        Busqueda
                    </button>
                </div>
            </div>
            <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Configuracion de Filtros</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Filtros de busqueda</p>
                            <input wire:model='nombre' type="text" class="form-control mb-2" name="search"
                                id="search" placeholder="Nombre">
                            <input wire:model='sku' type="text" class="form-control mb-2" name="search"
                                id="search" placeholder="SKU">
                            <input wire:model='color' type="text" class="form-control mb-2" name="color"
                                id="color" placeholder="Ingrese el color">
                            <input wire:model='category' type="text" class="form-control mb-2" name="category"
                                id="category" placeholder="Ingrese la familia">
                            <select wire:model='proveedor' name="proveedores" id="provee" class="form-control mb-2">
                                <option value="">Seleccione Proveedor...</option>
                                @foreach ($proveedores as $provider)
                                    <option value="{{ $provider->id }}">{{ $provider->company }}</option>
                                @endforeach
                            </select>
                            <select wire:model='type' name="type" id="type" class="form-control mb-2">
                                <option value="">Seleccione Tipo...</option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->type }}</option>
                                @endforeach
                            </select>
                            <p class="mb-0">Precio</p>
                            <div class="d-flex align-items-center mb-2">
                                <input wire:model='precioMin' type="number" class="form-control" name="search"
                                    id="search" placeholder="Precio Minimo" min="0" value="0">
                                -
                                <input wire:model='precioMax' type="number" class="form-control" name="search"
                                    id="search" placeholder="Precio Maximo" value="{{ $price }}"
                                    max="{{ $price }}">
                            </div>
                            <p class="mb-0">Stock</p>
                            <div class="d-flex align-items-center mb-2">
                                <input wire:model='stockMin' type="number" class="form-control"
                                    placeholder="Stock Minimo" min="0" value="0">
                                -
                                <input wire:model='stockMax' type="number" class="form-control"
                                    placeholder="Stock Maximo" value="{{ $stock }}"
                                    max="{{ $stock }}">
                            </div>
                            <p class="mb-0">Ordenar por Stock</p>
                            <select wire:model='orderStock' name="orderStock" id="provee"
                                class="form-control mb-2">
                                <option value="">Ninguno</option>
                                <option value="ASC">De menor a mayor</option>
                                <option value="DESC">De mayor a menor</option>
                            </select>
                            <p class="mb-0">Ordenar por Precio</p>
                            <select wire:model='orderPrice' name="orderPrice" id="provee"
                                class="form-control mb-2">
                                <option value="">Ninguno</option>
                                <option value="ASC">De menor a mayor</option>
                                <option value="DESC">De mayor a menor</option>
                            </select>
                            <div class="d-flex">
                                <button class="btn btn-primary w-50" wire:click="limpiar">Limpiar</button>
                                <button class="btn btn-success w-50" data-dismiss="modal">Buscar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-span-3 hidden md:block">
            <div class="w-full border-2 border-gray-200 py-4 px-5 rounded-lg">
                <div class="card-body">
                    <p>Filtros de busqueda</p>
                    <p>Buscar por</p>
                    <input wire:model='nombre' type="text"
                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                        name="search" id="search" placeholder="Nombre">
                    <input wire:model='sku' type="text"
                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500"
                        name="search" id="search" placeholder="SKU">
                    <input wire:model='color' type="text"
                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500"
                        name="color" id="color" placeholder="Ingrese el color">
                    <input wire:model='category' type="text"
                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500"
                        name="category" id="category" placeholder="Ingrese la familia">
                    <select wire:model='proveedor' name="proveedores" id="provee"
                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Seleccione Proveedor...</option>
                        @foreach ($proveedores as $provider)
                            <option value="{{ $provider->id }}">{{ $provider->company }}</option>
                        @endforeach
                    </select>
                    <select wire:model='type' name="type" id="type"
                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Seleccione Tipo...</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}">{{ $type->type }}</option>
                        @endforeach
                    </select>
                    <p class="mb-0">Precio</p>
                    <div class="flex items-center mb-2">
                        <input wire:model='precioMin' type="number"
                            class="block w-full p-3 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500"
                            name="search" id="search" placeholder="Precio Minimo" min="0"
                            value="0">
                        -
                        <input wire:model='precioMax' type="number"
                            class="block w-full p-3 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500"
                            name="search" id="search" placeholder="Precio Maximo" value="{{ $price }}"
                            max="{{ $price }}">
                    </div>
                    <p class="mb-0">Stock</p>
                    <div class="flex items-center mb-2">
                        <input wire:model='stockMin' type="number"
                            class="block w-full p-3 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Stock Minimo" min="0" value="0">
                        -
                        <input wire:model='stockMax' type="number"
                            class="block w-full p-3 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Stock Maximo" value="{{ $stock }}" max="{{ $stock }}">
                    </div>
                    <p class="mb-0">Ordenar por Stock</p>
                    <select wire:model='orderStock' name="orderStock" id="provee"
                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Ninguno</option>
                        <option value="ASC">De menor a mayor</option>
                        <option value="DESC">De mayor a menor</option>
                    </select>
                    <p class="mb-0">Ordenar por Precio</p>
                    <select wire:model='orderPrice' name="orderPrice" id="provee"
                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-gray-50 sm:text-xs focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Ninguno</option>
                        <option value="ASC">De menor a mayor</option>
                        <option value="DESC">De mayor a menor</option>
                    </select>
                    <button class="btn btn-primary btn-block" wire:click="limpiar">Limpiar Filtros</button>
                </div>
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

    <!-- Modal toggle -->
    <button data-modal-target="staticModal" data-modal-toggle="staticModal"
        class="block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
        type="button">
        Toggle modal
    </button>

    <!-- Main modal -->
    <div id="staticModal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Static modal
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
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

                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button data-modal-hide="staticModal" type="button"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">I
                        accept</button>
                    <button data-modal-hide="staticModal" type="button"
                        class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Decline</button>
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
