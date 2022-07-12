<div class="row px-3 justify-content-center">
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
                                        <td class="text-center">1</td>
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
                                            <p class="m-0"><strong>Costo Indirecto:</strong>$
                                                {{ $quote->costo_indirecto }}</p>
                                            <p class="m-0"><strong>Margen de Utilidad:</strong>$
                                                {{ $quote->utilidad }}
                                            </p>
                                            <p class="m-0"><strong>Costo de Impresion:</strong>$
                                                {{ $quote->prices_techniques_id }}</p>
                                            <p class="m-0"><strong>Colores/Logos:</strong>
                                                {{ $quote->color_logos }}
                                            </p>
                                        </td>
                                        <td>$ {{ $quote->precio_unitario }}</td>
                                        <td> {{ $quote->cantidad }} piezas</td>
                                        <td>$ {{ $quote->precio_total }}</td>
                                        <td> {{ $quote->dias_entrega }} dias habiles</td>
                                        <td class="px-0 mx-0">
                                            <button class="btn btn-warning btn-sm mb-1 w-100">Editar</button>
                                            <button class="btn btn-danger btn-sm w-100 mt-1">Eliminar</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{-- <div class="col-md-4">
                    <div class="d-flex justify-content-start" style="font-size: 1rem;font-weight: bold;">
                        <p class="text-dark"> Agregar un descuento </p>
                    </div>
                    <div class="d-flex justify-content-start" style="font-size: 1rem;font-weight: bold;">
                        <input type="text" name="" id="" class="form-control mr-3"
                            wire:model="discountMount" placeholder="" value="{{ $discount }}">
                        <button class="btn btn-info btn-sm mb-1">Agregar</button>
                    </div>
                </div> --}}
            </div>

        </div>
    </div>
    <div class="col-md-12">
        <br>
        <div class="card">
            <div class="card-body">
                <h3>Total de la cotizacion</h3>

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
                    <a href="{{ route('finalizar') }}" class="btn btn-info btn-sm mb-1 w-100">Finalizar
                        Cotizacion</a>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="col-md-12">
        <hr>
        <h3>Detalles de la cotizacion</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Nombre</label>
                    <input type="text" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Nombre de la empresa</label>
                    <input type="text" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Telefono</label>
                    <input type="text" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Correo</label>
                    <input type="text" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Celular</label>
                    <input type="text" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Oportunidad</label>
                    <input type="text" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Rank</label>
                    <select name="" id="" class="form-control">
                        <option value="">Seleccione el rank</option>
                        <option value="">Medio</option>
                        <option value="">Alto</option>
                        <option value="">Muy Alto</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Departamento (opcional)</label>
                    <input type="text" class="form-control">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="">Deja un comentario</label>
                    <textarea name="" id="" cols="30" rows="10" class="form-control"></textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <button class="btn btn-success" type="submit">
                        Enviar Cotizacion
                    </button>
                    <button class="btn btn-ligth">
                        Volver al cotizador
                    </button>
                </div>
            </div>
        </div>
    </div> --}}
    <style>
        label {
            color: black;
        }
    </style>
</div>
