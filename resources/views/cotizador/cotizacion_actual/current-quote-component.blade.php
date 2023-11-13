<div>
    <div class="">
        <p class="mb-2 text-xl">Mis Cotizaciones</p>
        @include('cotizador.cotizacion_actual.sections.all-current-quotes')

    </div>
    @if (auth()->user()->currentQuoteActive)
        <div class="px-2 mt-3">
            <div class="grid grid-cols-4 reverse gap-5">
                @if (count($listaProductos) > 0)
                    @php
                        $quoteByScales = false;
                        foreach ($listaProductos as $quote) {
                            if ($quote->quote_by_scales) {
                                $quoteByScales = true;
                            }
                        }
                    @endphp
                    <div class="col-span-4 lg:col-span-3">
                        <div>
                            <div class=" relative overflow-x-auto shadow-md sm:rounded-lg">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">
                                                Imagen
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                Nombre
                                            </th>
                                            <th scope="col" class="sm:table-cell hidden px-6 py-3">
                                                Informacion
                                            </th>
                                            <th scope="col" class="sm:table-cell hidden px-6 py-3">
                                                Cantidad
                                            </th>
                                            <th scope="col" class="sm:table-cell hidden px-6 py-3">
                                                Unidad
                                            </th>
                                            <th scope="col" class="sm:table-cell hidden px-6 py-3">
                                                Total
                                            </th>
                                            <th scope="col" class="px-6 py-3">
                                                ...
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($listaProductos as $quote)
                                            @php
                                                $quoteByScales = false;
                                            @endphp
                                            <tr class="bg-white border-b hover:bg-gray-50">
                                                <th scope="row"
                                                    class="px-6 py-4 font-medium text-gray-900 text-center">
                                                    <img src="{{ $quote->images_selected ?: ($quote->product->firstImage ? $quote->product->firstImage->image_url : asset('img/default.jpg')) }}"
                                                        alt="" width="80">
                                                </th>
                                                <td class="px-6 py-4">
                                                    <div class="flex flex-col">
                                                        <p>{{ $quote->product->name }}</p>
                                                        <p> {{ $quote->product->internal_sku }}</p>
                                                    </div>
                                                </td>
                                                <td class="sm:table-cell hidden px-6 py-4">
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
                                                    <td colspan="3" class="sm:table-cell hidden px-6 py-4">
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
                                                                    @foreach (json_decode($quote->scales_info) as $item)
                                                                        <tr
                                                                            class="text-center bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                                            <td class="px-2 py-1">
                                                                                {{ $item->quantity }} pz
                                                                            </td>
                                                                            <td class="px-2 py-1">$
                                                                                {{ number_format($item->tecniquePrice, 2, '.', ',') }}
                                                                            </td>
                                                                            <td class="px-2 py-1">
                                                                                {{ $item->utility }} %</td>
                                                                            <td class="px-2 py-1">$
                                                                                {{ number_format($item->unit_price, 2, '.', ',') }}
                                                                            </td>
                                                                            <td class="px-2 py-1">$
                                                                                {{ number_format($item->total_price, 2, '.', ',') }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </td>
                                                @else
                                                    <td class="sm:table-cell hidden px-6 py-4"> {{ $quote->cantidad }}
                                                        pz</td>
                                                    <td class="sm:table-cell hidden px-6 py-4">$
                                                        {{ $quote->precio_unitario }}</td>
                                                    <td class="sm:table-cell hidden px-6 py-4">$
                                                        {{ $quote->precio_total }}</td>
                                                @endif
                                                <td class="px-6 py-4 space-x-3">
                                                    <button wire:click="show({{ $quote->id }})" type="button"
                                                        class="inline-block sm:hidden">
                                                        <div style="width: 1rem">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5"
                                                                stroke="currentColor" class="w-6 h-6">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                        </div>
                                                    </button>
                                                    <button type="button" data-modal-target="editarCotizacion"
                                                        data-modal-toggle="editarCotizacion"
                                                        wire:click="edit({{ $quote->id }})">
                                                        <div style="width: 1rem">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                                                fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                                stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                            </svg>
                                                        </div>
                                                    </button>

                                                    <button type="button" onclick='eliminar({{ $quote->id }})'>
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
                            </div>
                        </div>
                    </div>
                    @include('cotizador.cotizacion_actual.sections.discount')
                @else
                    <p class="col-span-4 text-center">No tienes productos en tu cotizacion</p>
                @endif
            </div>

            {{-- Modal Para Mostrar el product en responsive --}}
            @include('cotizador.cotizacion_actual.sections.show-product-modal')
            @include('cotizador.cotizacion_actual.sections.edit-product-modal')

            <!-- Modal -->
            {{--
                <div class="modal fade" id="discountModal" tabindex="-1" aria-labelledby="discountModalLabel"
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
            --}}

        </div>
    @else
        <div class="d-flex w-100 justify-content-center">
            <p class="text-center m-0 my-5"><strong>No tienes productos en tu cotizacion actual </strong></p>
        </div>
    @endif
    <script>
        // Estatus: 0 = cerrado, 1 = abierto
        function customToggleModal(id, estatus) {
            const modalGeneral = new Modal(document.getElementById(id), {
                backdrop: 'static'
            });
            if (estatus == 1) {
                modalGeneral.show();
            } else if (estatus == 0) {
                modalGeneral.hide();
                if (document.querySelector("body > div[modal-backdrop]")) {
                    document.querySelector("body > div[modal-backdrop]")?.remove()
                }
            }
        }

        function eliminar(id) {
            Swal.fire({
                title: 'Esta seguro?',
                text: "Esta accion ya no se puede revertir!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar mi producto!',
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
