<div>
    @php
        $subtotalAdded = 0;
        $subtotalSubstract = 0;
        $sumPrecioTotal = 0;
        $quoteScales = false;
    @endphp
    @if ($quote->latestQuotesUpdate)
        @if (count($quote->latestQuotesUpdate->quoteProducts) > 0)
            <div class="w-100">
                <table class="table table-responsive-sm">
                    <thead class="w-100">
                        <tr class="w-100">
                            <th>Imagen</th>
                            <th>Producto</th>
                            <th>Subtotal</th>
                            <th>Piezas</th>
                            <th>Total</th>
                            <th>...</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quote->latestQuotesUpdate->quoteProducts as $product)
                            @php
                                $producto = (object) json_decode($product->product);
                                $tecnica = (object) json_decode($product->technique);
                                $visible = true;
                                $auxProduct = $product;
                                $sumPrecioTotal += $product->precio_total;
                                if ($product->quote_by_scales) {
                                    $quoteScales = true;
                                }
                            @endphp
                            <tr class="{{ !$visible ? 'd-none' : '' }}">
                                <td class="text-center">
                                    <img src="{{ $producto->image == '' ? asset('img/default.jpg') : $producto->image }}"
                                        style="max-height: 80px;height:auto;max-width: 70px;width:auto;" alt=""
                                        srcset="">
                                </td>
                                <td class="">
                                    <p>{{ Str::limit($producto->name, 25, '...') }}</p>
                                </td>
                                @if (!$product->quote_by_scales)
                                    <td class="">
                                        <p class="text-center">
                                            ${{ number_format($product->precio_unitario, 2, '.', ',') }}</p>
                                    </td>
                                    <td class="">
                                        <p class="text-center"> {{ $product->cantidad }} piezas</p>
                                    </td>
                                    <td>
                                        <p class="text-center">
                                            ${{ number_format($product->precio_total, 2, '.', ',') }}
                                        </p>
                                    </td>
                                @else
                                    <td colspan="3" class="text-right">
                                        <table class="table table-sm table-bordered m-0">
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
                                                @foreach (json_decode($product->scales_info) as $item)
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>No hay Productos Cotizados</p>
        @endif
    @else
        <p>No hay Productos Cotizados</p>
    @endif
</div>
