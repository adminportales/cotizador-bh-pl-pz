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
                    {{-- <select wire:model='type' name="types" id="type" class="form-control mb-2">
                        <option value="">Importacion o Catalogo...</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->id }}">{{ $type->type }}</option>
                        @endforeach
                    </select> --}}
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
                    {{-- <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1"
                                value="option1">
                            <label class="form-check-label" for="inlineRadio1">Catalogo</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2"
                                value="option2">
                            <label class="form-check-label" for="inlineRadio2">Importacion</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                id="inlineRadio3" value="option2">
                            <label class="form-check-label" for="inlineRadio3">Productos de Referencia</label>
                        </div>
                    </div> --}}
                    <div>
                        <a href="{{ route('addProduct.cotizador') }}" class="btn btn-sm btn-success">Agregar Nuevo
                            Producto</a>
                        <!-- <a href="{{ route('listProducts.cotizador') }}" class="btn btn-sm btn-info">Ver Mis
                            Productos</a> -->
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
            <div class="row row-cols-2 row-cols-lg-3">
                @foreach ($products as $row)
                   
                <div class="col p-2">
                    <div class="card">
                        <div class="card-body text-center shadow-sm">
                            @php
                                $priceProduct = $row->price;
                                if ($row->producto_promocion) {
                                    $priceProduct = round($priceProduct - $priceProduct * ($row->descuento / 100), 2);
                                } else {
                                    $priceProduct = round($priceProduct - $priceProduct * ($row->provider->discount / 100), 2);
                                }
                            @endphp
                            <div class="text-center" style="height: 140px">
                                <img src="{{ $row->firstImage ? $row->firstImage->image_url : '' }}"
                                    class="card-img-top " alt="{{ $row->name }}"
                                    style="width: 100%; max-width: 100px; max-height: 140px; width: auto">
                            </div>
                            <h5 class="card-title" style="text-transform: capitalize">
                                {{ Str::limit($row->name, 30, '...') }}</h5>
                            <p class=" m-0 pt-1"><strong>SKU:</strong> {{ $row->sku }}</p>
                            <div class="">
                                <p class=" m-0 pt-1">Stock: {{ $row->stock }}</p>
                                <p class=" m-0 pt-1">$
                                    {{ round($priceProduct / ((100 - $utilidad) / 100), 2) }}</p>
                            </div>
                            <br>
                            <div>
                                <a href="{{ route('show.product', ['product' => $row->id]) }}"
                                    class="btn btn-primary mb-2 btn-block">
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
</div>
