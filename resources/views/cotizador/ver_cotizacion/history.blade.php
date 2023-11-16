<div class="w-full mt-2 ">
    <h5 class="card-title">Historial de Modificaciones</h5>
    <div class="accordion w-100" id="accordionExample">
        <div class="card w-100">
            @if (count($quote->quotesUpdate) > 0)
                @foreach ($quote->quotesUpdate()->orderBy('created_at', 'DESC')->get() as $item)
                    <div class="card-header w-100" id="modificacion{{ $item->id }}">
                        <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left collapsed" type="button"
                                data-toggle="collapse" data-target="#collapseMod{{ $item->id }}"
                                aria-expanded="false" aria-controls="collapseMod{{ $item->id }}">
                                {{ $item->created_at }}
                            </button>
                        </h2>
                    </div>
                    <div id="collapseMod{{ $item->id }}" class="collapse"
                        aria-labelledby="modificacion{{ $item->id }} " data-parent="#accordionExample">
                        <div class="card-body">
                            <div>
                                <p class="m-0"><strong>Informacion de Descuento Actual</strong>
                            </div>
                            </p>

                            <p class="m-0"><strong>Cantidad:
                                </strong>{{ $item->quoteDiscount->value }}</p>

                            <p class="m-0"> <strong>Tipo de descuento:
                                </strong>{{ $item->quoteDiscount->type }}</p>
                            <br>
                            <div>
                                <p class="m-0"><strong>Cliente</strong>
                            </div>
                            </p>

                            <div>
                                <p class="m-0"><strong>Nombre: </strong>
                                    {{ $item->quotesInformation->name }}</p>
                                <p class="m-0"><strong>Compañia: </strong>
                                    {{ $item->quotesInformation->company }}</p>
                                <p class="m-0"><strong>Correo: </strong>
                                    {{ $item->quotesInformation->email }}</p>
                                <p class="m-0"><strong>Telefono: </strong>
                                    {{ $item->quotesInformation->landline }}</p>
                                <p class="m-0"><strong>Celular: </strong>
                                    {{ $item->quotesInformation->cell_phone }}</p>
                                <p class="m-0"> <strong>Oportunidad: </strong>
                                    {{ $item->quotesInformation->oportunity }}</p>
                                <p class="m-0"><strong>Probabilidad de Venta: </strong>
                                    {{ $item->quotesInformation->rank }}</p>
                                <p class="m-0"><strong>Departamento: </strong>
                                    {{ $item->quotesInformation->department }}</p>
                                <p class="m-0"><strong> Informacion adicional: </strong>
                                    {{ $item->quotesInformation->information }}
                            </div>
                            </p>
                            <br>
                            <div>
                                <p class="m-0"><strong>Productos Cotizados</strong></p>
                                @foreach ($item->quoteProducts as $product)
                                    <div>
                                        <p class="m-0"><strong>Num: </strong>{{ $product->id }}
                                        </p>
                                        <br>
                                        @php
                                            $producto = (object) json_decode($product->product);
                                            $tecnica = (object) json_decode($product->technique);
                                        @endphp
                                        <p class="m-0"><strong>Producto:
                                            </strong>{{ $producto->name }}</p>
                                        <p class="m-0"><strong>Tecnica:
                                            </strong>{{ $tecnica->size }} {{ $tecnica->tecnica }}</p>

                                        <p class="m-0"><strong>Precios de tecnica:
                                            </strong>{{ $product->prices_techniques }}</p>

                                        <p class="m-0"><strong>Color/logos:
                                            </strong>{{ $product->color_logos }}</p>

                                        <p class="m-0"><strong>Costo indirecto:
                                            </strong>{{ $product->costo_indirecto }}</p>

                                        <p class="m-0"><strong>Utilidad:
                                            </strong>{{ $product->utilidad }}</p>

                                        <p class="m-0"><strong>Dias de entrega:
                                            </strong>{{ $product->dias_entrega }}</p>

                                        <p class="m-0"><strong>Cantidad:
                                            </strong>{{ $product->cantidad }}</p>

                                        <p class="m-0"><strong>Precio unitario:
                                            </strong>{{ $product->precio_unitario }}</p>

                                        <p class="m-0"><strong>Precio total:
                                            </strong>{{ $product->precio_total }}</p>
                                    </div>
                                @endforeach
                            </div>
                            <br>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="card-header" id="sinModificacionesCollapse">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse"
                            data-target="#collapsesinModificacionesCollapse" aria-expanded="false"
                            aria-controls="collapsesinModificacionesCollapse">
                            Sin Modificaciones
                        </button>
                    </h2>
                </div>
                <div id="collapsesinModificacionesCollapse" class="collapse" aria-labelledby="sinModificacionesCollapse"
                    data-parent="#accordionExample">
                    <div class="card-body">
                        <p>No hay modificaciones</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
