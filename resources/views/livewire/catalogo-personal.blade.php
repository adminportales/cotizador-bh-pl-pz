<div class="container">
    <div class="card w-100">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <h4>Mis Productos</h4>
                @if (session()->has('message'))
                    <div wire:poll.3s class="alert alert-success p-1 m-0">{{ session('message') }}</div>
                @endif
                <div>
                    <a href="{{ route('addProduct.cotizador') }}" class="btn btn-sm btn-info">Agregar Nuevo
                        Producto</a>
                </div>
            </div>
            <div>
                <input wire:model='keyWord' type="text" class="form-control" name="search" id="search"
                    placeholder="Buscar">
                <br>
            </div>
            <div class="row">
                @foreach ($products as $row)
                    @php
                        $row = $row->product;
                    @endphp
                    @if ($row)
                        <div class="col-md-4 col-lg-3 col-sm-6  d-flex justify-content-center">
                            <div class="card product-info">
                                <div class="card-body text-center shadow-sm p-2">
                                    @php
                                        $priceProduct = $row->price;
                                    @endphp
                                    <p class="stock-relative m-0 mb-1 pt-1" style="font-size: 16px">Stock: <span
                                            style="font-weight: bold">{{ $row->stock }}</span></p>
                                    <div class="d-flex justify-content-start">
                                        {{-- <h5><strong>Informacion del Cliente</strong></h5> --}}
                                        <div class="text-success" style="width: 25px; cursor: pointer;"
                                            wire:click="editProduct({{ $row->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                                                <path fill-rule="evenodd"
                                                    d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="text-danger" style="width: 25px; cursor: pointer;"
                                            onclick="eliminar({{ $row->id }})">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-row flex-sm-column">
                                        <div class="text-center" style="height: 110px">
                                            <img src="{{ $row->firstImage ? $row->firstImage->image_url : '' }}"
                                                class="card-img-top " alt="{{ $row->name }}"
                                                style="width: auto; max-width: 100px; max-height: 110px; height: auto">
                                        </div>
                                        <div class="info-products">
                                            <h5 class="card-title m-0" style="text-transform: capitalize">
                                                {{ Str::limit($row->name, 22, '...') }}</h5>
                                            <p class=" m-0 pt-1" style="font-size: 16px"><strong>SKU:</strong>
                                                {{ $row->sku }}</p>
                                            <p class="m-0 mb-1 pt-1 d-sm-none" style="font-size: 16px">Stock: <span
                                                    style="font-weight: bold">{{ $row->stock }}</span></p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p class=" m-0 pt-1" style="font-weight: bold">$
                                                    {{ $priceProduct }}</p>
                                                <a href="{{ route('show.product', ['product' => $row->id]) }}"
                                                    class="btn btn-sm btn-primary">
                                                    Cotizar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center w-100">
        {{ $products->links() }}
    </div>
    <div wire:ignore.self class="modal fade" id="editarInfoProduct" tabindex="-1"
        aria-labelledby="editarInfoProductLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarInfoProductLabel">Editar
                        Informacion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($productEdit)
                        <div class="row">
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
                                        <div class="btn btn-danger btn-sm" wire:click="limpiarImagen()">Eliminar</div>
                                    @else
                                        <p>No hay un logo de cliente cargado</p>
                                    @endif
                                    @if ($errors->has('imagen'))
                                        <span class="text-danger">{{ $errors->first('imagen') }}</span>
                                    @endif
                                </div>
                                <p>Si tu imagen pesa mas de 500 KB, puedes comprimirla <a
                                        href="https://www.iloveimg.com/es/comprimir-imagen" target="_blank">aqui</a>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    @if ($imagen)
                                        <div class="text-center">
                                            <p>Imagen del Producto</p>
                                            <p class="text-warning">Revisa que tenga fondo
                                                blanco o que se un archivo PNG</p>
                                            <img src="{{ $imagen->temporaryUrl() }}" alt=""
                                                style="max-width: 100%; height: 160px;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-primary" wire:click="guardar({{ $product_id }})">Actualizar
                                    Producto</button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('showModalEditar', event => {
            $('#editarInfoProduct').modal('show')
        })
        window.addEventListener('hideModalEditar', event => {
            $('#editarInfoProduct').modal('hide')
        })

        function eliminar(id) {
            Swal.fire({
                title: 'Esta seguro?',
                text: "Desea eliminar este producto permanentemente!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si!',
                cancelButtonText: 'Cancelar!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let respuesta = @this.deleteProduct(id)
                    Swal.fire('Por Favor Espere');
                    respuesta
                        .then((response) => {
                            console.log(response);
                            if (response == 1) {
                                Swal.fire(
                                    'Se ha elimiado este producto correctamente',
                                    '',
                                    'success'
                                )
                            }
                            /* if (response != 1) {
                                Swal.fire(
                                    'Se enviaron los accesos, pero estos no se pudieron enviar',
                                    response, 'success')
                            } */
                        }, function() {
                            // one or more failed
                            Swal.fire('¡Error al elimiar el producto!', '', 'error')
                        });
                }
            })
        }
    </script>
    <style>
        .product-info {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .info-products {
            flex-grow: 1;
            text-align: left;
        }

        @media(min-width:576px) {
            .product-info {
                width: 14rem;
                margin-bottom: 1.5rem;
            }

            .info-products {
                flex-grow: 0;
                text-align: center;
            }

            .stock-relative {
                display: block !important;
            }
        }

        .stock-relative {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #fcfcfcf2;
            border-radius: 5px;
            padding: 0px 5px;
            display: none;
        }
    </style>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</div>
