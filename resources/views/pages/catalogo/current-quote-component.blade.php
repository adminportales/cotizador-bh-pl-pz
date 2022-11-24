<div class="row px-3 justify-content-center">
    @if (count($cotizacionActual) > 0)
        <div class="col-md-12">
            <div class="card">
                <div class="card-body row py-3">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>SKU</th>
                                        <th>Nombre</th>

                                        <th>Informacion Adicional</th>
                                        <th>Unidad</th>
                                        <th>Cantidad</th>
                                        <th>Total</th>
                                        <th>Dias de Entrega</th>
                                        <th class="px-0 mx-0"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cotizacionActual as $quote)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td> {{ $quote->product->internal_sku }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <img src="{{ $quote->product->firstImage ? $quote->product->firstImage->image_url : asset('img/default.jpg') }}"
                                                            alt="" width="80">
                                                    </div>
                                                    <div>
                                                        <p class="m-0 px-2">{{ $quote->product->name }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="m-0"><strong>Costo
                                                        Indirecto:</strong>${{ $quote->costo_indirecto }}</p>
                                                <p class="m-0"><strong>Margen de Utilidad:</strong>
                                                    {{ $quote->utilidad }}%
                                                </p>
                                                <p class="m-0"><strong>Costo de
                                                        Impresion:</strong>
                                                    ${{ $quote->new_price_technique
                                                        ? $quote->new_price_technique
                                                        : ($quote->priceTechnique->tipo_precio == 'D'
                                                            ? round($quote->priceTechnique->precio / $quote->cantidad, 2)
                                                            : $quote->priceTechnique->precio) }}
                                                </p>
                                                <p class="m-0"><strong>Colores/Logos:</strong>
                                                    {{ $quote->color_logos }}
                                                </p>
                                            </td>
                                            <td>${{ $quote->precio_unitario }}</td>
                                            <td> {{ $quote->cantidad }} piezas</td>
                                            <td>${{ $quote->precio_total }}</td>
                                            <td> {{ $quote->dias_entrega }} dias habiles</td>
                                            <td class="px-0 mx-0">
                                                <button type="button" class="btn btn-warning btn-sm mb-1 w-100"
                                                    data-toggle="modal"
                                                    data-target="#editProductModal{{ $quote->id }}">
                                                    Editar
                                                </button>
                                                <div class="modal fade" id="editProductModal{{ $quote->id }}"
                                                    tabindex="-1"
                                                    aria-labelledby="editProductModal{{ $quote->id }}Label"
                                                    aria-hidden="true" wire:ignore.self>
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title"
                                                                    id="editProductModal{{ $quote->id }}Label">
                                                                    Editar
                                                                    Cotizacion</h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                @livewire('formulario-de-cotizacion-current-min', ['currentQuote' => $quote], key($quote->id))
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn btn-danger btn-sm w-100 mt-1"
                                                    onclick='eliminar({{ $quote->id }})'>Eliminar</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-12">
            <br>
            <div class="card w-50">
                <div class="card-body">
                    <h3>Total de la cotizacion</h3>
                    <button type="button"
                        class="btn {{ auth()->user()->currentQuote->discount ? 'btn-warning' : 'btn-info' }} btn-block btn-sm my-1"
                        data-toggle="modal" data-target="#discountModal">
                        {{ auth()->user()->currentQuote->discount ? 'Editar Descuento' : 'Agregar Descuento' }}
                    </button>

                    <div class="d-flex justify-content-between">
                        <p class="text-dark"> Subtotal: </p>
                        <strong class="d-flex text-primary d-block"> $ {{ $totalQuote }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class="text-dark"> Descuento: </p>
                        <strong class="d-flex text-primary d-block"> $
                            {{ $discount }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between" style="font-size: 1.3rem;font-weight: bold;">
                        <p class="text-dark"> Total: </p>
                        <strong class="d-flex text-primary d-block"> $ {{ $totalQuote - $discount }}</strong>
                    </div>
                    <div class="d-flex justify-content-between" style="font-size: 1.3rem;font-weight: bold;">
                        <a href="{{ route('finalizar') }}" class="btn btn-primary btn-sm mb-1 w-100">Finalizar
                            Cotizacion</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <p class="text-center">No tienes productos en tu cotizacion</p>
    @endif
    <!-- Modal -->
    <div class="modal fade" id="discountModal" tabindex="-1" aria-labelledby="discountModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="discountModalLabel">Agregar Descuento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Tipo de Descuento</label>
                        {{ auth()->user()->currentQuote->type }}
                        <select class="form-control" wire:model.lazy="type">
                            <option value="">Seleccione...</option>
                            <option value="Fijo">Valor Fijo</option>
                            <option value="Porcentaje">Porcentaje</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Cantidad</label>
                        <input type="number" class="form-control" placeholder="ej: 50, 2000" wire:model.lazy="value">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Salir</button>
                    @if (auth()->user()->currentQuote->discount)
                        <button type="button" class="btn btn-warning" wire:click="addDiscount">Editar</button>
                        <button type="button" class="btn btn-danger"
                            wire:click="eliminarDescuento">Eliminar</button>
                    @else
                        <button type="button" class="btn btn-primary" wire:click="addDiscount">Guardar</button>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <style>
        label {
            color: black;
        }
    </style>
    <script>
        window.addEventListener('hide-modal-discount', event => {
            $('#discountModal').modal('hide')
        })
        window.addEventListener('closeModal', event => {
            $(`#editProductModal${event.detail.currentQuote}`).modal('hide');
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
