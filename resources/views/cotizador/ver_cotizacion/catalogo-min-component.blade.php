<div>
    <div class="{{ trim($producto) == '' ? '' : 'hidden' }}">
        <div class="row">
            <div class="col-md-12">
                <input wire:model='nombre' type="text"
                    class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                    name="search" id="search" placeholder="Buscar Unicamente Por Nombre">
                <br>
            </div>
        </div>
        <div class="grid grid-cols-12 gap-2 mb-3" wire:loading.class="opacity-70">
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
                <div class="sm:col-span-12 lg:col-span-4 md:col-span-6 col-span-12 flex justify-center">
                    <div class="border-2 border-gray-200 py-2 px-3 rounded-xl w-full h-full">
                        <div class="text-center shadow-sm p-2 h-full">
                            
                            @php

                                $product_type = $row->productAttributes->where('attribute', 'Tipo Descuento')->first();

                                $priceProduct = $row->price;
                                if ($row->producto_promocion) {
                                    $priceProduct = round($priceProduct - $priceProduct * ($row->descuento / 100), 2);
                                } else {
                                    $priceProduct = round($priceProduct - $priceProduct * ($row->provider->discount / 100), 2);
                                }

                                if ($product_type) {
                                    if($product_type->value == 'Outlet'){
                                        $priceProduct = round($priceProduct - $priceProduct * (30 / 100), 2);
                                    }
                                } 

                            @endphp
                            <div class="flex flex-row sm:flex-col sm:justify-between h-full ">
                                <div class="flex justify-center" style="height: 150px;">
                                    <img src="{{ $row->firstImage ? $row->firstImage->image_url : '' }}"
                                        alt="{{ $row->name }}" class="text-center"
                                        style="width: auto; max-width: 150px; max-height: 150px; height: auto">
                                </div>
                                <div class="flex flex-col sm:text-center sm:flex-grow flex-grow-0 text-left">
                                    <h5 class="text-lg font-medium m-0" style="text-transform: capitalize">
                                        {{ Str::limit($row->name, 22, '...') }}</h5>
                                    <p class="m-0 pt-0" style="font-size: 16px">SKU: {{ $row->sku }}</p>
                                    <div class="flex justify-between items-center mb-2 flex-grow">
                                        <div class="text-left">
                                            <p class=" m-0">$
                                                {{ round($priceProduct / ((100 - $utilidad) / 100), 2) }}</p>
                                            <p class="m-0" style="font-size: 16px">Disponible:
                                                <span class="font-bold">{{ $row->stock }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <button wire:click="seleccionarProducto({{ $row }})"
                                        class="block text-white bg-blue-500 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm px-5 py-2.5 text-center">
                                        Seleccionar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex justify-center">
            {{ $products->onEachSide(0)->links() }}
        </div>
    </div>
    <div class="{{ trim($producto) == '' ? 'hidden' : '' }}">
        <div style="width: 20px; cursor: pointer;">
            <div wire:click="regresar()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </div>
        </div>
        <br>
        @if (!trim($producto) == '')
            @php
                $priceProduct = $producto->price;
                if ($producto->producto_promocion) {
                    $priceProduct = round($priceProduct - $priceProduct * ($producto->descuento / 100), 2);
                } else {
                    $priceProduct = round($priceProduct - $priceProduct * ($producto->provider->discount / 100), 2);
                }
            @endphp
            <div class="mb-3">
                <div class="img-container w-25 text-center">
                    <img id="imgBox" style="max-width: 150px; max-height: 200px"
                        src="{{ $producto->firstImage ? $producto->firstImage->image_url : asset('img/default.jpg') }}"
                        class="img-fluid" alt="imagen">
                </div>
                <div class="flex items-center justify-between">
                    <p class="font-bold">{{ $producto->name }}</p>
                    @if ($producto->precio_unico)
                        <div>
                            <p class="text-lg font-bold">
                                $ {{ round($priceProduct + $priceProduct * ($utilidad / 100), 2) }}</p>
                            </p>
                        </div>
                    @endif
                </div>
                <div class="flex items-center justify-between">
                    <div class="grow ">
                        <div class="hidden md:block">
                            <p class="my-1">SKU Interno: {{ $producto->internal_sku }}</p>
                            <p class="my-1">SKU Proveedor: {{ $producto->sku }}</p>
                        </div>
                        <div class="block md:hidden space-y-1 mb-2">
                            <div>
                                <p>Sku Interno:</p>
                                <p>{{ $producto->internal_sku }}</p>
                            </div>
                            <div>
                                <p>Sku Proveedor:</p>
                                <p>{{ $producto->sku }}</p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="text-green-500">Disponibles:<strong> {{ $producto->stock }}</strong> </p>
                    </div>
                </div>
                <p>{{ $producto->description }}</p>
            </div>
            @livewire('components.formulario-de-cotizacion', ['productNewAdd' => $producto])
        @endif
    </div>
</div>
