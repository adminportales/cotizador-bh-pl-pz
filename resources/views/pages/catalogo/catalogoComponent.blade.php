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
            <div class="row">
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
                                <p class="stock-relative m-0 mb-1 pt-1" style="font-size: 16px">Stock: <span
                                        style="font-weight: bold">{{ $row->stock }}</span></p>
                                <div class="d-flex flex-row flex-sm-column">
                                    <div class="text-center" style="height: 110px">
                                        <img src="{{ $row->firstImage ? $row->firstImage->image_url : '' }}"
                                            class="card-img-top " alt="{{ $row->name }}"
                                            style="width: auto; max-width: 100px; max-height: 110px; height: auto">
                                    </div>
                                    <div class="info-products">
                                        <h5 class="card-title m-0" style="text-transform: capitalize">
                                            {{ Str::limit($row->name, 22, '...') }}</h5>
                                        <p class=" m-0 pt-1" style="font-size: 16px"><strong>SKU:</strong>
                                            {{ $row->sku }}</p>
                                        <p class="m-0 mb-1 pt-1 d-sm-none" style="font-size: 16px">Stock: <span
                                                style="font-weight: bold">{{ $row->stock }}</span></p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class=" m-0 pt-1" style="font-weight: bold">$
                                                {{ round($priceProduct / ((100 - $utilidad) / 100), 2) }}</p>
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
    </style>
</div>
