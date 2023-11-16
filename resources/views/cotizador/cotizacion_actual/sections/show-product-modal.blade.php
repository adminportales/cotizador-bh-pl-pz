<!-- Show Product modal -->
<div wire:ignore.self id="show-product-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Detalles del Producto
                </h3>
                <button type="button" onclick="customToggleModal('show-product-modal', 0)"
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
                <div wire:loading.flex wire:target="show">
                    @include('components.shared.loading')
                </div>
                @if ($currentQuoteShow)
                    <div>
                        <p class="m-0 px-2">{{ $currentQuoteShow->product->name }}</p>
                    </div>
                    <div>
                        <img src="{{ $currentQuoteShow->images_selected ?: ($currentQuoteShow->product->firstImage ? $currentQuoteShow->product->firstImage->image_url : asset('img/default.jpg')) }}"
                            alt="" width="120">
                    </div>
                    <p> {{ $currentQuoteShow->product->internal_sku }}</p>

                    <p class="m-0"><strong>Costo
                            Indirecto:</strong>${{ $currentQuoteShow->costo_indirecto }}</p>
                    @if (!$currentQuoteShow->quote_by_scales)
                        <p class="m-0"><strong>Margen de Utilidad:</strong>
                            {{ $currentQuoteShow->utilidad }}%
                        </p>
                    @endif

                    <p class="m-0"><strong>Colores/Logos:</strong>
                        {{ $currentQuoteShow->color_logos }}
                    </p>
                    <p class="m-0"><strong>Tiempo de entrega: </strong> {{ $currentQuoteShow->dias_entrega }}
                        dias
                    </p>
                    @if (!$currentQuoteShow->quote_by_scales)
                        <p class="m-0"><strong>Costo de
                                Impresion por tinta:</strong>
                            ${{ $currentQuoteShow->new_price_technique
                                ? $currentQuoteShow->new_price_technique
                                : ($currentQuoteShow->priceTechnique->tipo_precio == 'D'
                                    ? round($currentQuoteShow->priceTechnique->precio / $currentQuoteShow->cantidad, 2)
                                    : $currentQuoteShow->priceTechnique->precio) }}
                        </p>
                    @endif
                    @if ($currentQuoteShow->quote_by_scales)
                        @php
                            $priceScales = json_decode($currentQuoteShow->scales_info);
                        @endphp
                        <br>
                        <div class="relative overflow-x-auto my-2">
                            <table
                                class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr class="text-center">
                                        <th scope="col" class="px-2 py-1">
                                            Cantidad
                                        </th>
                                        <th scope="col" class="px-2 py-1">
                                            Impresion Por tinta
                                        </th>
                                        <th scope="col" class="px-2 py-1">
                                            Utilidad
                                        </th>
                                        <th scope="col" class="px-2 py-1">
                                            Unitario
                                        </th>
                                        <th scope="col" class="px-2 py-1">
                                            Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($priceScales as $scale)
                                        <tr
                                            class="text-center bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <td class="px-2 py-1">
                                                {{ $scale->quantity }} pz
                                            </td>
                                            <td class="px-2 py-1">$
                                                {{ number_format($scale->tecniquePrice, 2, '.', ',') }}
                                            </td>
                                            <td class="px-2 py-1">
                                                {{ $scale->utility }} %</td>
                                            <td class="px-2 py-1">$
                                                {{ number_format($scale->unit_price, 2, '.', ',') }}
                                            </td>
                                            <td class="px-2 py-1">$
                                                {{ number_format($scale->total_price, 2, '.', ',') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="m-0"><strong>Cantidad: </strong> {{ $currentQuoteShow->cantidad }} piezas
                        </p>
                        <p class="m-0"><strong>Precio Unitario:
                            </strong>${{ $currentQuoteShow->precio_unitario }}
                        </p>
                        <p class="m-0"><strong>Precio Total:</strong>${{ $currentQuoteShow->precio_total }}
                        </p>
                    @endif
                @endif
            </div>
            <!-- Modal footer -->
            <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                <button type="button" onclick="customToggleModal('show-product-modal', 0)"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('show-modal-show', event => {
        customToggleModal('show-product-modal', 1)
    })
</script>
