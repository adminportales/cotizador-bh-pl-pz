<!-- Show Product modal -->
<div wire:ignore.self id="ver-product-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Informacion del producto
                </h3>
                <button type="button" onclick="customToggleModal('ver-product-modal', 0)"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
                @if ($showProduct)
                    <div class="row">
                        <div class="col-md-8">
                            @php
                                $productoShow = (object) json_decode($showProduct['product']);
                                $tecnicaShow = (object) json_decode($showProduct['technique']);
                            @endphp
                            <p class="m-0">Informacion del Producto: </p>
                            <p class="m-0"><b>Nombre: </b>{{ $productoShow->name }} </p>
                            <p class="m-0"><b>Descripcion: </b>{{ $productoShow->description }} </p>
                            <p class="m-0"><b>Precio: </b> $
                                @php
                                    $priceProduct = 0;
                                    if ($dataProduct) {
                                        $priceProduct = $dataProduct->price;
                                        if ($dataProduct->producto_promocion) {
                                            $priceProduct = round($priceProduct - $priceProduct * ($dataProduct->descuento / 100), 2);
                                        } else {
                                            $priceProduct = round($priceProduct - $priceProduct * ($dataProduct->provider->discount / 100), 2);
                                        }
                                    }
                                @endphp
                                {{ $priceProduct }}
                            </p>
                            @if (property_exists($productoShow, 'provider'))
                                @if ($productoShow->provider->company == 'Registro Personal')
                                    <p class="m-0"><b>Proveedor: </b>
                                        {{ $dataProduct->productAttributes->where('slug', 'proveedor')->first()
                                            ? $dataProduct->productAttributes->where('slug', 'proveedor')->first()->value
                                            : '' }}
                                    </p>
                                @else
                                    <p class="m-0"><b>Proveedor: </b>{{ $dataProduct->provider->company }} </p>
                                @endif
                            @endif
                            <br>
                            <p class="m-0">Informacion de la Tecnica: </p>
                            <p class="m-0"><b>Material: </b>{{ $tecnicaShow->material }} </p>
                            <p class="m-0"><b>Tecnica: </b>{{ $tecnicaShow->tecnica }} </p>
                            <p class="m-0"><b>Tamaño: </b>{{ $tecnicaShow->size }}</p>
                            <br>
                            <p class="m-0"><b>Numero de tintas o logos:</b> {{ $showProduct['color_logos'] }}
                            </p>
                            <p class="m-0"><b>Costo Indirecto:</b> $ {{ $showProduct['costo_indirecto'] }}</p>
                            <p class="m-0"><b>Margen de Utilidad:</b> {{ $showProduct['utilidad'] }} %</p>
                            <p class="m-0"><b>Dias de entrega:</b> {{ $showProduct['dias_entrega'] }} dias
                                {{ $showProduct['type_days'] == 1 ? 'habiles' : ($showProduct['type_days'] == 2 ? 'naturales' : '') }}
                            </p>
                            @if ($showProduct['quote_by_scales'])
                                <table class="table table-sm table-bordered m-0">
                                    <thead>
                                        <tr>
                                            <th>Cantidad</th>
                                            <th>Utilidad</th>
                                            <th>Impresion por tinta</th>
                                            <th>Unitario</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (json_decode($showProduct['scales_info']) as $item)
                                            <tr>
                                                <td>{{ $item->quantity }} pz</td>
                                                <td>{{ $item->utility }} %</td>
                                                <td>$
                                                    {{ number_format($item->tecniquePrice, 2, '.', ',') }}
                                                </td>
                                                <td>$
                                                    {{ number_format($item->unit_price, 2, '.', ',') }}
                                                </td>
                                                <td>$
                                                    {{ number_format($item->total_price, 2, '.', ',') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p class="m-0"><b>Precio de la tecnica:</b> $
                                    {{ $showProduct['prices_techniques'] }}</p>
                                <p class="m-0"><b>Precio Unitario:</b> $ {{ $showProduct['precio_unitario'] }}
                                </p>
                                <p class="m-0"><b>Cantidad:</b> {{ $showProduct['cantidad'] }} </p>
                                <p class="m-0"><b>Precio Total:</b> $ {{ $showProduct['precio_total'] }} </p>
                            @endif

                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <img src="{{ $productoShow->image }}" alt=""
                                    style="max-width: 200px; width: auto;max-height: 500px; height:auto;">
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="p-4 md:p-5 space-y-4 border-t">
                <button type="button" class="btn btn-secondary"
                    onclick="customToggleModal('ver-product-modal', 0)">Cerrar</button>
            </div>
        </div>
    </div>
</div>
