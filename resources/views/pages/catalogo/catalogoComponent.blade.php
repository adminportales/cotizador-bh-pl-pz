<div class="container ">
    <div class="row">
        <div class="col-md-3">
            <div class="card component-card_1 m-0 w-100">
                <div class="card-body">
                    <p>Filtros de busqueda</p>
                    <input wire:model='nombre' type="text" class="form-control mb-2" name="search" id="search"
                        placeholder="Nombre">
                    <input wire:model='sku' type="text" class="form-control mb-2" name="search" id="search"
                        placeholder="SKU">
                    <input wire:model='color' type="text" class="form-control mb-2" name="color" id="color"
                        placeholder="Ingrese el color">
                    <input wire:model='category' type="text" class="form-control mb-2" name="category" id="category"
                        placeholder="Ingrese la familia">
                    <select wire:model='proveedor' name="proveedores" id="provee" class="form-control mb-2">
                        <option value="">Seleccione Proveedor...</option>
                        @foreach ($proveedores as $provider)
                            <option value="{{ $provider->id }}">{{ $provider->company }}</option>
                        @endforeach
                    </select>
                    <p class="mb-0">Precio</p>
                    <div class="d-flex align-items-center mb-2">
                        <input wire:model='precioMin' type="number" class="form-control" name="search" id="search"
                            placeholder="Precio Minimo" min="0" value="0">
                        -
                        <input wire:model='precioMax' type="number" class="form-control" name="search" id="search"
                            placeholder="Precio Maximo" value="{{ $price }}" max="{{ $price }}">
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
            <div class="card px-4 py-3 mb-1 shadow-sm">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="m-0">¿Vas a cotizar un producto que no esta en el catalogo? </p>
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
                        <div class="card mb-4" style="width: 14rem;">
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
                                <div class="text-center" style="height: 110px">
                                    <img src="{{ $row->firstImage ? $row->firstImage->image_url : '' }}"
                                        class="card-img-top " alt="{{ $row->name }}"
                                        style="width: 100%; max-width: 100px; max-height: 110px; height: auto">
                                </div>
                                <h5 class="card-title m-0" style="text-transform: capitalize">
                                    {{ Str::limit($row->name, 22, '...') }}</h5>
                                <p class=" m-0 pt-1" style="font-size: 16px"><strong>SKU:</strong>
                                    {{ $row->sku }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class=" m-0 pt-1" style="font-weight: bold">$
                                        {{ round($priceProduct / ((100 - $utilidad) / 100), 2) }}</p>
                                    <a href="{{ route('show.product', ['product' => $row->id]) }}"
                                        class="btn btn-sm btn-primary">
                                        Cotizar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>
    </div>
    <style>
        .stock-relative {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #fcfcfcf2;
            border-radius: 5px;
            padding: 0px 5px;
        }
    </style>
</div>
