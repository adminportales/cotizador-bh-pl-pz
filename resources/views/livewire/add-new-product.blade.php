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
                <label for="nombre">Precio</label>
                <input type="number" class="form-control" wire:model="precio" placeholder="Precio del producto"
                    value="{{ old('precio') }}">
                @if ($errors->has('precio'))
                    <span class="text-danger">{{ $errors->first('precio') }}</span>
                @endif
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
                <input type="text" class="form-control" wire:model="proveedor" placeholder="Proverdor del Producto"
                    value="{{ old('proveedor') }}">
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
</div>
