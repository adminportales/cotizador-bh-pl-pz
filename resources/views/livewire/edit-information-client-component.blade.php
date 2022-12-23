<div>
    <h3>Detalles del Cliente</h3>
    <br>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Nombre de contacto</label>
                <input type="text" class="form-control" placeholder="Nombre" wire:model="nombre">
            </div>
            @if ($errors->has('nombre'))
                <span class="text-danger">{{ $errors->first('nombre') }}</span>
            @endif
        </div>
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
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Como mostrar el IVA</label>
                <select name="tipo" class="form-control" wire:model="ivaByItem">
                    <option value="0">Mostrar el IVA en el monto total</option>
                    <option value="1">Mostrar el IVA por partida</option>
                </select>
            </div>
            @if ($errors->has('ivaByItem'))
                <span class="text-danger">{{ $errors->first('departamento') }}</span>
            @endif
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="">Tax Fee (Opcional)</label>
                <input type="number" class="form-control" placeholder="Tax Fee (Valor Maximo 99)" wire:model="taxFee" max="99">
            </div>
            @if ($errors->has('ivaByItem'))
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
