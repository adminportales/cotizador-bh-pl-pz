<div class="container ">
    <div class="row">
        <div class="col-md-3">
            <div class="card component-card_1 m-0 w-100">
                <div class="card-body">
                    <h5 class="card-title">Filtros de busqueda</h5>
                    <input wire:model='nombre' type="text" class="form-control" name="search" id="search"
                        placeholder="Nombre">
                    <br>
                    <input wire:model='sku' type="text" class="form-control" name="search" id="search"
                        placeholder="SKU">
                    <br>
                    <select wire:model='proveedor' name="proveedores" id="provee" class="form-control">
                        <option value="">Seleccione Proveedor...</option>
                        @foreach ($proveedores as $provider)
                            <option value="{{ $provider->id }}">{{ $provider->company }}</option>
                        @endforeach
                    </select>
                    <br>
                    <p class="mb-0">Precio</p>
                    <div class="d-flex align-items-center">
                        <input wire:model='precioMin' type="number" class="form-control" name="search" id="search"
                            placeholder="Precio Minimo" min="0" value="0">
                        -
                        <input wire:model='precioMax' type="number" class="form-control" name="search" id="search"
                            placeholder="Precio Maximo" value="{{ $price }}" max="{{ $price }}">
                    </div>
                    <br>
                    <p class="mb-0">Stock</p>
                    <div class="d-flex align-items-center">
                        <input wire:model='stockMin' type="number" class="form-control" placeholder="Stock Minimo"
                            min="0" value="0">
                        -
                        <input wire:model='stockMax' type="number" class="form-control" placeholder="Stock Maximo"
                            value="{{ $stock }}" max="{{ $stock }}">
                    </div>
                    <br>
                    <p class="mb-0">Ordenar por Stock</p>
                    <select wire:model='orderStock' name="orderStock" id="provee" class="form-control">
                        <option value="">Ninguno</option>
                        <option value="ASC">De menor a mayor</option>
                        <option value="DESC">De mayor a menor</option>
                    </select>
                    <br>
                    <p class="mb-0">Ordenar por Precio</p>
                    <select wire:model='orderPrice' name="orderPrice" id="provee" class="form-control">
                        <option value="">Ninguno</option>
                        <option value="ASC">De menor a mayor</option>
                        <option value="DESC">De mayor a menor</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-9">
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
                            <div class="card-body text-center shadow-sm">
                                @php
                                    $priceProduct = $row->price;
                                    if ($row->producto_promocion) {
                                        $priceProduct = round($priceProduct - $priceProduct * ($row->descuento / 100), 2);
                                    } else {
                                        $priceProduct = round($priceProduct - $priceProduct * ($row->provider->discount / 100), 2);
                                    }
                                @endphp
                                <img src="{{ $row->firstImage ? $row->firstImage->image_url : '' }}"
                                    class="card-img-top " alt="{{ $row->name }}"
                                    style="max-width: 100%; max-height: 150px; width: auto">
                                <h5 class="card-title" style="text-transform: capitalize">{{ Str::limit($row->name, 30, '...') }}</h5>
                                <p class=" m-0 pt-1"><strong>SKU:</strong> {{ $row->sku }}</p>
                                <div class="d-flex justify-content-between">
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
