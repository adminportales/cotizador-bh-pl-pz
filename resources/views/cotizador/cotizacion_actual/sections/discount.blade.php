<div class="col-span-4 lg:col-span-1">
    <div class="bg-white shadow-md rounded-md px-6 py-4">
        <p class="text-black text-center text-lg">Total de la cotizacion</p>
        @if (!$quoteByScales)
            <button
                class="bg-gray-200 hover:bg-gray-200 text-black font-bold py-2 px-4 rounded w-full  {{ $cotizacion->discount }}"
                data-modal-target="default-modal" data-modal-toggle="default-modal">
                Agregar Descuento
            </button>
            <div wire:ignore.self id="default-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true"
                class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div class="relative w-full max-w-2xl max-h-full">
                    <!-- Modal content -->
                    <div class="relative  bg-gray-50 rounded-lg shadow dark:bg-gray-700">
                        <!-- Modal header -->
                        <div class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-xl font-semibold   text-black">
                                Agrega tu descuento
                            </h3>
                            <button type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                data-modal-hide="default-modal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-3">
                            <label for="countries" class=" mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo
                                de
                                Descuento</label>
                            <select id="countries"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected>Tipo de Descuento</option>
                                {{ $cotizacion->type }}
                                <option value="Seleccione">Seleccione</option>
                                <option value="valorfijo">Valor Fijo</option>
                                <option value="porcentaje">Porcentaje</option>
                            </select>
                            <label for="">Cantidad</label>
                            <input class="w-full py-1 text-center rounded-lg ring-2 ring-stone-950 ring-inset "
                                type="number" name="cantidad">



                            <button type="button"
                                class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-4 py-2 rounded-md"
                                data-dismiss="modal">Salir</button>
                            @if ($cotizacion->discount)
                                <button type="button"
                                    class="bg-yellow-500 text-white hover:bg-yellow-600 px-4 py-2 rounded-md"
                                    wire:click="addDiscount">Editar</button>
                                <button type="button"
                                    class="bg-red-500 text-white hover:bg-red-600 px-4 py-2 rounded-md"
                                    wire:click="eliminarDescuento">Eliminar</button>
                            @else
                                <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded-md m-2"
                                    wire:click="addDiscount">Guardar</button>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="content-between">
            <div class="flex justify-between">
                <span class="text-black"> Subtotal:</span>
                <span class="text-black"> $ {{ $totalQuote }}</span>

            </div>
            @if (!$quoteByScales)
                <div class="flex justify-between">
                    <span class="text-black"> Descuento: </span>
                    <span class="text-black text-right"> $
                        {{ $discount }}</span>
                </div>
            @endif
            <hr>
            <div class="flex justify-between">
                <span class="text-black">Total:</span>
                <span class="text-black text-right">$ {{ $totalQuote - $discount }}</span>
            </div>
        </div>
        <div class="justify-items-center bg-gray-200 text-center p-2 font-bold">
            <a href="{{ route('finalizar') }}" class="">Finalizar
                Cotizacion</a>
        </div>
    </div>
</div>
