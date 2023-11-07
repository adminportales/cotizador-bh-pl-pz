<div>
    <div class="">
        <p class="mb-2 text-xl">Mis Cotizaciones</p>
        @if (count($allQuotes) > 0)
            <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200">
                <ul class="flex">
                    @foreach ($allQuotes as $item)
                        <li class="hover:border-b-2 rounded-t-lg cursor-pointer text-center flex items-center {{ $item->active ? 'bg-gray-50 active border-gray-300 border-b-2 ' : '' }}"
                            wire:click='selectQuoteActive({{ $item->id }})'>
                            <a class="inline-block py-2 px-4 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300"
                                aria-current="page">{{ $item->name ?: 'Cotizacion Actual' }}</a>
                            @if ($item->active)
                                <div class="flex">
                                    <button>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    <button>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </li>
                    @endforeach
                    <li class="hover:border-b-2 rounded-t-lg cursor-pointer text-center flex items-center"
                        data-modal-target="modal-add-quote" data-modal-toggle="modal-add-quote">


                        <a class="inline-block p-4 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300"
                            aria-current="page">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        <div wire:ignore.self id="modal-add-quote" data-modal-backdrop="static" tabindex="-1" aria-hidden="true"
            class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative w-full max-w-2xl max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-4 border-b rounded-t">
                        <h3 class="text-xl font-semibold text-gray-900 ">
                            Agregar nueva cotizacion
                        </h3>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-2 gap-2 text-left">
                            <p class="col-span-2">Esta funcion es util cuando quieres crear una nueva cotizacion para un
                                cliente nuevo o
                                para el mismo cliente, pero no quieres perder el progreso de tu cotizacion actual</p>
                            <div class="col-span-2">
                                <label for="" class="text-sm font-semibold">Nombre de la cotizacion</label>
                                <input
                                    class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                                    type="text" placeholder="Ej. Bolsas Coca Cola" wire:model="nameQuote">
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b ">
                        <button type="button" class="btn btn-primary btn-sm" wire:click="addQuote">Agregar</button>
                        <button data-modal-hide="modal-add-quote" type="button"
                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 ">Decline</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (auth()->user()->currentQuoteActive)
        <div class="px-2 mt-3">
            <div class="content-all flex flex-col justify-between">
                @if (count($listaProductos) > 0)

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Imagen
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Nombre
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Informacion
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Cantidad
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Unidad
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Total
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        ...
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $quoteByScales = false;
                                @endphp
                                @foreach ($listaProductos as $quote)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 text-center">
                                            <img src="{{ $quote->images_selected ?: ($quote->product->firstImage ? $quote->product->firstImage->image_url : asset('img/default.jpg')) }}"
                                                alt="" width="80">
                                        </th>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <p>{{ $quote->product->name }}</p>
                                                <p> {{ $quote->product->internal_sku }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p>
                                                <strong>Indirecto:</strong>${{ number_format($quote->costo_indirecto, 2, '.', ',') }}
                                            </p>
                                            @if (!$quote->quote_by_scales)
                                                <p><strong>Utilidad:</strong>
                                                    {{ $quote->utilidad }}%
                                                </p>
                                            @endif
                                            @if (!$quote->quote_by_scales)
                                                <p><strong>Impresion Por Tinta:</strong>
                                                    ${{ number_format(
                                                        $quote->new_price_technique
                                                            ? $quote->new_price_technique
                                                            : ($quote->priceTechnique->tipo_precio == 'D'
                                                                ? round($quote->priceTechnique->precio / $quote->cantidad, 2)
                                                                : $quote->priceTechnique->precio),
                                                        2,
                                                        '.',
                                                        ',',
                                                    ) }}
                                                </p>
                                            @endif
                                            <p><strong>Colores/Logos:</strong>
                                                {{ $quote->color_logos }}
                                            </p>
                                            <p>
                                                <strong>Entrega:</strong> {{ $quote->dias_entrega }} dias
                                                {{ $quote->type_days == 1 ? 'habiles' : ($quote->type_days == 2 ? 'naturales' : '') }}
                                            </p>
                                        </td>
                                        @if ($quote->quote_by_scales)
                                            @php
                                                $quoteByScales = true;
                                            @endphp
                                            <td colspan="3">
                                                <table class="table table-sm table-bordered m-0">
                                                    <thead>
                                                        <tr>
                                                            <th>Cantidad</th>
                                                            <th>Impresion Por tinta</th>
                                                            <th>Utilidad</th>
                                                            <th>Unitario</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach (json_decode($quote->scales_info) as $item)
                                                            <tr>
                                                                <td>{{ $item->quantity }} pz</td>
                                                                <td>$
                                                                    {{ number_format($item->tecniquePrice, 2, '.', ',') }}
                                                                </td>
                                                                <td>
                                                                    {{ $item->utility }} %</td>
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
                                        @else
                                            <td> {{ $quote->cantidad }} pz</td>
                                            <td>$ {{ $quote->precio_unitario }}</td>
                                            <td>$ {{ $quote->precio_total }}</td>
                                        @endif
                                        <td class="px-6 py-4">
                                            <button type="button" class="btn btn-warning btn-sm"
                                                data-modal-target="editarCotizacion"
                                                data-modal-toggle="editarCotizacion"
                                                wire:click="edit({{ $quote->id }})">
                                                <div
                                                    style="width:
                                                1rem">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </div>
                                            </button>

                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick='eliminar({{ $quote->id }})'>
                                                <div style="width: 1rem">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                        stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </div>
                                            </button>
                                        </td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Modal -->


                    </div>

                    {{--  --}}

                    {{--  --}}


                    <div class="content-normal bg-white shadow-lg rounded-lg  w-4/4 lg:w-2/3  p-4 m-2 lg:flex-auto">
                        @if (!$quoteByScales)
                            <p class="text-black text-center text-lg">Total de la cotizacion</p>
                            <button
                                class="bg-gray-200 hover:bg-gray-200 text-black font-bold py-2 px-4 rounded w-full  {{ $cotizacion->discount }}"
                                data-modal-target="default-modal" data-modal-toggle="default-modal">
                                Agregar Descuento
                            </button>
                            <div wire:ignore.self id="default-modal" data-modal-backdrop="static" tabindex="-1"
                                aria-hidden="true"
                                class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                <div class="relative w-full max-w-2xl max-h-full">
                                    <!-- Modal content -->
                                    <div class="relative  bg-gray-50 rounded-lg shadow dark:bg-gray-700">
                                        <!-- Modal header -->
                                        <div
                                            class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                            <h3 class="text-xl font-semibold   text-black">
                                                Agrega tu descuento
                                            </h3>
                                            <button type="button"
                                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                                data-modal-hide="default-modal">
                                                <svg class="w-3 h-3" aria-hidden="true"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 14 14">
                                                    <path stroke="currentColor" stroke-linecap="round"
                                                        stroke-linejoin="round" stroke-width="2"
                                                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                                </svg>
                                                <span class="sr-only">Close modal</span>
                                            </button>
                                        </div>
                                        <!-- Modal body -->
                                        <div class="p-3">
                                            <label for="countries"
                                                class=" mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de
                                                Descuento</label>
                                            <select id="countries"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option selected>Tipo de Descuento</option>
                                                {{ $cotizacion->type }}
                                                <option value="Seleccione">Seleccione</option>
                                                <option value="valorfijo">Valor Fijo</option>
                                                <option value="porcentaje">Porcentaje</option>
                                            </select>
                                            <label for="">Cantidad</label>
                                            <input
                                                class="w-full py-1 text-center rounded-lg ring-2 ring-stone-950 ring-inset "
                                                type="number" name="cantidad">



                                            <button type="button"
                                                class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-4 py-2 rounded-md"
                                                data-dismiss="modal">Salir</button>
                                            @if ($cotizacion->discount)
                                                <button type="button"
                                                    class="bg-yellow-500 text-white hover:bg-yellow-600 px-4 py-2 rounded-md"
                                                    wire:click="addDiscount">Editar</button>
                                                <button type="button"
                                                    class="bg-red-500 text-white hover:bg-red-600 px-4 py-2 rounded-md"
                                                    wire:click="eliminarDescuento">Eliminar</button>
                                            @else
                                                <button type="button"
                                                    class="bg-blue-500 text-white px-4 py-2 rounded-md m-2"
                                                    wire:click="addDiscount">Guardar</button>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="content-between">
                                <div class="flex justify-between">
                                    <span class="text-black"> Subtotal:</span>
                                    <span class="text-black"> $ {{ $totalQuote }}</span>

                                </div>
                                <div class="flex justify-between">
                                    <span class="text-black"> Descuento: </span>
                                    <span class="text-black text-right"> $
                                        {{ $discount }}</span>

                                </div>
                                <hr>
                                <div class="flex justify-between">
                                    <span class="text-black">Total:</span>
                                    <span class="text-black text-right">$ {{ $totalQuote - $discount }}</span>
                                </div>
                            </div>
                        @endif
                        <div class="justify-items-center bg-gray-200 text-center p-2 font-bold">
                            <a href="{{ route('finalizar') }}" class="">Finalizar
                                Cotizacion</a>
                        </div>
                    </div>
                @else
                    <p class="text-center">No tienes productos en tu cotizacion</p>
                @endif
            </div>
            <!-- Modal -->
            {{--   <div class="modal fade" id="discountModal" tabindex="-1" aria-labelledby="discountModalLabel"
                aria-hidden="true" wire:ignore.self>
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="discountModalLabel">Agregar Descuento11111</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <label for="">Tipo de Descuento</label>
                            {{ $cotizacion->type }}
                            <select class="form-control" wire:model.lazy="type">
                                <option value="">Seleccione...</option>
                                <option value="Fijo">Valor Fijo</option>
                                <option value="Porcentaje">Porcentaje</option>
                            </select>


                        </div>

                    </div>
                </div>
            </div>
            <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel"
                data-backdrop="static" aria-hidden="true" wire:ignore.self>
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editProductModalLabel">Editar Cotizacion</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="max-height: 80vh; overflow: auto;">
                            @if ($quoteEdit)
                                @livewire('components.formulario-de-cotizacion', ['currentQuote' => $quoteEdit], key($quoteEdit->id))
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="showProductModal" tabindex="-1" aria-labelledby="showProductModalLabel"
                aria-hidden="true" wire:ignore.self>
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="showProductModalLabel">Detalles de la Cotizacion</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            @if ($quoteShow)
                                <div>
                                    <p class="m-0 px-2">{{ $quoteShow->product->name }}</p>
                                </div>
                                <div>
                                    <img src="{{ $quoteShow->images_selected ?: ($quoteShow->product->firstImage ? $quoteShow->product->firstImage->image_url : asset('img/default.jpg')) }}"
                                        alt="" width="120">
                                </div>
                                <p> {{ $quoteShow->product->internal_sku }}</p>

                                <p class="m-0"><strong>Costo
                                        Indirecto:</strong>${{ $quoteShow->costo_indirecto }}</p>
                                @if (!$quoteShow->quote_by_scales)
                                    <p class="m-0"><strong>Margen de Utilidad:</strong>
                                        {{ $quoteShow->utilidad }}%
                                    </p>
                                @endif

                                <p class="m-0"><strong>Colores/Logos:</strong>
                                    {{ $quoteShow->color_logos }}
                                </p>
                                <p class="m-0"><strong>Tiempo de entrega: </strong> {{ $quoteShow->dias_entrega }}
                                    dias
                                </p>
                                <p class="m-0"><strong>Costo de
                                        Impresion por tinta:</strong>
                                    ${{ $quoteShow->new_price_technique
                                        ? $quoteShow->new_price_technique
                                        : ($quoteShow->priceTechnique->tipo_precio == 'D'
                                            ? round($quoteShow->priceTechnique->precio / $quoteShow->cantidad, 2)
                                            : $quoteShow->priceTechnique->precio) }}
                                </p>
                                @if ($quoteShow->quote_by_scales)
                                    @php
                                        $priceScales = json_decode($quoteShow->scales_info);
                                    @endphp
                                    <br>
                                    <table class="table table-sm table-responsive-sm w-100">
                                        <thead>
                                            <tr>
                                                <th>Cantidad</th>
                                                <th>Utilidad</th>
                                                <th>Impresion</th>
                                                <th>Unitario</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($priceScales as $scale)
                                                <tr>
                                                    <td> {{ $scale->quantity }} pz</td>
                                                    <td> {{ $scale->utility }} %</td>
                                                    <td>$ {{ number_format($scale->tecniquePrice, 2, '.', ',') }} </td>
                                                    <td>$ {{ number_format($scale->unit_price, 2, '.', ',') }} </td>
                                                    <td>$ {{ number_format($scale->total_price, 2, '.', ',') }} </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="m-0"><strong>Cantidad: </strong> {{ $quoteShow->cantidad }} piezas
                                    </p>
                                    <p class="m-0"><strong>Precio Unitario:
                                        </strong>${{ $quoteShow->precio_unitario }}
                                    </p>
                                    <p class="m-0"><strong>Precio Total:</strong>${{ $quoteShow->precio_total }}
                                    </p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div> --}}


            <div wire:ignore.self id="editarCotizacion" data-modal-backdrop="static" tabindex="-1"
                aria-hidden="true"
                class="justify-center fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative w-full max-w-2xl max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <!-- Modal header -->
                        <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white" id="editarCotizacion">
                                Editar Cotizacion
                            </h3>
                            <button type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-hide="editarCotizacion">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-6">

                            @if ($quoteEdit)
                                @livewire('components.formulario-de-cotizacion', ['currentQuote' => $quoteEdit], key($quoteEdit->id))
                            @endif
                        </div>
                        <h5 class="modal-title" id="showProductModalLabel">Detalles de la Cotizacion</h5>

                        <div class="modal-body text-center">
                            @if ($quoteShow)
                                <div>
                                    <p class="m-0 px-2">{{ $quoteShow->product->name }}</p>
                                </div>
                                <div>
                                    <img src="{{ $quoteShow->images_selected ?: ($quoteShow->product->firstImage ? $quoteShow->product->firstImage->image_url : asset('img/default.jpg')) }}"
                                        alt="" width="120">
                                </div>
                                <p> {{ $quoteShow->product->internal_sku }}</p>

                                <p class="m-0"><strong>Costo
                                        Indirecto:</strong>${{ $quoteShow->costo_indirecto }}</p>
                                @if (!$quoteShow->quote_by_scales)
                                    <p class="m-0"><strong>Margen de Utilidad:</strong>
                                        {{ $quoteShow->utilidad }}%
                                    </p>
                                @endif

                                <p class="m-0"><strong>Colores/Logos:</strong>
                                    {{ $quoteShow->color_logos }}
                                </p>
                                <p class="m-0"><strong>Tiempo de entrega: </strong>
                                    {{ $quoteShow->dias_entrega }}
                                    dias
                                </p>
                                <p class="m-0"><strong>Costo de
                                        Impresion por tinta:</strong>
                                    ${{ $quoteShow->new_price_technique
                                        ? $quoteShow->new_price_technique
                                        : ($quoteShow->priceTechnique->tipo_precio == 'D'
                                            ? round($quoteShow->priceTechnique->precio / $quoteShow->cantidad, 2)
                                            : $quoteShow->priceTechnique->precio) }}
                                </p>
                                @if ($quoteShow->quote_by_scales)
                                    @php
                                        $priceScales = json_decode($quoteShow->scales_info);
                                    @endphp
                                    <br>
                                    <table class="table table-sm table-responsive-sm w-100">
                                        <thead>
                                            <tr>
                                                <th>Cantidad</th>
                                                <th>Utilidad</th>
                                                <th>Impresion</th>
                                                <th>Unitario</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($priceScales as $scale)
                                                <tr>
                                                    <td> {{ $scale->quantity }} pz</td>
                                                    <td> {{ $scale->utility }} %</td>
                                                    <td>$
                                                        {{ number_format($scale->tecniquePrice, 2, '.', ',') }}
                                                    </td>
                                                    <td>$
                                                        {{ number_format($scale->unit_price, 2, '.', ',') }}
                                                    </td>
                                                    <td>$
                                                        {{ number_format($scale->total_price, 2, '.', ',') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="m-0"><strong>Cantidad: </strong>
                                        {{ $quoteShow->cantidad }} piezas
                                    </p>
                                    <p class="m-0"><strong>Precio Unitario:
                                        </strong>${{ $quoteShow->precio_unitario }}
                                    </p>
                                    <p class="m-0"><strong>Precio
                                            Total:</strong>${{ $quoteShow->precio_total }}
                                    </p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">

                </div>
            </div>
        </div>
</div>
<style>
    body {
        height: 100vh
    }

    .container.cq,
    .container.cq .content-all {
        height: 100%;
    }

    .content-products {
        max-height: 100%;
        overflow: auto;
    }

    label {
        color: black;
    }

    .discount {
        width: 100%;
    }

    @media(min-width:768px) {
        .discount {
            width: 50%;
        }

        body {
            height: 100%
        }

        #app {
            height: auto;
        }

        .container.cq,
        .container.cq .content-all {
            height: auto;
        }

        .content-products {
            max-height: none;
            margin-bottom: 1rem;
        }

    }

    .container {
        max-height: 100%;
    }
</style>
<script>
    /* document.addEventListener('DOMContentLoaded', () => {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        var height = document.querySelector('.navbar.d-block.d-md-none.mb-2').offsetHeight;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        document.querySelector('#app').style.height = `calc(100% - ${height}px)`
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        console.log(height);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    }) */

    /* $(document).ready(function() {
        // $('#div2').height(height);
    }); */
    window.addEventListener('show-modal-show', event => {
        $('#showProductModal').modal('show')
    })
    window.addEventListener('show-modal-edit', event => {
        $('#editProductModal').modal('show')
    })
    window.addEventListener('hide-modal-discount', event => {
        $('#discountModal').modal('hide')
    })
    window.addEventListener('closeModal', event => {
        $(`#editProductModal`).modal('hide');
    })

    function eliminar(id) {
        Swal.fire({
            title: 'Esta seguro?',
            text: "Esta accion ya no se puede revertir!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, eliminar!',
            cancelButtonText: 'Cancelar!'
        }).then((result) => {
            if (result.isConfirmed) {
                @this.eliminar(id)
                Swal.fire(
                    'Eliminado!',
                    'El producto se ha eliminado.',

                    'success'
                )
            }
        })
    }
</script>
</div>
@else
<div class="d-flex w-100 justify-content-center">
    <p class="text-center m-0 my-5"><strong>No tienes productos en tu cotizacion actual </strong></p>
</div>
@endif
<script>
    window.addEventListener('hideModalAddQuote', event => {
        const modalAddQuote = new Modal(document.getElementById('modal-add-quote'), {
            backdrop: 'static'
        });
        modalAddQuote.hide();
        document.querySelector("body > div[modal-backdrop]")?.remove()
    })
</script>
<script>
    // Función para abrir el modal
    function openModal() {
        document.getElementById("myModal").classList.remove("hidden");
    }

    // Función para cerrar el modal
    function closeModal() {
        document.getElementById("myModal").classList.add("hidden");
    }

    // Asigna la función openModal al botón "openModalButton"
    document.getElementById("openModalButton").addEventListener("click", openModal);

    // Asigna la función closeModal al botón "closeModal"
    document.getElementById("closeModal").addEventListener("click", closeModal);
</script>

</div>
