<div class="text-center mt-3">
    <button class="w-full md:w-auto bg-green-200 p-3  rounded-md hover:bg-green-300"
        onclick="customToggleModal('modalNewProduct', 1)">
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

    <!-- Show Product modal -->
    <div wire:ignore.self id="modalNewProduct" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-3xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b Proverdor del Producto
rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Añadir producto a la cotizacion que no esta en el catalogo
                    </h3>
                    <button type="button" onclick="customToggleModal('modalNewProduct', 0)"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    @if (!$thereProduct)
                        @if ($isNewProduct)
                            <div class="grid grid-cols-2 gap-3">
                                <div class="col-span-1">
                                    <div class="form-group">
                                        <label for="nombre">Nombre</label>
                                        <input type="text"
                                            class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                                            wire:model="nombre" placeholder="Nombre del producto"
                                            value="{{ old('nombre') }}">
                                        @if ($errors->has('nombre'))
                                            <span class="text-red-500">{{ $errors->first('nombre') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <div class="form-group">
                                        <label for="nombre">Descripcion</label>
                                        <input type="text"
                                            class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                                            wire:model="descripcion" placeholder="Descripción del producto"
                                            value="{{ old('descripcion') }}">
                                        @if ($errors->has('descripcion'))
                                            <span class="text-red-500">{{ $errors->first('descripcion') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between flex-sm-row flex-column">
                                            <label class="">Precio</label>
                                            <div class="form-check mt-2 mb-1" wire:click='changeTypePrice'>
                                                <input class="form-check-input" type="checkbox"
                                                    {{ $priceScales ? 'checked' : '' }}>
                                                <label class="form-check-label" style="font-weight: bold;"
                                                    for="defaultCheck1">
                                                    Agregar escalas de precios
                                                </label>
                                            </div>
                                        </div>
                                        @if (!$priceScales)
                                            <input type="number"
                                                class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                                                wire:model="precio" placeholder="Precio del producto"
                                                value="{{ old('precio') }}">
                                            @if ($errors->has('precio'))
                                                <span class="text-red-500">{{ $errors->first('precio') }}</span>
                                            @endif
                                        @else
                                            @if (count($infoScales) > 0)
                                                <div class="relative overflow-x-auto my-2">
                                                    <table
                                                        class="w-full text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                                        <thead
                                                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                                            <tr>
                                                                <th>Escala Inicial</th>
                                                                <th>Escala Final</th>
                                                                <th>Escala Costo</th>
                                                                <th>Acciones</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($infoScales as $key => $scale)
                                                                <tr>
                                                                    <td>{{ $scale['initial'] }}</td>
                                                                    <td>{{ $scale['final'] }}</td>
                                                                    <td>$ {{ $scale['cost'] }}</td>
                                                                    <td>
                                                                        <div class="btn-group">
                                                                            <button type="button"
                                                                                class="btn btn-warning btn-sm"
                                                                                wire:click='editScale({{ $key }})'>
                                                                                <div style="width: 1rem">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        class="h-6 w-6" fill="none"
                                                                                        viewBox="0 0 24 24"
                                                                                        stroke="currentColor"
                                                                                        stroke-width="2">
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                                    </svg>
                                                                                </div>
                                                                            </button>
                                                                            <button type="button"
                                                                                class="btn btn-danger btn-sm"
                                                                                onclick='deleteEscala({{ $key }})'>
                                                                                <div style="width: 1rem">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        class="h-6 w-6" fill="none"
                                                                                        viewBox="0 0 24 24"
                                                                                        stroke="currentColor"
                                                                                        stroke-width="2">
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                                    </svg>
                                                                                </div>
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            <div class="text-center">
                                                <button type="button"
                                                    class="bg-gray-200 p-3  rounded-md hover:bg-gray-300"
                                                    wire:click='openScale'>
                                                    Agregar una nueva escala
                                                </button>
                                            </div>
                                            @if ($errors->has('infoScales'))
                                                <span class="text-red-500">{{ $errors->first('infoScales') }}</span>
                                            @endif
                                        @endif
                                    </div>
                                    @include('cotizador.mis_productos.sections.scales-modal')
                                </div>
                                <div class="col-span-1">
                                    <div class="form-group">
                                        <label for="nombre">Stock</label>
                                        <input type="number"
                                            class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500"
                                            wire:model="stock"
                                            placeholder="Cantidad disponible o Cantidad que desea cotizar"
                                            value="{{ old('stock') }}">
                                        @if ($errors->has('stock'))
                                            <span class="text-red-500">{{ $errors->first('stock') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <div class="form-group">
                                        <label for="nombre">Color</label>
                                        <input type="text"
                                            class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                                            wire:model="color" placeholder="Color del producto"
                                            value="{{ old('color') }}">
                                        @if ($errors->has('color'))
                                            <span class="text-red-500">{{ $errors->first('color') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <div class="form-group">
                                        <label for="nombre">Proveedor</label>
                                        <input type="text"
                                            class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                                            wire:model="proveedor" placeholder="Proverdor del producto"
                                            value="{{ old('proveedor') }}">
                                        @if ($errors->has('proveedor'))
                                            <span class="text-red-500">{{ $errors->first('proveedor') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-span-1">
                                    <div class="form-group">

                                        <label for="nombre">Imagen</label>
                                        <div class="form-group" x-data="{ isUploading: false, progress: 5 }"
                                            x-on:livewire-upload-start="isUploading = true"
                                            x-on:livewire-upload-finish="isUploading = false"
                                            x-on:livewire-upload-error="isUploading = false"
                                            x-on:livewire-upload-progress="progress = $event.detail.progress">

                                            <input type="file"
                                                class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                                                wire:model="imagen" accept="image/*">
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
                                            <p>No hay un logo de cliente cargado</p>
                                        @endif
                                        @if ($errors->has('imagen'))
                                            <span class="text-red-500">{{ $errors->first('imagen') }}</span>
                                        @endif
                                    </div>
                                    <p>Si tu imagen pesa más de 500 KB, puedes comprimirla <a
                                            href="https://www.iloveimg.com/es/comprimir-imagen"
                                            target="_blank">aquí</a></p>
                                </div>
                                <div class="col-span-1">
                                    <div class="form-group">
                                        @if ($imagen)
                                            <div class="text-center">
                                                <p>Imagen del Producto</p>
                                                <p class="text-warning">Revisa que tenga fondo blanco o que se un
                                                    archivo PNG</p>
                                                <img src="{{ $imagen->temporaryUrl() }}" alt=""
                                                    style="max-width: 100%; height: 160px;">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button class="bg-green-300 p-3  rounded-md hover:bg-green-400"
                                        wire:click="guardar()">Utilizar
                                        Producto</button>
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
                        <div class="mb-3">
                            <div class="img-container w-25 text-center">
                                <img id="imgBox" style="max-width: 150px; max-height: 200px"
                                    src="{{ $producto->firstImage ? $producto->firstImage->image_url : asset('img/default.jpg') }}"
                                    class="img-fluid" alt="imagen">
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="font-bold">{{ $producto->name }}</p>
                                @if ($producto->precio_unico)
                                    <div>
                                        <p class="text-lg font-bold">
                                            $ {{ round($priceProduct + $priceProduct * ($utilidad / 100), 2) }}</p>
                                        </p>
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="grow ">
                                    <div class="hidden md:block">
                                        <p class="my-1">SKU Interno: {{ $producto->internal_sku }}</p>
                                        <p class="my-1">SKU Proveedor: {{ $producto->sku }}</p>
                                    </div>
                                    <div class="block md:hidden space-y-1 mb-2">
                                        <div>
                                            <p>Sku Interno:</p>
                                            <p>{{ $producto->internal_sku }}</p>
                                        </div>
                                        <div>
                                            <p>Sku Proveedor:</p>
                                            <p>{{ $producto->sku }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-green-500">Disponibles:<strong> {{ $producto->stock }}</strong>
                                    </p>
                                </div>
                            </div>
                            <p>{{ $producto->description }}</p>
                        </div>
                        @livewire('components.formulario-de-cotizacion', ['productNewAdd' => $producto])
                    @endif
                </div>
                <div class="p-4 md:p-5 space-y-4">
                    <button type="button" class="btn btn-secondary"
                        onclick="customToggleModal('modalNewProduct', 0)">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function deleteEscala(id) {
            Swal.fire({
                title: '¿Está seguro?',
                text: "¿Desea eliminar esta escala?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    let respuesta = @this.deleteScale(id)
                    Swal.fire('Por Favor Espere');
                    respuesta
                        .then((response) => {
                            console.log(response);
                            if (response == 1) {
                                Swal.fire(
                                    'Se ha elimiado esta escala correctamente',
                                    '',
                                    'success'
                                )
                            }
                        }, function() {
                            // one or more failed
                            Swal.fire('¡Error al elimiar el producto!', '', 'error')
                        });
                }
            })
        }
    </script>
</div>
