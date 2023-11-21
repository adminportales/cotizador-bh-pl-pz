<div class="text-center mt-3">
    <button class="w-full md:w-auto bg-green-200 p-3  rounded-md hover:bg-green-300" data-toggle="modal" data-target="#modalNewProduct">
        <div class="flex justify-center w-100">
            <div style="width: 1rem;" class="mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p> Agregar un Producto que no esta en el catalogo</p>
        </div>
    </button>

    {{-- <div class="modal fade" id="modalNewProduct" tabindex="-1" aria-labelledby="modalNewProductLabel" data-backdrop="static"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNewProductLabel">Añadir producto a la cotizacion que no esta en la
                        cotizacion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left" style="max-height: 80vh; overflow: auto;">
                    @if (!$thereProduct)
                        @if ($isNewProduct)
                            <div class="row text-left">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Nombre</label>
                                        <input type="text" class="form-control" wire:model="nombre"
                                            placeholder="Nombre del producto" value="{{ old('nombre') }}">
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
                                        <input type="number" class="form-control" wire:model="precio"
                                            placeholder="Precio del producto" value="{{ old('precio') }}">
                                        @if ($errors->has('precio'))
                                            <span class="text-danger">{{ $errors->first('precio') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Stock</label>
                                        <input type="number" class="form-control" wire:model="stock"
                                            placeholder="Cantidad Disponible o Cantidad que desea cotizar"
                                            value="{{ old('stock') }}">
                                        @if ($errors->has('stock'))
                                            <span class="text-danger">{{ $errors->first('stock') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre">Color</label>
                                        <input type="text" class="form-control" wire:model="color"
                                            placeholder="Color del producto" value="{{ old('color') }}">
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
                                        <div class="form-group" x-data="{ isUploading: false, progress: 5 }"
                                            x-on:livewire-upload-start="isUploading = true"
                                            x-on:livewire-upload-finish="isUploading = false"
                                            x-on:livewire-upload-error="isUploading = false"
                                            x-on:livewire-upload-progress="progress = $event.detail.progress">

                                            <input type="file" class="form-control" wire:model="imagen"
                                                accept="image/*">
                                            <div x-show="isUploading" class="progress">
                                                <div class="progress-bar" role="progressbar"
                                                    x-bind:style="`width: ${progress}%`" aria-valuenow="25"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        @if ($imagen)
                                            <div class="btn btn-danger btn-sm" wire:click="limpiarImagen()">Eliminar
                                            </div>
                                        @else
                                            <p>No hay un logo de producto cargado</p>
                                        @endif
                                        @if ($errors->has('imagen'))
                                            <span class="text-danger">{{ $errors->first('imagen') }}</span>
                                        @endif
                                    </div>
                                    <p>Si tu imagen pesa mas de 500 KB, puedes comprimirla <a
                                            href="https://www.iloveimg.com/es/comprimir-imagen"
                                            target="_blank">aqui</a>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        @if ($imagen)
                                            <div class="text-center">
                                                <p>Imagen del Producto</p>
                                                <p class="text-warning">Revisa que tenga fondo blanco o que se un
                                                    archivo
                                                    PNG</p>
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

                        @endif
                    @else
                        @php
                            $priceProduct = $producto->price;
                            if ($producto->producto_promocion) {
                                $priceProduct = round($priceProduct - $priceProduct * ($producto->descuento / 100), 2);
                            } else {
                                $priceProduct = round($priceProduct - $priceProduct * ($producto->provider->discount / 100), 2);
                            }
                        @endphp
                        <div class="d-flex">
                            <div class="img-container w-25">
                                <img id="imgBox"
                                    src="{{ $producto->firstImage ? $producto->firstImage->image_url : asset('img/default.jpg') }}"
                                    class="img-fluid" alt="imagen">
                            </div>
                            <div class="px-3">
                                <h4 class="card-title">{{ $producto->name }}</h4>
                                <p class="my-1"><strong>SKU Interno: </strong> {{ $producto->internal_sku }}</p>
                                <p class="my-1"><strong>SKU Proveedor: </strong> {{ $producto->sku }}</p>
                                <h5 class="text-primary">
                                    $ {{ round($priceProduct + $priceProduct * ($utilidad / 100), 2) }}</p>
                                </h5>
                                <h5 class="text-success">Disponibles:<strong> {{ $producto->stock }}</strong>
                                </h5>
                                <br>
                            </div>
                        </div>
                        @livewire('components.formulario-de-cotizacion', ['productNewAdd' => $producto])
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div> --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</div>
