<div>
    <div class="card-body">
        <div class="d-flex justify-content-between">
            <h5 class="card-title">Productos Cotizados</h5>
            <div style="width: 20px; cursor: pointer;" wire:click="editar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                    <path fill-rule="evenodd"
                        d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                        clip-rule="evenodd" />
                </svg>
            </div>
        </div>
        <br>
        @if ($quote->latestQuotesInformation)
            @if (count($quote->latestQuotesInformation->quotesProducts) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Subtotal</th>
                            <th>Piezas</th>
                            <th>Total</th>
                            @if ($puedeEditar)
                                <th class="text-center">Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($quote->latestQuotesInformation->quotesProducts as $product)
                            @php
                                $producto = (object) json_decode($product->product);
                                $tecnica = (object) json_decode($product->technique);
                            @endphp
                            <tr>
                                <td class="pr-5">
                                    <p>{{ $producto->name }}</p>
                                </td>
                                <td class="pr-5">
                                    <p>$ {{ $product->precio_unitario }}</p>
                                </td>
                                <td class="pr-5">
                                    <p> {{ $product->cantidad }} piezas</p>
                                </td>
                                <td>
                                    <p>$ {{ $product->precio_total }}</p>
                                </td>
                                @if ($puedeEditar)
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-sm"><svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg></button>
                                        <button class="btn btn-danger btn-sm"><svg xmlns="http://www.w3.org/2000/svg"
                                                class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg></button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                    @if ($puedeEditar)
                        <tr>
                            <td colspan="5" class="text-center">
                                <button class="btn btn-light btn-block shadow-none" data-toggle="modal"
                                    data-target="#exampleModal">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endif
                </table>
                @php
                    $subtotal = $quote->latestQuotesInformation->quotesProducts->sum('precio_total');
                @endphp
                <div class="d-flex justify-content-between">
                    <div class="w-50">
                        <p><b>Subtotal: </b>$ {{ $subtotal }}</p>
                        <p><b>Descuento: </b>$ {{ (int) $quote->discount > 0 ? $quote->discount : 0 }}
                        </p>
                        <p><b>Total: </b>$ {{ $subtotal - $quote->discount }}</p>
                    </div>
                    <div class="w-50 text-right">
                        <button class="btn btn-primary mb-1">Ver PDF</button>
                        <br>
                        <button class="btn btn-primary mb-1">Enviar al Cliente</button>
                    </div>
                </div>
            @else
                <p>No hay Productos Cotizados</p>
            @endif
        @else
            <p>No hay Productos Cotizados</p>
        @endif
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Añadir producto a la cotizacion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @livewire('catalogo-min-component')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
