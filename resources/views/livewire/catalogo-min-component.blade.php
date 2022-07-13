<div>
    @if (trim($producto) == '')
        <div class="row">
            <div class="col-md-12">
                <input wire:model='nombre' type="text" class="form-control" name="search" id="search"
                    placeholder="Buscar Unicamente Por Nombre">
                <br>
            </div>
        </div>
        <div class="row">
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
            @foreach ($products as $row)
                <div class="col-md-4 d-flex justify-content-center">
                    <div class="card component-card_2 mb-4" style="width: 14rem;">
                        <div class="card-body">
                            <div class="h-100 d-flex justify-content-between flex-column">
                                <div>
                                    @php
                                        $priceProduct = $row->price;
                                        if ($row->producto_promocion) {
                                            $priceProduct = round($priceProduct - $priceProduct * ($row->descuento / 100), 2);
                                        } else {
                                            $priceProduct = round($priceProduct - $priceProduct * ($row->provider->discount / 100), 2);
                                        }
                                    @endphp
                                    <img src="{{ $row->firstImage ? $row->firstImage->image_url : asset('img/default.jpg') }}"
                                        class="card-img-top" alt="{{ $row->name }}">
                                    <h5 class="card-title" style="text-transform: capitalize">
                                        {{ $row->name }}
                                    </h5>
                                    <div class="d-flex justify-content-between">
                                        <p class=" m-0 pt-1">Stock: {{ $row->stock }}</p>
                                        <p class=" m-0 pt-1">$
                                            {{ round($priceProduct + $priceProduct * ($utilidad / 100), 2) }}
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <button class="btn btn-primary mb-2 btn-block"
                                        wire:click="seleccionarProducto({{ $row }})"> Seleccionar </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="d-flex justify-content-center w-100">
                {{ $products->links() }}
            </div>
        </div>
    @else
        {{ $producto }}
        <div style="width: 20px; cursor: pointer;" wire:click="regresar">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </div>
    @endif

</div>
