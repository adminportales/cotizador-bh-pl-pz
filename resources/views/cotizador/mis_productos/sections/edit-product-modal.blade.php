<!-- Show Product modal -->
<div wire:ignore.self id="editarInfoProduct" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-5xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Editar Informacion
                </h3>
                <button type="button" onclick="customToggleModal('editarInfoProduct', 0)"
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
                @if ($productEdit)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="col-span-1">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text"
                                    class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                                    wire:model="nombre" placeholder="Nombre del producto"
                                    value="{{ old('nombre') }}">
                                @if ($errors->has('nombre'))
                                    <span class="text-danger">{{ $errors->first('nombre') }}</span>
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
                                    <span class="text-danger">{{ $errors->first('descripcion') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-span-1">
                            <div class="form-group">
                                <div class="flex justify-between sm:flex-row flex-col">
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
                                        <span class="text-danger">{{ $errors->first('precio') }}</span>
                                    @endif
                                @else
                                    @if (count($infoScales) > 0)
                                    <div class="relative overflow-x-auto my-2">
                                        <table class="w-full text-left rtl:text-right text-gray-500 dark:text-gray-400">
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
                                        <button type="button" class="bg-gray-200 p-3  rounded-md hover:bg-gray-300 shadow-none "
                                            wire:click='openScale'>
                                            Agregar una nueva escala
                                        </button>
                                    </div>
                                    @if ($errors->has('infoScales'))
                                        <span class="text-danger">{{ $errors->first('infoScales') }}</span>
                                    @endif
                                @endif
                            </div>

                           {{--  <div wire:ignore.self class="modal fade" id="addScaleModal" tabindex="-1"
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
                                                <p class="m-0">Colocar las escalas de precio en orden, de menor
                                                    a mayor y sin repetir
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
                                                <label for="cantidad" class="text-dark m-0"><strong>Escala
                                                        Inicial</strong>
                                                </label>
                                                <input type="number" name="cantidad" wire:model="inicial"
                                                    placeholder="Escala Inicial"
                                                    class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 ">
                                            </div>
                                            <div class="form-group m-0 mb-1 col-md-4">
                                                <label for="cantidad" class="text-dark m-0"><strong>Escala
                                                        Final</strong>
                                                </label>
                                                <input type="number" name="cantidad" wire:model="final"
                                                    placeholder="Escala Final"
                                                    class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 ">
                                            </div>
                                            <div class="form-group m-0 mb-1 col-md-4">
                                                <label for="margen" class="text-dark m-0"><strong>Costo</strong>
                                                </label>
                                                <input type="number" name="margen" wire:model="costo"
                                                    placeholder="Costo"
                                                    class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 ">
                                            </div>
                                            <div class="d-flex col-12 flex-column">

                                                {{-- Errores de validacion
                                                @if ($errors->has('inicial'))
                                                    <span class="text-danger">{{ $errors->first('inicial') }}</span>
                                                @endif
                                                @if ($errors->has('costo'))
                                                    <span class="text-danger">{{ $errors->first('costo') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-info btn-sm"
                                                wire:click='closeScale'>Cerrar</button>
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
                            </div> --}}
                            @include('cotizador.mis_productos.sections.scales-modal')
                        </div>
                        <div class="col-span-1">
                            <div class="form-group">
                                <label for="nombre">Stock</label>
                                <input type="number"
                                    class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                                    wire:model="stock" placeholder="Cantidad disponible o Cantidad que desea cotizar"
                                    value="{{ old('stock') }}">
                                @if ($errors->has('stock'))
                                    <span class="text-danger">{{ $errors->first('stock') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-span-1">
                            <div class="form-group">
                                <label for="nombre">Color</label>
                                <input type="text"
                                    class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                                    wire:model="color" placeholder="Color del producto" value="{{ old('color') }}">
                                @if ($errors->has('color'))
                                    <span class="text-danger">{{ $errors->first('color') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-span-1">
                            <div class="form-group">
                                <label for="nombre">Proveedor</label>
                                <input type="text"
                                    class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                                    wire:model="proveedor" placeholder="Proverdor del Producto"
                                    value="{{ old('proveedor') }}">
                                @if ($errors->has('proveedor'))
                                    <span class="text-danger">{{ $errors->first('proveedor') }}</span>
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
                                            x-bind:style="`width: ${progress}%`" aria-valuenow="25" aria-valuemin="0"
                                            aria-valuemax="100"></div>
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
                        <div class="col-span-1">
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
                            <button class="bg-yellow-200 p-3  rounded-md hover:bg-yellow-300" wire:click="guardar({{ $product_id }})">Actualizar
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
        customToggleModal('editarInfoProduct', 1)
    })
    window.addEventListener('hideModalEditar', event => {
        customToggleModal('editarInfoProduct', 0)
    })
</script>
