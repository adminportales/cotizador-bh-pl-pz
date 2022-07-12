<div>
    <div class="card-body">
        <h3>Detalles del Cliente</h3>
        <br>
        <form action="" method="post">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Opciones de Cliente</label>
                        <select name="tipo" class="form-control" wire:model="tipoCliente">
                            <option value="">Como vas a registrar el cliente</option>
                            <option value="buscar">Seleccionar Cliente</option>
                            <option value="crear">Crear Prospecto</option>
                        </select>
                    </div>
                    @if ($errors->has('tipoCliente'))
                        <span class="text-danger">{{ $errors->first('tipoCliente') }}</span>
                    @endif
                </div>
                @if ($tipoCliente == 'buscar')
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="">Buscar Cliente</label>
                            <select name="tipo" class="form-control" wire:model="clienteSeleccionado">
                                <option value="">Seleccionar Cliente</option>
                                <option value="cliente1">Cliente 1</option>
                                <option value="cliente2">Cliente 1</option>
                                <option value="cliente3">Cliente 1</option>
                                <option value="cliente4">Cliente 1</option>
                                <option value="cliente5">Cliente 1</option>
                            </select>
                        </div>
                        @if ($errors->has('clienteSeleccionado'))
                            <span class="text-danger">{{ $errors->first('clienteSeleccionado') }}</span>
                        @endif
                    </div>
                @elseif($tipoCliente == 'crear')
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Nombre</label>
                            <input type="text" class="form-control" placeholder="Nombre" wire:model="nombre">
                        </div>
                        @if ($errors->has('nombre'))
                            <span class="text-danger">{{ $errors->first('nombre') }}</span>
                        @endif
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="">Nombre de la empresa</label>
                            <input type="text" class="form-control" placeholder="Nombre de la empresa"
                                wire:model="empresa">
                        </div>
                        @if ($errors->has('empresa'))
                            <span class="text-danger">{{ $errors->first('empresa') }}</span>
                        @endif
                    </div>
                @else
                    <div class="col-md-8"></div>
                @endif
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" class="form-control" placeholder="Correo electronico del contacto"
                            wire:model="email">
                    </div>
                    @if ($errors->has('email'))
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Telefono</label>
                        <input type="tel" class="form-control" placeholder="Telefono del contacto"
                            wire:model="telefono">
                    </div>
                    @if ($errors->has('telefono'))
                        <span class="text-danger">{{ $errors->first('telefono') }}</span>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Celular</label>
                        <input type="tel" class="form-control" placeholder="Celular del contacto"
                            wire:model="celular">
                    </div>
                    @if ($errors->has('celular'))
                        <span class="text-danger">{{ $errors->first('celular') }}</span>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Oportunidad</label>
                        <input type="text" class="form-control" placeholder="Oportunidad" wire:model="oportunidad">
                    </div>
                    @if ($errors->has('oportunidad'))
                        <span class="text-danger">{{ $errors->first('oportunidad') }}</span>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Rank</label>
                        <select name="tipo" class="form-control" wire:model="rank">
                            <option value="">Seleccione el rank</option>
                            <option value="medio">Medio</option>
                            <option value="alto">Alto</option>
                            <option value="muy_alto">Muy Alto</option>
                        </select>
                    </div>
                    @if ($errors->has('rank'))
                        <span class="text-danger">{{ $errors->first('rank') }}</span>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Departamento (opcional)</label>
                        <input type="text" class="form-control" placeholder="Departamento" wire:model="departamento">
                    </div>
                    @if ($errors->has('departamento'))
                        <span class="text-danger">{{ $errors->first('departamento') }}</span>
                    @endif
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">Informacion Adicional</label>
                        <textarea name="" id="" cols="30" rows="2" class="form-control" wire:model="informacion"></textarea>
                    </div>
                    @if ($errors->has('informacion'))
                        <span class="text-danger">{{ $errors->first('informacion') }}</span>
                    @endif
                </div>
            </div>
        </form>
        <br>
        <h3>Tu cotizacion</h3>
        <div class="d-flex justify-content-between align-items-end">
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Subtotal</th>
                        <th>Piezas</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (auth()->user()->currentQuote->currentQuoteDetails as $quote)
                        <tr>
                            <td class="pr-5">
                                <p>{{ $quote->product->name }}</p>
                            </td>
                            <td class="pr-5">
                                <p>$ {{ $quote->precio_unitario }}</p>
                            </td>
                            <td class="pr-5">
                                <p> {{ $quote->cantidad }} piezas</p>
                            </td>
                            <td>
                                <p>$ {{ $quote->precio_total }}</p>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="3">Subtotal</th>
                        <th>147</th>
                    </tr>
                    <tr>
                        <th colspan="3">Descuento</th>
                        <th>147</th>
                    </tr>
                    <tr>
                        <th colspan="3">Total</th>
                        <th>147</th>
                    </tr>
                </tbody>
            </table>
            <div class="d-flex flex-column">
                <a class="btn btn-primary mb-3" href="{{ route('previsualizar') }}" target="_blank">Previsualizar
                    Cotizacion</a>
                <button class="btn btn-primary" wire:click="guardarCotizacion">Guardar Cotizacion</button>
            </div>
        </div>
    </div>
</div>
