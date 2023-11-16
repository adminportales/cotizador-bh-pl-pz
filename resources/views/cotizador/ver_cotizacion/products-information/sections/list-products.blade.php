<div class="relative overflow-x-auto my-2 shadow-md rounded-md">
    <table class="w-full text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th class="px-3 py-2">Imagen</th>
                <th class="px-3 py-2">Producto</th>
                <th class="px-3 py-2">Subtotal</th>
                <th class="px-3 py-2">Piezas</th>
                <th class="px-3 py-2">Total</th>
                <th class="px-3 py-2">...</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quote->latestQuotesUpdate->quoteProducts as $product)
                @php
                    $producto = (object) json_decode($product->product);
                    $tecnica = (object) json_decode($product->technique);
                    $visible = true;
                    $auxProduct = $product;

                    foreach ($listUpdateCurrent as $productUpdate) {
                        if ($product->id == $productUpdate['currentQuote_id']) {
                            $product = (object) $productUpdate;
                            $producto->image = ((object) json_decode($product->product))->image;
                            $product->id = $productUpdate['currentQuote_id'];
                        }
                    }
                    $sumPrecioTotal += $product->precio_total;

                    foreach ($listDeleteCurrent as $productDeleted) {
                        if ($product->id == $productDeleted['id']) {
                            $visible = false;
                            $subtotalSubstract += $product->precio_total;
                        }
                    }
                    if ($product->quote_by_scales) {
                        $quoteScales = true;
                    }
                @endphp
                <tr class="{{ !$visible ? 'd-none' : '' }}">
                    <td class="px-3 py-2 text-center">
                        <img src="{{ $producto->image == '' ? asset('img/default.jpg') : $producto->image }}"
                            style="max-height: 80px;height:auto;max-width: 70px;width:auto;" alt=""
                            srcset="">
                    </td>
                    <td class="px-3 py-2 ">
                        <p>{{ Str::limit($producto->name, 25, '...') }}</p>
                    </td>
                    @if (!$product->quote_by_scales)
                        <td class="px-3 py-2 ">
                            <p class="text-center">
                                ${{ number_format($product->precio_unitario, 2, '.', ',') }}</p>
                        </td>
                        <td class="px-3 py-2 ">
                            <p class="text-center"> {{ $product->cantidad }} piezas</p>
                        </td>
                        <td class="px-3 py-2 ">
                            <p class="text-center">
                                ${{ number_format($product->precio_total, 2, '.', ',') }}
                            </p>
                        </td>
                    @else
                        <td colspan="3" class="px-3 py-2 text-right">
                            <div class="relative overflow-x-auto my-2 shadow-md rounded-md">
                                <table class="w-full text-left text-sm rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead
                                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th class="px-2 py-1">Cantidad</th>
                                            <th class="px-2 py-1">Utilidad</th>
                                            <th class="px-2 py-1">Impresion por tinta</th>
                                            <th class="px-2 py-1">Unitario</th>
                                            <th class="px-2 py-1">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (json_decode($product->scales_info) as $item)
                                            <tr>
                                                <td class="px-2 py-1">
                                                    <p class="block">{{ $item->quantity }} pz</p>
                                                </td>
                                                <td class="px-2 py-1">
                                                    <p class="block">{{ $item->utility }} %</p>
                                                </td>
                                                <td class="px-2 py-1">
                                                    <p class="block">$
                                                        {{ number_format($item->tecniquePrice, 2, '.', ',') }}
                                                    </p>
                                                </td>
                                                <td class="px-2 py-1">
                                                    <p class="block">$
                                                        {{ number_format($item->unit_price, 2, '.', ',') }}
                                                    </p>
                                                </td>
                                                <td class="px-2 py-1">
                                                    <p class="block">$
                                                        {{ number_format($item->total_price, 2, '.', ',') }}
                                                    </p>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </td>
                    @endif
                    <td class=" px-3 py-2 align-middle">
                        <div class="flex  flex-col gap-x-2">
                            <button class="btn btn-info btn-sm" wire:click="verDetalles({{ $auxProduct }})">
                                <div style="width: 1rem">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                            </button>
                            @if ($puedeEditar)
                                <button class="btn btn-warning btn-sm"
                                    wire:click="editarProducto({{ $auxProduct }})">
                                    <div style="width: 1rem">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </div>
                                </button>
                                <button class="btn btn-danger btn-sm" wire:click="deleteProducto({{ $auxProduct }})">
                                    <div style="width: 1rem">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </div>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
            @if ($puedeEditar)
                @foreach ($listNewProducts as $newProduct)
                    <tr>
                        @php
                            $producto = json_decode($newProduct['product']);
                        @endphp
                        <td class="text-center">
                            <img src="{{ $producto->image == '' ? asset('img/default.jpg') : $producto->image }}"
                                style="max-height: 100px;height:auto;max-width: 80px;width:auto;" alt=""
                                srcset="">
                        </td>
                        <td class="">
                            <p>{{ $producto->name }}</p>
                        </td>

                        @if (!$newProduct['quote_by_scales'])
                            <td class="">
                                <p class="text-center">${{ $newProduct['precio_unitario'] }}
                                </p>
                            </td>
                            <td class="">
                                <p class="text-center"> {{ $newProduct['cantidad'] }} piezas
                                </p>
                            </td>
                            <td>
                                <p class="text-center">${{ $newProduct['precio_total'] }}</p>
                                @php
                                    $subtotalAdded += $newProduct['precio_total'];
                                @endphp
                            </td>
                        @else
                            <td colspan="3" class="text-right">
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
                                        @foreach (json_decode($newProduct['scales_info']) as $item)
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
                            </td>
                        @endif
                        <td class="text-center d-flex">
                            <button class="btn btn-danger btn-sm"
                                wire:click="deleteNewProducto({{ $newProduct['idNewQuote'] }})">
                                <div style="width: 1rem">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </div>
                            </button>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="6" class="text-center">
                        <div>
                            <button class="bg-gray-200 p-3  rounded-md hover:bg-gray-300"
                                onclick="customToggleModal('add-product-modal', 1)">
                                <div class="flex justify-center w-100">
                                    <div style="width: 1rem;" class="mr-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 w-100" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p>
                                        Agregar Producto
                                    </p>
                                </div>
                            </button>
                        </div>
                        <br>
                        @livewire('cotizador.add-new-product-to-quote')
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
@if ($puedeEditar)
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div class="col-span-2">
            <button class="bg-gray-200 p-3  rounded-md hover:bg-gray-300" wire:click='editar'>
                Cancelar
            </button>
        </div>
        <div class="col-span-2">
            <div class="d-flex justify-content-center">
                <div wire:loading wire:target="guardar">
                    <div class="spinner-border text-success" role="status">
                        <span class="sr-only">loading...</span>
                    </div>
                </div>
            </div>
            <button class="bg-green-200 p-3  rounded-md hover:bg-green-300" wire:click="guardar">
                Guardar Cambios en los Productos
            </button>
        </div>
    </div>

    <br>
@endif

{{-- Incluir modales --}}
@include('cotizador.ver_cotizacion.products-information.sections.modal-add-new-product')
@include('cotizador.ver_cotizacion.products-information.sections.modal-edit-product')
@include('cotizador.ver_cotizacion.products-information.sections.modal-show-products')

<script>
    window.addEventListener('showProduct', event => {
        customToggleModal('ver-product-modal', 1)
    })
    window.addEventListener('showModalEditar', event => {
        customToggleModal('editar-product-quote-modal', 1)
    })
    window.addEventListener('closeModal', event => {
        customToggleModal('editar-product-quote-modal', 0)
        customToggleModal('add-product-modal', 0)
    })
    window.addEventListener('Editarproducto', event => {
        Swal.fire('Actualizado correctamente')
    })
    window.addEventListener('Noseedito', event => {
        Swal.fire('No hay cambio')
    })
    window.addEventListener('Editardescuento', event => {
        Swal.fire('Descuento modificado')
    })
</script>
