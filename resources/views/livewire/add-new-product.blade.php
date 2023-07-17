<div>
    <h5>Agregar Nuevo Producto</h5>
    {{-- <p><span class="text-danger">Importante:</span> Este producto solo sera registrado para esta cotizacion y no
        sera posible volver a cotizarlo.</p> --}}
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" wire:model="nombre" placeholder="Nombre del producto"
                    value="{{ old('nombre') }}">
                @if ($errors->has('nombre'))
                    <span class="text-danger">{{ $errors->first('nombre') }}</span>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="nombre">Descripcion</label>
                <input type="text" class="form-control" wire:model="descripcion"
                    placeholder="Descripcion del producto" value="{{ old('descripcion') }}">
                @if ($errors->has('descripcion'))
                    <span class="text-danger">{{ $errors->first('descripcion') }}</span>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <div class="d-flex justify-content-between flex-sm-row flex-column">
                    <label class="">Precio</label>
                    <div class="form-check mt-2 mb-1" wire:click='changeTypePrice'>
                        <input class="form-check-input" type="checkbox" {{ $priceScales ? 'checked' : '' }}>
                        <label class="form-check-label" style="font-weight: bold;" for="defaultCheck1">
                            Agregar escalas de precios
                        </label>
                    </div>
                </div>
                @if (!$priceScales)
                    <input type="number" class="form-control" wire:model="precio" placeholder="Precio del producto"
                        value="{{ old('precio') }}">
                    @if ($errors->has('precio'))
                        <span class="text-danger">{{ $errors->first('precio') }}</span>
                    @endif
                @else
                    <div class="text-center">
                        <button type="button" class="btn btn-light shadow-none " wire:click='openScale'>
                            Agregar una nueva escala
                        </button>
                    </div>
                @endif
            </div>

            <div wire:ignore.self class="modal fade" id="addScaleModal" tabindex="-1"
                aria-labelledby="addScaleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addScaleModalLabel">
                                {{ $editScale ? 'Editar la cantidad de Productos' : 'Agregar una nueva cantidad' }}
                            </h5>
                        </div>
                        <div class="modal-body row">
                            <div class="col-12">
                                <p class="m-0">Importante</p>
                                <p class="m-0">Colocar las escalas de precio en orden, de menor a mayor y sin repetir
                                    datos en las escalas</p>
                                <p class="m-0">Ejemplo:</p>
                                <table class="table table-bordered table-sm">
                                    <thead>
                                        <tr>
                                            <th>Escala Inicial</th>
                                            <th>Escala Final</th>
                                            <th>Escala Costo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>500</td>
                                            <td>$ 2.50</td>
                                        </tr>
                                        <tr>
                                            <td>501</td>
                                            <td>999</td>
                                            <td>$ 2.40</td>
                                        </tr>
                                        <tr>
                                            <td>1000</td>
                                            <td>9999</td>
                                            <td>$ 2.40</td>
                                        </tr>
                                        <tr>
                                            <td>10000</td>
                                            <td>-</td>
                                            <td>$ 2.40</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group m-0 mb-1 col-md-4">
                                <label for="cantidad" class="text-dark m-0"><strong>Escala Inicial</strong>
                                </label>
                                <input type="number" name="cantidad" wire:model="inicial" placeholder="Escala Inicial"
                                    class="form-control form-control-sm">
                            </div>
                            <div class="form-group m-0 mb-1 col-md-4">
                                <label for="cantidad" class="text-dark m-0"><strong>Escala Final</strong>
                                </label>
                                <input type="number" name="cantidad" wire:model="final" placeholder="Escala Final"
                                    class="form-control form-control-sm">
                            </div>
                            <div class="form-group m-0 mb-1 col-md-4">
                                <label for="margen" class="text-dark m-0"><strong>Costo</strong> </label>
                                <input type="number" name="margen" wire:model="costo" placeholder="Costo"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info btn-sm" wire:click='closeScale'>Cerrar</button>
                            @if ($editScale)
                                <button type="button" class="btn btn-warning btn-sm"
                                    wire:click='updateScale'>Editar</button>
                            @else
                                <button type="button" class="btn btn-primary btn-sm"
                                    wire:click='addScale'>Agregar</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="nombre">Stock</label>
                <input type="number" class="form-control" wire:model="stock"
                    placeholder="Cantidad Disponible o Cantidad que desea cotizar" value="{{ old('stock') }}">
                @if ($errors->has('stock'))
                    <span class="text-danger">{{ $errors->first('stock') }}</span>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="nombre">Color</label>
                <input type="text" class="form-control" wire:model="color" placeholder="Color del producto"
                    value="{{ old('color') }}">
                @if ($errors->has('color'))
                    <span class="text-danger">{{ $errors->first('color') }}</span>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="nombre">Proveedor</label>
                <input type="text" class="form-control" wire:model="proveedor"
                    placeholder="Proverdor del Producto" value="{{ old('proveedor') }}">
                @if ($errors->has('proveedor'))
                    <span class="text-danger">{{ $errors->first('proveedor') }}</span>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">

                <label for="nombre">Imagen</label>
                <div class="form-group" x-data="{ isUploading: false, progress: 5 }" x-on:livewire-upload-start="isUploading = true"
                    x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-error="isUploading = false"
                    x-on:livewire-upload-progress="progress = $event.detail.progress">

                    <input type="file" class="form-control" wire:model="imagen" accept="image/*">
                    <div x-show="isUploading" class="progress">
                        <div class="progress-bar" role="progressbar" x-bind:style="`width: ${progress}%`"
                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                @if ($imagen)
                    <div class="btn btn-danger btn-sm" wire:click="limpiarImagen()">Eliminar</div>
                @else
                    <p>No hay un logo de cliente cargado</p>
                @endif
                @if ($errors->has('imagen'))
                    <span class="text-danger">{{ $errors->first('imagen') }}</span>
                @endif
            </div>
            <p>Si tu imagen pesa mas de 500 KB, puedes comprimirla <a
                    href="https://www.iloveimg.com/es/comprimir-imagen" target="_blank">aqui</a></p>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                @if ($imagen)
                    <div class="text-center">
                        <p>Imagen del Producto</p>
                        <p class="text-warning">Revisa que tenga fondo blanco o que se un archivo PNG</p>
                        <img src="{{ $imagen->temporaryUrl() }}" alt=""
                            style="max-width: 100%; height: 160px;">
                    </div>
                @endif
            </div>
        </div>
        <div class="col-md-12">
            <button class="btn btn-primary" wire:click="guardar()">Utilizar Producto</button>
        </div>
    </div>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        window.addEventListener('hideModalScales', event => {
            $('#addScaleModal').modal('hide')
        })
        window.addEventListener('showModalScales', event => {
            $('#addScaleModal').modal('show')
        })
        window.addEventListener('showModalImage', event => {
            $('#modalImage').modal('show')
        })
        window.addEventListener('hideModalImage', event => {
            $('#modalImage').modal('hide')
        })
    </script>
</div>
