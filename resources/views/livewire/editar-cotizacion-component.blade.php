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
        @php
            $subtotalAdded = 0;
            $subtotalSubstract = 0;
            $sumPrecioTotal = 0;
        @endphp
        @if ($quote->latestQuotesUpdate)
            @if (count($quote->latestQuotesUpdate->quoteProducts) > 0)
                <div class="w-100">
                    <table class="table table-responsive">
                        <thead class="w-100">
                            <tr class="w-100">
                                <th>Imagen</th>
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
                            @foreach ($quote->latestQuotesUpdate->quoteProducts as $product)
                                @php
                                    $producto = (object) json_decode($product->product);
                                    $tecnica = (object) json_decode($product->technique);
                                    $visible = true;
                                    $auxProduct = $product;

                                    foreach ($listUpdateCurrent as $productUpdate) {
                                        if ($product->id == $productUpdate['currentQuote_id']) {
                                            $product = (object) $productUpdate;
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
                                @endphp
                                <tr class="{{ !$visible ? 'd-none' : '' }}">
                                    <td class="">
                                        <img src="{{ $producto->image == '' ? asset('img/default.jpg') : $producto->image }}"
                                            width="80" alt="" srcset="">
                                    </td>
                                    <td class="">
                                        <p>{{ $producto->name }}</p>
                                    </td>
                                    <td class="">
                                        <p class="text-center">${{ $product->precio_unitario }}</p>
                                    </td>
                                    <td class="">
                                        <p class="text-center"> {{ $product->cantidad }} piezas</p>
                                    </td>
                                    <td>
                                        <p class="text-center">${{ $product->precio_total }}</p>
                                    </td>
                                    @if ($puedeEditar)
                                        <td class="text-center d-flex">
                                            <button class="btn btn-warning btn-sm"
                                                wire:click="editarProducto({{ $auxProduct }})"><svg
                                                    xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg></button>
                                            <button class="btn btn-danger btn-sm"
                                                wire:click="deleteProducto({{ $auxProduct }})"><svg
                                                    xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            @if ($puedeEditar)
                                @foreach ($listNewProducts as $newProduct)
                                    <tr>
                                        @php
                                            $producto = json_decode($newProduct['product']);
                                        @endphp
                                        <td class="">
                                            <img src="{{ $producto->image == '' ? asset('img/default.jpg') : $producto->image }}"
                                                width="80" alt="" srcset="">
                                        </td>
                                        <td class="">
                                            <p>{{ $producto->name }}</p>
                                        </td>
                                        <td class="">
                                            <p class="text-center">${{ $newProduct['precio_unitario'] }}</p>
                                        </td>
                                        <td class="">
                                            <p class="text-center"> {{ $newProduct['cantidad'] }} piezas</p>
                                        </td>
                                        <td>
                                            <p class="text-center">${{ $newProduct['precio_total'] }}</p>
                                            @php
                                                $subtotalAdded += $newProduct['precio_total'];
                                            @endphp
                                        </td>
                                        <td class="text-center d-flex">
                                            {{-- <button class="btn btn-warning btn-sm"
                                                wire:click="editarProducto({{ $newProduct['idNewQuote'] }}, {{ true }})"><svg
                                                    xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg></button> --}}
                                            <button class="btn btn-danger btn-sm"
                                                wire:click="deleteNewProducto({{ $newProduct['idNewQuote'] }})"><svg
                                                    xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg></button>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <button class="btn btn-light btn-block shadow-none" data-toggle="modal"
                                            data-target="#modalCotizador">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @if ($puedeEditar)
                    <div class="row px-2">
                        <div class="col-md-6">
                            <button class="btn btn-info btn-block shadow-none" wire:click='editar'>
                                Cancelar
                            </button>
                        </div>
                        <div class="col-md-6">

                            <div class="d-flex justify-content-center">
                                <div wire:loading wire:target="guardar">
                                    <div class="spinner-border text-success" role="status">
                                        <span class="sr-only">loading...</span>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-success btn-block shadow-none" wire:click="guardar">
                                Guardar Cambios en los Productos
                            </button>
                        </div>
                    </div>

                    <br>
                @endif
                {{-- INICIO DE LA PARTE DE LOS DESCUENTOS --}}
                {{-- @livewire('edit-quote-discount-component', ['quote' => $quote]) --}}
                @php
                    $subtotal = $sumPrecioTotal - $subtotalSubstract;
                    $discountValue = 0;
                    if ($type == 'Fijo') {
                        $discountValue = $value;
                    } else {
                        $discountValue = round(($subtotal / 100) * $value, 2);
                    }
                @endphp
                <div class="d-flex justify-content-between">
                    <h5 class="card-title">Informacion del descuento actual</h5>
                    <div style="width: 20px; cursor: pointer;" data-toggle="modal" data-target="#discountModalEdit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                            <path fill-rule="evenodd"
                                d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="d-flex flex-column">
                    <p><b>Subtotal: </b>$ {{ $subtotal + $subtotalAdded }}</p>
                    <p><b>Descuento: </b>$ {{ $discountValue }}
                    </p>
                    <p><b>Total: </b>$ {{ $subtotal + $subtotalAdded - $discountValue }}</p>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="discountModalEdit" tabindex="-1"
                    aria-labelledby="discountModalEditLabel" aria-hidden="true" wire:ignore.self>
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="discountModalEditLabel">Editar Descuento</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="">Tipo de Descuento</label>
                                    <select class="form-control" wire:model.lazy="type">
                                        <option value=""
                                            {{ $quote->latestQuotesUpdate->quoteDiscount->type == '' ? 'selected' : '' }}>
                                            Seleccione...
                                        </option>
                                        <option value="Fijo"
                                            {{ $quote->latestQuotesUpdate->quoteDiscount->type == 'Fijo' ? 'selected' : '' }}>
                                            Valor Fijo
                                        </option>
                                        <option value="Porcentaje"
                                            {{ $quote->latestQuotesUpdate->quoteDiscount->type == 'Porcentaje' ? 'selected' : '' }}>
                                            Porcentaje</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Cantidad</label>
                                    <input type="number" class="form-control" placeholder="ej: 50, 2000"
                                        wire:model.lazy="value">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">Cancelar</button>

                                <div class="d-flex justify-content-center">
                                    <div wire:loading wire:target="updateDiscount">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="sr-only">loading...</span>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary"
                                        wire:click="updateDiscount">Actualizar</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <script>
                    window.addEventListener('hideModalDiscount', event => {
                        $('#discountModalEdit').modal('hide')
                    })
                </script>
                {{-- FIN DE LA PARTE DE LOS DESCUENTOS --}}
            @else
                <p>No hay Productos Cotizados</p>
            @endif
        @else
            <p>No hay Productos Cotizados</p>
        @endif
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalCotizador" tabindex="-1" aria-labelledby="modalCotizadorLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCotizadorLabel">Añadir producto a la cotizacion</h5>
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

    <div class="modal fade" id="modalEditarProducto" tabindex="-1" aria-labelledby="modalEditarProductoLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarProductoLabel">Editar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @livewire('formulario-de-cotizacion-edit-min')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('showModalEditar', event => {
            $('#modalEditarProducto').modal('show')
        })
        window.addEventListener('closeModal', event => {
            $('#modalEditarProducto').modal('hide')
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
</div>
