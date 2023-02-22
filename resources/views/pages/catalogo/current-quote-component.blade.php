<div class="container cq">
    <div class="content-all d-flex flex-column justify-content-between">
        @if (count($cotizacionActual) > 0)
            <div class="content-products">
                <div class="">
                    <div class="card d-none d-md-block p-2">
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
                                                        <img src="{{ $quote->images_selected ?: ($quote->product->firstImage ? $quote->product->firstImage->image_url : asset('img/default.jpg')) }}"
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
                                                    wire:click="edit({{ $quote->id }})">
                                                    Editar
                                                </button>
                                                <button class="btn btn-danger btn-sm w-100 mt-1"
                                                    onclick='eliminar({{ $quote->id }})'>Eliminar</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="d-md-none">
                        @foreach ($cotizacionActual as $quote)
                            <div class="shadow-sm pl-2 pr-2 mb-2">
                                <div class="d-flex">
                                    <div class="w-25">
                                        <img src="{{ $quote->images_selected ?: ($quote->product->firstImage ? $quote->product->firstImage->image_url : asset('img/default.jpg')) }}"
                                            alt="" width="80">
                                    </div>
                                    <div class="w-75">
                                        <div class="d-flex justify-content-between">
                                            <p class="m-0"><strong>{{ $quote->product->name }}</strong></p>
                                            <p class="m-0"><strong>${{ $quote->precio_total }}</strong></p>
                                        </div>
                                        <div style="font-size: 14px;">
                                            <p class="m-0"> {{ $quote->dias_entrega }} dias habiles</p>
                                            <p class="m-0"> {{ $quote->cantidad }} piezas</p>
                                        </div>
                                    </div>
                                </div>
                                <hr class="mt-1 mb-1">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <button class="btn btn-link btn-sm" wire:click='show({{ $quote->id }})'>Ver
                                            detalles</button>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-link btn-sm"
                                            wire:click="edit({{ $quote->id }})">
                                            Editar
                                        </button>
                                        <button class="btn btn-link btn-sm"
                                            onclick='eliminar({{ $quote->id }})'>Eliminar</button>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="content-discount">
                <div class="card discount">
                    <div class="card-body">
                        <h4>Total de la cotizacion</h4>
                        <button type="button"
                            class="btn {{ auth()->user()->currentQuote->discount ? 'btn-warning' : 'btn-info' }} btn-block btn-sm my-1"
                            data-toggle="modal" data-target="#discountModal">
                            {{ auth()->user()->currentQuote->discount ? 'Editar Descuento' : 'Agregar Descuento' }}
                        </button>

                        <div class="d-flex justify-content-between">
                            <p class="text-dark m-0"> Subtotal: </p>
                            <strong class="d-flex text-primary d-block"> $ {{ $totalQuote }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class="text-dark m-0"> Descuento: </p>
                            <strong class="d-flex text-primary d-block"> $
                                {{ $discount }}</strong>
                        </div>
                        <hr class="mt-1 mb-1">
                        <div class="d-flex justify-content-between" style="font-size: 1.3rem;font-weight: bold;">
                            <p class="text-dark m-0"> Total: </p>
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
    </div>
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
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Editar Cotizacion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($quoteEdit)
                        @livewire('formulario-de-cotizacion-current-min', ['currentQuote' => $quoteEdit], key($quoteEdit->id))
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
                        <p class="m-0"><strong>Margen de Utilidad:</strong>
                            {{ $quoteShow->utilidad }}%
                        </p>
                        <p class="m-0"><strong>Costo de
                                Impresion:</strong>
                            ${{ $quoteShow->new_price_technique
                                ? $quoteShow->new_price_technique
                                : ($quoteShow->priceTechnique->tipo_precio == 'D'
                                    ? round($quoteShow->priceTechnique->precio / $quoteShow->cantidad, 2)
                                    : $quoteShow->priceTechnique->precio) }}
                        </p>
                        <p class="m-0"><strong>Colores/Logos:</strong>
                            {{ $quoteShow->color_logos }}
                        </p>

                        <p class="m-0"><strong>Cantidad: </strong> {{ $quoteShow->cantidad }} piezas</p>
                        <p class="m-0"><strong>Precio Unitario: </strong>${{ $quoteShow->precio_unitario }}</p>
                        <p class="m-0"><strong>Precio Total:</strong>${{ $quoteShow->precio_total }}</p>
                        <p class="m-0"><strong>Tiempo de entrega: </strong> {{ $quoteShow->dias_entrega }} dias habiles
                        </p>
                    @endif
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
        document.addEventListener('DOMContentLoaded', () => {
            var height = document.querySelector('.navbar.d-block.d-md-none.mb-2').offsetHeight;
            document.querySelector('#app').style.height = `calc(100% - ${height}px)`
            console.log(height);
        })

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
