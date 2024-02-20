<div>
    <ol class="relative border-l border-gray-200">
        <li class="mb-2 ml-6">
            <span
                class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white ">1
            </span>
            <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900 ">
                Personalización de la técnica
            </h3>
            <div class="bg-gray-50 p-3 rounded-sm">
                <div class="grid grid-cols-2 gap-2">
                    <div class="md:col-span-1 col-span-2">
                        <label for="" class="text-sm font-semibold">Material</label>
                        <select name="" id=""
                            class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                            wire:model="materialSeleccionado" wire:change="resetTecnique">
                            <option value="">Seleccione el material</option>
                            @foreach ($materiales as $material)
                                <option value="{{ $material->id }}">{{ $material->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if ($techniquesAvailables)
                        <div class="md:col-span-1 col-span-2">
                            <label for="" class="text-sm font-semibold">Tecnica</label>
                            <select name="" id=""
                                class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                                wire:model="tecnicaSeleccionada" wire:change="resetSizes">
                                <option value="">Seleccione la tecnica</option>
                                @foreach ($techniquesAvailables as $technique)
                                    <option value="{{ $technique->id }}">{{ $technique->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if ($sizesAvailables)
                        <div class="md:col-span-1 col-span-2">
                            <label for="" class="text-sm font-semibold">Tamaño</label>
                            <select name="" id=""
                                class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                                wire:model="sizeSeleccionado">
                                <option value="">Seleccione el tamaño</option>
                                @foreach ($sizesAvailables as $size)
                                    <option value="{{ $size->id }}">{{ $size->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="md:col-span-1 col-span-2">
                        <label for="" class="text-sm font-semibold">Cantidad de Colores/Logos</label>
                        <input type="number" name="colores" wire:model="colores" placeholder="Cantidad de Colores"
                            class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                            min="0">
                    </div>
                    @if ($preciosDisponibles)
                        <div class="col-span-2">
                            <p class="font-semibold mb-2">
                                Escala de precios de acuerdo al material,
                                tecnica y tamaño seleccionados
                            </p>
                            <div class="grid grid-cols-9 text-sm">
                                <div class="col-span-3 border-gray-200 border-b border-t font-semibold p-2 ">Escala
                                </div>
                                <div class="col-span-3 p-2 border-b border-t font-semibold border-gray-200  "> Precio
                                </div>
                                <div class="col-span-3 p-2 border-b border-t font-semibold border-gray-200  "> Tipo de
                                    Precio</div>
                                @foreach ($preciosDisponibles as $item)
                                    <div class="col-span-3  border-b border-gray-200 p-2">{{ $item->escala_inicial }} -
                                        {{ $item->escala_final }}</div>
                                    <div class="col-span-3 p-2  border-b border-gray-200"> $ {{ $item->precio }} </div>
                                    <div class="col-span-3 p-2  border-b border-gray-200">
                                        {{ $item->tipo_precio == 'F' ? 'Precio por Articulo' : 'Precio Fijo' }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </li>
        <li class="mb-2 ml-6">
            <span
                class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white ">2
            </span>
            <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900 ">
                Costo y Utilidad
            </h3>
            <div class="">
                <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200">
                    <ul class="flex w-full">
                        <li class="hover:border-b-2 rounded-t-lg cursor-pointer text-center flex-1 {{ !$priceScales ? 'bg-gray-50 active border-gray-300 border-b-2' : '' }}"
                            wire:click='changeTypePrice(false)'>
                            <a
                                class="inline-block p-4 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300">Cotización
                                normal</a>
                        </li>
                        <li class="hover:border-b-2 rounded-t-lg cursor-pointer text-center flex-1 {{ $priceScales ? 'bg-gray-50 active border-gray-300 border-b-2' : '' }}"
                            wire:click='changeTypePrice(true)'>
                            <a class="inline-block p-4 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300"
                                aria-current="page">Cotización por escalas</a>
                        </li>
                    </ul>
                </div>
                <div class="grid grid-cols-2 gap-2 bg-gray-50 p-3 rounded-sm">

                    @if (!$priceScales)
                        <div class="col-span-2">
                            <label for="" class="text-sm font-semibold">Costo adicionales de operación</label>
                            <input type="number" name="operacion" wire:model="operacion"
                                placeholder="Costo de operación"
                                class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 ">
                        </div>
                        <div class="md:col-span-1 col-span-2">
                            <label for="" class="text-sm font-semibold">Margen de Utilidad</label>
                            <input type="number" name="margen" wire:model="utilidad" placeholder="Margen de utilidad"
                                class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 ">
                        </div>
                        <div class="md:col-span-1 col-span-2">
                            <label for="" class="text-sm font-semibold">Cantidad</label>
                            <input type="number" name="cantidad" wire:model="cantidad"
                                placeholder="Cantidad de productos a cotizar"
                                class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 ">
                        </div>
                    @else
                        <div class="m-0 mb-1 col-span-2 text-center">
                            @if (count($this->priceScalesComplete) > 0)
                                <div class="col-span-2">
                                    <p class="font-semibold mb-2">
                                        Precios Por Cantidad de Artículos
                                    </p>
                                    <div class="relative overflow-x-auto">
                                        <table
                                            class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                            <thead
                                                class="text-xs text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                                <tr>
                                                    <th scope="col" class="px-2 py-2">
                                                        Cantidad
                                                    </th>
                                                    <th scope="col" class="px-2 py-2">
                                                        Técnica
                                                    </th>
                                                    <th scope="col" class="px-2 py-2">
                                                        Utilidad
                                                    </th>
                                                    <th scope="col" class="px-2 py-2">
                                                        Unitario
                                                    </th>
                                                    <th scope="col" class="px-2 py-2">
                                                        Costo de Operación
                                                    </th>
                                                    <th scope="col" class="px-2 py-2">
                                                        Total
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($priceScalesComplete as $key => $item)
                                                    <tr
                                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                                        <td class="px-2 py-2 text-center">
                                                            {{ $item['quantity'] }}
                                                        </td>
                                                        <td class="px-2 py-2 text-center">
                                                            $ {{ number_format($item['tecniquePrice'], 2, '.', ',') }}
                                                        </td>
                                                        <td class="px-2 py-2 text-center">
                                                            {{ $item['utility'] }} %
                                                        </td>
                                                        <td class="px-2 py-2 text-center">
                                                            $ {{ number_format($item['unit_price'], 2, '.', ',') }}
                                                        </td>
                                                        <td class="px-2 py-2 text-center">
                                                            $ {{ number_format($item['operacion'], 2, '.', ',') }}
                                                        </td>
                                                        <td class="px-2 py-2 text-center">
                                                            $ {{ number_format($item['total_price'], 2, '.', ',') }}
                                                        </td>
                                                        <td class="px-2 py-2 text-center">
                                                            <button type="button" class="btn btn-warning btn-sm"
                                                                wire:click="editScale({{ $key }})">
                                                                <div>
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="h-6 w-6" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor"
                                                                        stroke-width="2">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                    </svg>
                                                                </div>
                                                            </button>
                                                            <button type="button" class="btn btn-danger btn-sm"
                                                                wire:click="openDeleteScale({{ $key }})">
                                                                <div>
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="h-6 w-6" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor"
                                                                        stroke-width="2">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                    </svg>
                                                                </div>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                            <button type="button"
                                class="inline  bg-white hover:bg-gray-500 shadow focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-xl text-sm px-5 py-2.5 text-center mr-2 mb-2 "
                                wire:click='openScale'>
                                Agregar una nueva escala
                            </button>

                            <!-- Main modal -->
                            <div wire:ignore.self id="modal-scales-quote" data-modal-backdrop="static" tabindex="-1"
                                aria-hidden="true"
                                class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                <div class="relative w-full max-w-2xl max-h-full">
                                    <!-- Modal content -->
                                    <div class="relative bg-white rounded-lg shadow">
                                        <!-- Modal header -->
                                        <div class="flex items-start justify-between p-4 border-b rounded-t">
                                            <h3 class="text-xl font-semibold text-gray-900 ">
                                                {{ $editScale ? 'Editar la cantidad de Productos' : 'Agregar una nueva cantidad' }}
                                            </h3>
                                        </div>
                                        <!-- Modal body -->
                                        <div class="p-6 space-y-6">
                                            <div class="grid grid-cols-2 gap-2 text-left">
                                                <div class="col-span-2">
                                                    <label for="" class="text-sm font-semibold">Costo de
                                                        operación</label>
                                                    <input type="number" name="operacion" wire:model="operacion"
                                                        placeholder="Costo de operación"
                                                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 ">
                                                </div>
                                                <div class="col-span-2 md:col-span-1">
                                                    <label for=""
                                                        class="text-sm font-semibold">Cantidad</label>
                                                    <input type="number" name="cantidad" wire:model="cantidad"
                                                        placeholder="Cantidad de productos a cotizar"
                                                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 ">
                                                </div>

                                                <div class="col-span-2 md:col-span-1">
                                                    <label for="" class="text-sm font-semibold">Utilidad por 
                                                        partida
                                                    </label>
                                                    <input type="number" name="margen" wire:model="utilidad"
                                                        placeholder="Margen de Utilidad. Max: 99" max="99"
                                                        maxlength="2" max="100"
                                                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 ">
                                                </div>
                                                <div class="col-span-2">
                                                    <label for="" class="text-sm font-semibold block">Precio
                                                        actual de la técnica (por
                                                        tinta):
                                                        <span class="font-bold"> $
                                                            {{ $precioDeTecnica }}</span></label>
                                                    <label for="" class="text-sm font-semibold block">Precio
                                                        actual de la técnica (por
                                                        artículo con {{ $colores }} tintas seleccionadas): <span
                                                            class="font-bold"> $
                                                            {{ $precioDeTecnica * $colores }}</span></label>
                                                    <input type="number" name="newTechnique"
                                                        wire:model="precioTecnicaEscala"
                                                        placeholder="Nuevo precio de la técnica por tinta (Opcional)"
                                                        class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 ">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal footer -->
                                        <div
                                            class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b ">
                                            @if ($editScale)
                                                <button type="button" class="btn btn-warning btn-sm"
                                                    wire:click='updateScale'>Editar</button>
                                            @else
                                                <button type="button" class="btn btn-primary btn-sm"
                                                    wire:click='addScale'>Agregar</button>
                                            @endif
                                            <button wire:click='closeScale' type="button"
                                                class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 ">Cancelar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </li>
        <li class="mb-2 ml-6">
            <span
                class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white ">3
            </span>
            <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900 ">
                Configuración
            </h3>
            <div class="bg-gray-50 p-3 rounded-sm">
                <div class="grid grid-cols-2 gap-2">
                    <div class="md:col-span-1 col-span-2">
                        <label for="" class="text-sm font-semibold">Días de entrega</label>
                        <input type="number" name="dias" wire:model="entrega"
                            placeholder="Días de entrega estimada"
                            class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 ">
                    </div>
                    <div class="md:col-span-1 col-span-2">
                        <label for="" class="text-sm font-semibold">Naturales o Hábiles</label>
                        <select name="" id=""
                            class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                            wire:model="typeDays">
                            <option value="">Seleccione el tipo de días (opcional)</option>
                            <option value="1">Hábiles</option>
                            <option value="2">Naturales</option>
                        </select>
                    </div>
                    @if (!$priceScales)
                        <div class="col-span-2">
                            <label for="" class="text-sm font-semibold block">Precio actual de la técnica (por
                                tinta):
                                <span class="font-bold"> $ {{ $precioDeTecnica }}</span></label>
                            <label for="" class="text-sm font-semibold block">Precio actual de la técnica (por
                                artículo con {{ $colores }} tintas seleccionadas): <span class="font-bold"> $
                                    {{ $precioDeTecnica * $colores }}</span></label>
                            <input type="number" name="newTechnique" wire:model="newPriceTechnique"
                                placeholder="Nuevo precio de la técnica por tinta (Opcional)"
                                class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 ">
                        </div>
                    @endif
                    <div class="col-span-2">
                        <label for="" class="text-sm font-semibold block">Coloca la descripción que se
                            mostrara
                            en la
                            cotización:</label>
                        <textarea rows="3" name="newDescription" wire:model="newDescription"
                            class="block w-full p-3 mb-2 text-gray-900 border border-gray-300 rounded-md bg-white sm:text-xs focus:ring-blue-500 focus:border-blue-500 "
                            placeholder="Descripción que se mostrará en la cotización. Si se deja vacío, se mostrará la descripción que se encuentra en la parte de arriba. (Opcional)"> </textarea>
                    </div>
                    <div class="col-span-2">
                        <label for="" class="text-sm font-semibold block">Imagen que sera visualizada en la
                            cotización</label>
                        @if (!$imageSelected)
                            <button type="button"
                                class="w-full block mt-2 text-white bg-green-500 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded text-sm px-5 py-2.5 text-center mr-2 mb-2 "
                                data-toggle="modal" wire:click='openModalImage'>
                                Selecciona la imagen que se vera en la cotización
                            </button>
                        @else
                            <div class="flex justify-between items-center">
                                <div class="text-center">
                                    <img src="{{ $imageSelected }}" class="rounded"
                                        style=" width: auto; max-width: 260px; max-height: 170px"
                                        alt="{{ $imageSelected }}">
                                </div>
                                <div class="flex flex-col">
                                    <button type="button"
                                        class="inline-block text-white bg-green-500 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-xl text-sm px-5 py-2.5 text-center mr-2 mb-2 "
                                        data-toggle="modal" wire:click='openModalImage'>
                                        Actualiza la imagen que se vera en la cotización
                                    </button>
                                    <button type="button"
                                        class="inline-block text-white bg-green-500 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-xl text-sm px-5 py-2.5 text-center mr-2 mb-2 "
                                        wire:click="eliminarImagen">
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        @endif
                        <div wire:ignore.self id="modal-image-quote" data-modal-backdrop="static" tabindex="-1"
                            aria-hidden="true"
                            class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative w-full max-w-2xl max-h-full">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow">
                                    <!-- Modal header -->
                                    <div class="flex items-start justify-between p-4 border-b rounded-t">
                                        <h3 class="text-xl font-semibold text-gray-900 ">
                                            Seleccionar la imagen que se visualizara en la cotización
                                        </h3>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="p-6 space-y-6">
                                        <div class="flex justify-between">
                                            <p>Solo en caso de que no quieras que se muestre la primera imagen</p>
                                            <div wire:loading wire:target='seleccionarImagen'>
                                                <p>Espere...</p>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-6 gap-2">
                                            {{-- Quitar elementos duplicados --}}
                                            @php
                                                $images = $product->images->unique('image_url');
                                            @endphp
                                            @foreach ($images as $image)
                                                <div class="col-span-3 sm:col-span-2">
                                                    <div class="w-full img-select object-contain {{ $imageSelected == $image->image_url ? 'selected' : '' }}"
                                                        wire:click="seleccionarImagen('{{ $image->image_url }}')">
                                                        <img src="{{ $image->image_url }}"
                                                            class="rounded w-full object-cover" style=" width: auto;"
                                                            alt="{{ $image->image_url }}">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <!-- Modal footer -->
                                    <div class="flex items-center p-6 space-x-2 border-t border-gray-200 rounded-b">
                                        <button wire:click='closeModalImage' type="button"
                                            class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 ">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        <li class="mb-2 ml-6">
            <span
                class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -left-3 ring-8 ring-white ">4
            </span>
            <h3 class="flex items-center mb-1 text-lg font-semibold text-gray-900 ">
                Finalizar Cotización
                @php

                    $precioDeTecnica = 0;

                    if (!$this->priceScales) {
                        if (!is_numeric($this->cantidad)) {
                            $this->cantidad = null;
                        }

                        if (!is_numeric($this->newPriceTechnique)) {
                            $this->newPriceTechnique = null;
                        }

                        if ((int) $this->cantidad > 0 && $preciosDisponibles && $this->sizeSeleccionado !== null) {
                            foreach ($preciosDisponibles as $precioDisponible) {
                                if ($precioDisponible->escala_final != null) {
                                    if ((int) $this->cantidad >= $precioDisponible->escala_inicial && (int) $this->cantidad <= $precioDisponible->escala_final) {
                                        $this->priceTechnique = $precioDisponible;
                                        $precioDeTecnica = $precioDisponible->tipo_precio == 'D' ? round($precioDisponible->precio / (int) $this->cantidad, 2) : $precioDisponible->precio;
                                    }
                                } elseif ($precioDisponible->escala_final == null) {
                                    if ((int) $this->cantidad >= $precioDisponible->escala_inicial) {
                                        $this->priceTechnique = $precioDisponible;
                                        $precioDeTecnica = $precioDisponible->tipo_precio == 'D' ? round($precioDisponible->precio / (int) $this->cantidad, 2) : $precioDisponible->precio;
                                    }
                                }
                            }
                        } else {
                            $precioDeTecnica = 0;
                            $this->priceTechnique = null;
                        }

                        $precioDeTecnicaUsado = $precioDeTecnica;
                        if ($this->newPriceTechnique != null && $this->newPriceTechnique >= 0) {
                            $precioDeTecnicaUsado = $this->newPriceTechnique;
                        }

                        if (!$this->product->precio_unico) {
                            $escalasDeProductoDisponibles = $this->product->precios;
                            if ((int) $this->cantidad > 0 && $escalasDeProductoDisponibles) {
                                foreach ($escalasDeProductoDisponibles as $escalaDeCosto) {
                                    if ($escalaDeCosto->escala_final != null) {
                                        if ((int) $this->cantidad >= $escalaDeCosto->escala_inicial && (int) $this->cantidad <= $escalaDeCosto->escala_final) {
                                            $this->precio = $escalaDeCosto->price;
                                        }
                                    } elseif ($escalaDeCosto->escala_final == null) {
                                        if ((int) $this->cantidad >= $escalaDeCosto->escala_inicial) {
                                            $this->precio = $escalaDeCosto->price;
                                        }
                                    }
                                }
                            }
                        }

                        $nuevoPrecio = round(($this->precio + $precioDeTecnicaUsado * $this->colores + $this->operacion) / ((100 - $this->utilidad) / 100), 2);

                        $this->precioCalculado = $nuevoPrecio;
                        $this->precioTotal = $nuevoPrecio * $this->cantidad;
                    } else {
                        $this->priceScalesComplete = [];
                        foreach ($this->infoScales as $info) {
                            if ((int) $info['quantity'] > 0 && $preciosDisponibles && $this->sizeSeleccionado !== null) {
                                foreach ($preciosDisponibles as $precioDisponible) {
                                    if ($precioDisponible->escala_final != null) {
                                        if ((int) $info['quantity'] >= $precioDisponible->escala_inicial && (int) $info['quantity'] <= $precioDisponible->escala_final) {
                                            $this->priceTechnique = $precioDisponible;
                                            $precioDeTecnica = $precioDisponible->tipo_precio == 'D' ? round($precioDisponible->precio / (int) $info['quantity'], 2) : $precioDisponible->precio;
                                        }
                                    } elseif ($precioDisponible->escala_final == null) {
                                        if ((int) $info['quantity'] >= $precioDisponible->escala_inicial) {
                                            $this->priceTechnique = $precioDisponible;
                                            $precioDeTecnica = $precioDisponible->tipo_precio == 'D' ? round($precioDisponible->precio / (int) $info['quantity'], 2) : $precioDisponible->precio;
                                        }
                                    }
                                }
                            } else {
                                $precioDeTecnica = 0;
                                $this->priceTechnique = null;
                            }

                            if (!is_numeric($info['quantity'])) {
                                $info['quantity'] = null;
                            }

                            if (!is_numeric($info['tecniquePrice'])) {
                                $info['tecniquePrice'] = null;
                            }
                            $precioDeTecnicaUsado = $precioDeTecnica;
                            if ($info['tecniquePrice'] != null && $info['tecniquePrice'] >= 0) {
                                $precioDeTecnicaUsado = $info['tecniquePrice'];
                            }

                            if (!$this->product->precio_unico) {
                                $escalasDeProductoDisponibles = $this->product->precios;
                                if ((int) $info['quantity'] > 0 && $escalasDeProductoDisponibles) {
                                    foreach ($escalasDeProductoDisponibles as $escalaDeCosto) {
                                        if ($escalaDeCosto->escala_final != null) {
                                            if ((int) $info['quantity'] >= $escalaDeCosto->escala_inicial && (int) $info['quantity'] <= $escalaDeCosto->escala_final) {
                                                $this->precio = $escalaDeCosto->price;
                                            }
                                        } elseif ($escalaDeCosto->escala_final == null) {
                                            if ((int) $info['quantity'] >= $escalaDeCosto->escala_inicial) {
                                                $this->precio = $escalaDeCosto->price;
                                            }
                                        }
                                    }
                                }
                            }
                            $nuevoPrecio = round(($this->precio + $precioDeTecnicaUsado * $this->colores + $info['operacion']) / ((100 - $info['utility']) / 100), 2);

                            array_push($this->priceScalesComplete, [
                                'quantity' => $info['quantity'],
                                'tecniquePrice' => $info['tecniquePrice'] != null ? floatval($info['tecniquePrice']) : $precioDeTecnica,
                                'utility' => $info['utility'],
                                'unit_price' => $nuevoPrecio,
                                'operacion' => $info['operacion'],
                                'total_price' => $nuevoPrecio * $info['quantity'],
                            ]);
                        }
                        if ((int) $this->cantidad > 0 && $preciosDisponibles && $this->sizeSeleccionado !== null) {
                            foreach ($preciosDisponibles as $precioDisponible) {
                                if ($precioDisponible->escala_final != null) {
                                    if ((int) $this->cantidad >= $precioDisponible->escala_inicial && (int) $this->cantidad <= $precioDisponible->escala_final) {
                                        $this->priceTechnique = $precioDisponible;
                                        $precioDeTecnica = $precioDisponible->tipo_precio == 'D' ? round($precioDisponible->precio / (int) $this->cantidad, 2) : $precioDisponible->precio;
                                    }
                                } elseif ($precioDisponible->escala_final == null) {
                                    if ((int) $this->cantidad >= $precioDisponible->escala_inicial) {
                                        $this->priceTechnique = $precioDisponible;
                                        $precioDeTecnica = $precioDisponible->tipo_precio == 'D' ? round($precioDisponible->precio / (int) $this->cantidad, 2) : $precioDisponible->precio;
                                    }
                                }
                            }
                        } else {
                            $precioDeTecnica = 0;
                            $this->priceTechnique = null;
                        }
                    }
                @endphp
                {{ $this->product->precios }}
                {{ ' precio de la tecnica' }}
                {{ $precioDeTecnica }}
                {{ 'precio de tecnica con colores' }}
                {{ $precioDeTecnicaUsado * $this->colores }}
                Finalizar Cotizacion
            </h3>
            <div class="bg-gray-50 p-3 rounded-sm">
                <div class=" sm:flex justify-between p-4">



                    @if (!$priceScales)
                        <div>
                            <h6 class="text-success">Precio Final por Artículo: $ {{ $precioCalculado }}</h6>
                            <h6 class="text-success">Precio Total: $ {{ $precioTotal }}</h6>
                        </div>
                    @endif

                    <div class="m-0 mb-1 text-center">
                        @if ($currentQuote)
                            <button type="button" class="btn btn-warning py-2 px-4"
                                wire:click='editarCurrentCotizacion'>Editar
                                cotizacion</button>
                        @elseif ($productEdit)
                            <button type="button" class="btn btn-info py-2 px-4"
                                wire:click='editarCotizacion'>Actualizar
                                cotización</button>
                        @elseif ($productNewAdd)
                            <button type="button" class="btn btn-secondary py-2 px-4"
                                wire:click='addNewProductToQuote'>Agregar
                                a la
                                cotización</button>
                        @else
                            <button type="button" class="btn btn-primary py-2 px-4"
                                wire:click='agregarCotizacion'>Añadir a
                                la cotización</button>
                        @endif
                    </div>
                </div>

                @if (session()->has('message'))
                    <div id="alert-2"
                        class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                        role="alert">
                        <div class="ms-3 text-sm font-medium grow">
                            <p class="text-center mb-3">{{ session('message') }}</p>
                            <div class="flex justify-center gap-x-3 w-full">
                                <a href="{{ url('/') }}" class="btn btn-sm btn-info w-50 px-1">
                                    Ir al cotizador </a>
                                <a href="{{ url('/cotizacion-actual') }}" class="btn btn-sm btn-secondary w-50 px-1">
                                    Ver mi cotización </a>
                            </div>
                        </div>
                        <button type="button"
                            class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700"
                            data-dismiss-target="#alert-2" aria-label="Close">
                            <span class="sr-only">Close</span>
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                        </button>
                    </div>
                @endif
                @if (count($errors) > 0)
                    <div wire:poll.12s class="p-4 text-sm text-red-800 rounded-lg bg-red-50  ">
                        @if ($errors->has('colores'))
                            <p>No se han especificado la cantidad de colores </p>
                        @endif
                        @if ($errors->has('operacion'))
                            <p>Es necesario el costo de operacion</p>
                        @endif
                        @if ($errors->has('utilidad'))
                            <p>No se ha colocado el margen de utilidad</p>
                        @endif
                        @if ($errors->has('cantidad'))
                            <p>No se ha colocado la cantidad de productos</p>
                        @endif
                        @if ($errors->has('entrega'))
                            <p>No se han colocado los dias de entrega</p>
                        @endif
                        @if ($errors->has('priceTechnique'))
                            <p> No se ha seleccionado una tecnica de personalizacion</p>
                        @endif
                        @if ($errors->has('infoScales'))
                            <p>No se ha creado una escala de precios</p>
                        @endif
                    </div>
                @endif
            </div>
        </li>
    </ol>

    <style>
        .img-select:hover {
            background-color: rgb(177, 191, 250);
        }

        .img-select.selected {
            background-color: rgb(177, 191, 250);
        }

        .img-select {
            padding: 10px;
            margin: 0 10px;
            border-radius: 10px;
            background-color: rgb(251, 251, 254);
        }
    </style>
    <script>
        window.addEventListener('hideModalScales', event => {
            const modalScale = new Modal(document.getElementById('modal-scales-quote'));
            modalScale.hide();
            document.querySelector("body > div[modal-backdrop]")?.remove()
        })
        window.addEventListener('showModalScales', event => {
            const modalScale = new Modal(document.getElementById('modal-scales-quote'), {
                backdrop: 'static'
            });
            modalScale.show();
        })
        window.addEventListener('showModalImage', event => {
            const modalImage = new Modal(document.getElementById('modal-image-quote'), {
                backdrop: 'static'
            });
            modalImage.show();
        })
        window.addEventListener('hideModalImage', event => {
            const modalImage = new Modal(document.getElementById('modal-image-quote'));
            modalImage.hide();
            document.querySelector("body > div[modal-backdrop]")?.remove()
        })

        window.addEventListener('openConfirmDelete', event => {
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
                    let respuesta = @this.deleteScale(event.detail.id)
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
                            Swal.fire('¡Error al elimiar el producto!', '', 'error')
                        });
                }
            })
        })
    </script>
</div>
