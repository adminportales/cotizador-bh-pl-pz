<div class="container ">
    <div class="row">
        <div class="col-md-3 d-md-none d-block mb-2">
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
            <!-- Modal -->
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
        <div class="col-md-3 d-none d-md-block">
            <div class="card component-card_1 m-0 w-100">
                <div class="card-body">
                    <p>Filtros de busqueda</p>
                    <input wire:model='nombre' type="text" class="form-control mb-2" name="search"
                        id="search" placeholder="Nombre">
                    <input wire:model='sku' type="text" class="form-control mb-2" name="search" id="search"
                        placeholder="SKU">
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
                        <input wire:model='stockMin' type="number" class="form-control" placeholder="Stock Minimo"
                            min="0" value="0">
                        -
                        <input wire:model='stockMax' type="number" class="form-control" placeholder="Stock Maximo"
                            value="{{ $stock }}" max="{{ $stock }}">
                    </div>
                    <p class="mb-0">Ordenar por Stock</p>
                    <select wire:model='orderStock' name="orderStock" id="provee" class="form-control mb-2">
                        <option value="">Ninguno</option>
                        <option value="ASC">De menor a mayor</option>
                        <option value="DESC">De mayor a menor</option>
                    </select>
                    <p class="mb-0">Ordenar por Precio</p>
                    <select wire:model='orderPrice' name="orderPrice" id="provee" class="form-control mb-2">
                        <option value="">Ninguno</option>
                        <option value="ASC">De menor a mayor</option>
                        <option value="DESC">De mayor a menor</option>
                    </select>
                    <button class="btn btn-primary btn-block" wire:click="limpiar">Limpiar Filtros</button>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="px-4 py-3 mb-1 shadow-sm d-none d-md-block">
                <div class="d-md-flex justify-content-between align-items-center">
                    <p class="m-0">¿Vas a cotizar un producto que no esta en el catalogo?</p>
                    <div>
                        <a href="{{ route('addProduct.cotizador') }}" class="btn btn-sm btn-info">Agregar Nuevo
                            Producto</a>
                        <a href="{{ route('listProducts.cotizador') }}" class="btn btn-sm btn-info">Ver Mis
                            Productos</a>
                    </div>
                </div>
            </div>
            <div class="position-relative">
                @php
                    $counter = $products->perPage() * $products->currentPage() - $products->perPage() + 1;
                @endphp
                @if (count($products) <= 0)
                    <div class="d-flex flex-wrap justify-content-center align-items-center flex-column">
                        <p>No hay resultados de busqueda en la pagina actual</p>
                        @if (count($products->items()) == 0 && $products->currentPage() > 1)
                            <p>Click en la paginacion para ver mas resultados</p>
                        @endif
                    </div>
                @endif
                <div class="position-absolute w-100 d-flex justify-content-center" style="top: 40%;z-index: 100;">
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
                <div class="row " wire:loading.class="opacity-70">
                    @foreach ($products as $row)
                        <div class="col-md-4 col-lg-3 col-sm-6  d-flex justify-content-center">
                            <div class="card product-info">
                                <div class="card-body text-center shadow-sm p-2">
                                    @php
                                        $priceProduct = $row->price;
                                        if ($row->producto_promocion) {
                                            $priceProduct = round($priceProduct - $priceProduct * ($row->descuento / 100), 2);
                                        } else {
                                            $priceProduct = round($priceProduct - $priceProduct * ($row->provider->discount / 100), 2);
                                        }
                                    @endphp

                                    <div class="d-flex flex-row flex-sm-column">
                                        <div class="text-center" style="height: 150px;" {{-- wire:click='showPreview({{ $row->id }})' --}}>
                                            <img src="{{ $row->firstImage ? $row->firstImage->image_url : '' }}"
                                                class="card-img-top " alt="{{ $row->name }}"
                                                style="width: auto; max-width: 150px; max-height: 150px; height: auto">
                                        </div>
                                        <div class="info-products">
                                            <h5 class="card-title m-0" style="text-transform: capitalize">
                                                {{ Str::limit($row->name, 22, '...') }}</h5>
                                            <p class=" m-0 pt-1" style="font-size: 16px"><strong>SKU:</strong>
                                                {{ $row->sku }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="text-left">
                                                    <p class=" m-0" style="font-weight: bold">$
                                                        {{ round($priceProduct / ((100 - $utilidad) / 100), 2) }}</p>
                                                    <p class="m-0" style="font-size: 16px">Disponible: <span
                                                            style="font-weight: bold">{{ $row->stock }}</span></p>
                                                </div>
                                                <a href="#" wire:click="cotizar({{ $row->id }})"
                                                    class="btn btn-sm btn-primary">
                                                    Cotizar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="d-flex d-sm-none justify-content-center">
                    {{ $products->onEachSide(0)->links() }}
                </div>
                <div class="d-none d-sm-flex justify-content-center">
                    {{ $products->onEachSide(3)->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
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
