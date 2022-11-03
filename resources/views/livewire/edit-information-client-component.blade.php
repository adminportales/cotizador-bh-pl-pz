<div>
    <h3>Detalles del Cliente</h3>
    <br>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Opciones de Cliente</label>
                <select name="tipo" class="form-control" wire:model="tipoCliente" disabled>
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
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Buscar Cliente</label>
                    <select name="tipo" class="form-control" wire:model="clienteSeleccionado" disabled>
                        <option value="">Seleccionar Cliente</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}">
                                {{ $client->name . ' | ' . $client->contact }}</option>
                        @endforeach
                        @if (count($clients) < 1)
                            <option value="">No tienes clientes asignados, si es un error, reportalo
                                con el area de desarrollo</option>
                        @endif
                    </select>
                </div>
                @if ($errors->has('clienteSeleccionado'))
                    <span class="text-danger">{{ $errors->first('clienteSeleccionado') }}</span>
                @endif
            </div>
        @elseif($tipoCliente == 'crear')
            <div class="col-md-4">
                <div class="form-group">
                    <label for="">Nombre de la empresa</label>
                    <input type="text" class="form-control" placeholder="Nombre de la empresa" wire:model="empresa"
                        disabled>
                </div>
                @if ($errors->has('empresa'))
                    <span class="text-danger">{{ $errors->first('empresa') }}</span>
                @endif
            </div>
        @endif
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Nombre de contacto</label>
                <input type="text" class="form-control" placeholder="Nombre" wire:model="nombre">
            </div>
            @if ($errors->has('nombre'))
                <span class="text-danger">{{ $errors->first('nombre') }}</span>
            @endif
        </div>
        <div class="w-100"></div>
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
                <input type="tel" class="form-control" placeholder="Telefono del contacto" wire:model="telefono">
            </div>
            @if ($errors->has('telefono'))
                <span class="text-danger">{{ $errors->first('telefono') }}</span>
            @endif
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Celular</label>
                <input type="tel" class="form-control" placeholder="Celular del contacto" wire:model="celular">
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
                <label for="">Probabilidad de Venta</label>
                <select name="tipo" class="form-control" wire:model="rank">
                    <option value="">Seleccione la Probabilidad de Venta</option>
                    <option value="1">Medio</option>
                    <option value="2">Alto</option>
                    <option value="3">Muy Alto</option>
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
                <label for="">Informacion Adicional (Opcional)</label>
                <textarea name="" id="" cols="30" rows="2" class="form-control" wire:model="informacion"></textarea>
            </div>
            @if ($errors->has('informacion'))
                <span class="text-danger">{{ $errors->first('informacion') }}</span>
            @endif
        </div>
        <div class="col-md-12">
            <div class="d-flex justify-content-center">
                <div wire:loading wire:target="guardarCotizacion">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">loading...</span>
                    </div>
                </div>
                <button class="btn btn-primary" wire:click="guardarCotizacion">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>
